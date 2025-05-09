<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Equipment;
use App\Models\PastryChef;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\ProductionDetail;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductionController extends Controller
{
    public function getTemplate($id)
    {
        $userId = Auth::id();

        $production = Production::with('details')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $details = $production->details->map(function($d) {
            return [
                'recipe_id'         => $d->recipe_id,
                'chef_id'           => $d->pastry_chef_id,
                'quantity'          => $d->quantity,
                'execution_time'    => $d->execution_time,
                'equipment_ids'     => $d->equipment_ids ? explode(',', $d->equipment_ids) : [],
                'potential_revenue' => $d->potential_revenue,
            ];
        });

        return response()->json($details);
    }

    public function show(Production $production)
    {
        $equipmentMap = \App\Models\Equipment::pluck('name', 'id')->toArray();
    
        return view('frontend.production.show', compact('production', 'equipmentMap'));
    }
    

    public function index(Request $request)
    {
        $user = Auth::user();
        // 1) Determine group: top‑level users see themselves + their children; child users see themselves + their creator
        $groupRootId  = $user->created_by ?? $user->id;
        $groupUserIds = User::where('created_by', $groupRootId)
                            ->pluck('id')
                            ->push($groupRootId)
                            ->unique();

        // 2) Load all productions for that group
        $productions = Production::with(['details.recipe', 'details.chef', 'user'])
                                 ->whereIn('user_id', $groupUserIds)
                                 ->latest()
                                 ->get();

        // 3) Compute total potential revenue across all details
        $totalPotentialRevenue = $productions
            ->flatMap(fn($p) => $p->details)    // flatten all detail collections
            ->sum('potential_revenue');        // sum the potential_revenue field

        // 4) Equipment map for display & filtering
        $equipmentMap = Equipment::whereIn('user_id', $groupUserIds)
                                 ->pluck('name', 'id')
                                 ->toArray();

        return view('frontend.production.index', compact(
            'productions',
            'equipmentMap',
            'totalPotentialRevenue'
        ));
    }

    

    public function create()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;
    
        $groupUserIds = \App\Models\User::where('created_by', $groupRootId)
                            ->pluck('id')
                            ->push($groupRootId);
    
        $recipes = \App\Models\Recipe::where('labor_cost_mode', 'shop')
                        ->whereIn('user_id', $groupUserIds)
                        ->get();
    
        $chefs = \App\Models\PastryChef::whereIn('user_id', $groupUserIds)->get();
        $equipments = \App\Models\Equipment::whereIn('user_id', $groupUserIds)->get();
    
        $templates = \App\Models\Production::where('save_template', true)
                        ->whereIn('user_id', $groupUserIds)
                        ->pluck('production_name', 'id');
    
        return view('frontend.production.create', compact(
            'recipes', 'chefs', 'equipments', 'templates'
        ));
    }
    

    public function store(Request $request)
    {
        $templateAction = $request->input('template_action');
        $isTemplate = in_array($templateAction, ['template', 'both']);

        $rules = [
            'production_name'   => $isTemplate ? 'required|string|max:255' : 'nullable|string|max:255',
            'production_date'   => 'required|date',
            'template_action'   => 'nullable|in:none,template,both',
            'recipe_id'         => 'required|array',
            'pastry_chef_id'    => 'required|array',
            'quantity'          => 'required|array',
            'execution_time'    => 'required|array',
            'equipment_ids'     => 'required|array',
            'potential_revenue' => 'required|array',
            'total_revenue'     => 'required|numeric|min:0',
        ];

        $data = $request->validate($rules);

        $saveTemplate = $isTemplate;

        $production = Production::create([
            'production_name'         => $data['production_name'],
            'production_date'         => $data['production_date'],
            'total_potential_revenue' => $data['total_revenue'],
            'save_template'           => $saveTemplate,
            'user_id'                 => Auth::id(),
        ]);

        foreach ($data['recipe_id'] as $i => $recipeId) {
            ProductionDetail::create([
                'production_id'     => $production->id,
                'recipe_id'         => $recipeId,
                'pastry_chef_id'    => $data['pastry_chef_id'][$i],
                'quantity'          => $data['quantity'][$i],
                'execution_time'    => $data['execution_time'][$i],
                'equipment_ids'     => implode(',', $data['equipment_ids'][$i] ?? []),
                'potential_revenue' => $data['potential_revenue'][$i],
                'user_id'           => Auth::id(),
            ]);
        }

        return redirect()->route('production.create')->with('success', 'Production saved successfully!');
    }

    public function edit($id)
    {
        $userId = Auth::id();

        $production = Production::with('details')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $recipes = Recipe::where('labor_cost_mode', 'shop')
            ->where('user_id', $userId)
            ->get();

        $chefs = PastryChef::where('user_id', $userId)->get();
        $equipments = Equipment::where('user_id', $userId)->get();

        $templates = Production::where('save_template', true)
            ->where('user_id', $userId)
            ->pluck('production_name', 'id');

        return view('frontend.production.create', compact(
            'production', 'recipes', 'chefs', 'equipments', 'templates'
        ));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $production = Production::where('user_id', $userId)
            ->findOrFail($id);

        $templateAction = $request->input('template_action');
        $isTemplate = in_array($templateAction, ['template', 'both']);

        $rules = [
            'production_name'   => $isTemplate ? 'required|string|max:255' : 'nullable|string|max:255',
            'production_date'   => 'required|date',
            'template_action'   => 'required|in:none,template,both',
            'recipe_id'         => 'required|array',
            'pastry_chef_id'    => 'required|array',
            'quantity'          => 'required|array',
            'execution_time'    => 'required|array',
            'equipment_ids'     => 'required|array',
            'potential_revenue' => 'required|array',
            'total_revenue'     => 'required|numeric|min:0',
        ];

        $data = $request->validate($rules);

        $production->update([
            'production_name'         => $data['production_name'],
            'production_date'         => $data['production_date'],
            'total_potential_revenue' => $data['total_revenue'],
            'save_template'           => $isTemplate,
        ]);

        $production->details()->delete();

        foreach ($data['recipe_id'] as $i => $recipeId) {
            ProductionDetail::create([
                'production_id'     => $production->id,
                'recipe_id'         => $recipeId,
                'pastry_chef_id'    => $data['pastry_chef_id'][$i],
                'quantity'          => $data['quantity'][$i],
                'execution_time'    => $data['execution_time'][$i],
                'equipment_ids'     => implode(',', $data['equipment_ids'][$i] ?? []),
                'potential_revenue' => $data['potential_revenue'][$i],
                'user_id'           => Auth::id(),
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Production updated successfully!');
    }

    public function destroy($id)
    {
        $production = Production::where('user_id', Auth::id())
            ->findOrFail($id);

        $production->delete();

        return redirect()->route('production.index')->with('success', 'Production deleted successfully!');
    }
}
