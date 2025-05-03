<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Recipe;
use App\Models\Equipment;
use App\Models\PastryChef;
use App\Models\Production;
use App\Models\ProductionDetail;

class ProductionController extends Controller
{
    /**
     * Return one template’s data as JSON (for AJAX),
     * but only if it belongs to the current user.
     */
    public function getTemplate($id)
    {
        $userId = Auth::id();

        $production = Production::with('details')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $details = $production->details->map(function($d) {
            return [
                'recipe_id'        => $d->recipe_id,
                'chef_id'          => $d->pastry_chef_id,
                'quantity'         => $d->quantity,
                'execution_time'   => $d->execution_time,
                'equipment_ids'    => $d->equipment_ids
                                        ? explode(',', $d->equipment_ids)
                                        : [],
                'potential_revenue'=> $d->potential_revenue,
            ];
        });

        return response()->json($details);
    }


    public function show(Production $production)
    {
        // ensure equipmentMap is available to the view
        $equipmentMap = config('production.equipment_map'); // or however you build this
        return view('frontend.production.show', compact('production', 'equipmentMap'));
    }

    /**
     * Display a listing of the logged‑in user’s productions.
     */
    public function index()
    {
        $userId       = Auth::id();
        $productions  = Production::with(['details.recipe', 'details.chef'])
                                 ->where('user_id', $userId)
                                 ->latest()
                                 ->get();

        $equipmentMap = Equipment::where('user_id', $userId)
                                 ->pluck('name', 'id')
                                 ->toArray();

        return view('frontend.production.index', compact('productions', 'equipmentMap'));
    }

    /**
     * Show the form for creating a new production,
     * with only this user’s recipes, chefs, and templates.
     */
    public function create()
    {
        $userId     = Auth::id();

        $recipes    = Recipe::where('labor_cost_mode', 'shop')
                            ->where('user_id', $userId)
                            ->get();

        $chefs      = PastryChef::where('user_id', $userId)
                               ->get();

        $equipments = Equipment::where('user_id', $userId)
                               ->get();

        $templates  = Production::where('save_template', true)
                                ->where('user_id', $userId)
                                ->pluck('production_name', 'id');

        return view('frontend.production.create', compact(
            'recipes', 'chefs', 'equipments', 'templates'
        ));
    }

    /**
     * Store a newly created production in storage,
     * stamping it with the current user’s ID.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'production_name'   => 'nullable|string|max:255',
            'production_date'   => 'required|date',
            'template_action'   => 'nullable|in:none,template,both',
            'recipe_id'         => 'required|array',
            'pastry_chef_id'    => 'required|array',
            'quantity'          => 'required|array',
            'execution_time'    => 'required|array',
            'equipment_ids'     => 'required|array',
            'potential_revenue' => 'required|array',
            'total_revenue'     => 'required|numeric|min:0',
        ]);

        $saveTemplate = in_array($data['template_action'], ['template', 'both']);
        $total = $data['total_revenue'];

        $production = Production::create([
            'production_name'         => $data['production_name'],
            'production_date'         => $data['production_date'],
            'total_potential_revenue' => $total,
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
                'user_id'           => Auth::id(), // ✅ Add this line
            ]);
            
        }

        return redirect()
            ->route('production.index')
            ->with('success', 'Production saved successfully!');
    }

    /**
     * Show the form for editing the specified production,
     * only if it belongs to the logged‑in user.
     */
    public function edit($id)
    {
        $userId     = Auth::id();
        $production = Production::with('details')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $recipes    = Recipe::where('labor_cost_mode', 'shop')
                            ->where('user_id', $userId)
                            ->get();

        $chefs      = PastryChef::where('user_id', $userId)
                               ->get();

        $equipments = Equipment::where('user_id', $userId)
                               ->get();

        $templates  = Production::where('save_template', true)
                                ->where('user_id', $userId)
                                ->pluck('production_name', 'id');

        return view('frontend.production.create', compact(
            'production', 'recipes', 'chefs', 'equipments', 'templates'
        ));
    }

    /**
     * Update the specified production in storage,
     * only if it belongs to the logged‑in user.
     */
    public function update(Request $request, $id)
    {
        $userId     = Auth::id();
        $production = Production::where('user_id', $userId)
                                ->findOrFail($id);

        $data = $request->validate([
            'production_name'   => 'required|string|max:255',
            'production_date'   => 'required|date',
            'template_action'   => 'required|in:none,template,both',
            'recipe_id'         => 'required|array',
            'pastry_chef_id'    => 'required|array',
            'quantity'          => 'required|array',
            'execution_time'    => 'required|array',
            'equipment_ids'     => 'required|array',
            'potential_revenue' => 'required|array',
            'total_revenue'     => 'required|numeric|min:0',
        ]);

        $saveTemplate = in_array($data['template_action'], ['template', 'both']);
        $production->update([
            'production_name'         => $data['production_name'],
            'production_date'         => $data['production_date'],
            'total_potential_revenue' => $data['total_revenue'],
            'save_template'           => $saveTemplate,
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
            ]);
        }

        return redirect()
            ->route('production.index')
            ->with('success', 'Production updated successfully!');
    }

    /**
     * Remove the specified production from storage,
     * only if it belongs to the logged‑in user.
     */
    public function destroy($id)
    {
        $production = Production::where('user_id', Auth::id())
                                ->findOrFail($id);

        $production->delete();

        return redirect()
            ->route('production.index')
            ->with('success', 'Production deleted successfully!');
    }
}
