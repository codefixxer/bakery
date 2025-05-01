<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Equipment;
use App\Models\PastryChef;
use App\Models\Production;
use App\Models\ProductionDetail;

class ProductionController extends Controller
{


    public function getTemplate($id)
    {
        $production = Production::with('details')->findOrFail($id);

        $details = $production->details->map(function($d) {
            return [
                'recipe_id'       => $d->recipe_id,
                'chef_id'         => $d->pastry_chef_id,
                'quantity'        => $d->quantity,
                'execution_time'  => $d->execution_time,
                'equipment_ids'   => $d->equipment_ids
                                       ? explode(',', $d->equipment_ids)
                                       : [],
                'potential_revenue' => $d->potential_revenue,
            ];
        });

        return response()->json($details);
    }
    /**
     * Display a listing of productions.
     */
    public function index()
    {
        // Eager load details with recipe and chef
        $productions  = Production::with(['details.recipe', 'details.chef'])
                                 ->latest()
                                 ->get();

        // Optional equipment map for display
        $equipmentMap = Equipment::pluck('name', 'id')->toArray();

        return view('frontend.production.index', compact('productions', 'equipmentMap'));
    }

    /**
     * Show the form for creating a new production.
     */
    public function create()
    {
        return view('frontend.production.create', [
            'recipes'    => Recipe::all(),
            'chefs'      => PastryChef::all(),
            'equipments' => Equipment::all(),
            // new: only those you’ve marked as templates
            'templates'  => Production::where('save_template', true)
                                      ->pluck('production_name', 'id'),
        ]);
    }
    

    /**
     * Store a newly created production in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'production_name'      => 'required|string|max:255',
            'production_date'      => 'required|date',
            'template_action'      => 'required|in:none,template,both',
            'recipe_id'            => 'required|array',
            'pastry_chef_id'       => 'required|array',
            'quantity'             => 'required|array',
            'execution_time'       => 'required|array',
            'equipment_ids'        => 'required|array',
            'potential_revenue'    => 'required|array',
            'total_revenue'        => 'required|numeric|min:0',
        ]);

        // Determine whether to save as template
        $saveTemplate = in_array($request->template_action, ['template', 'both']);

        // Create production header
        $production = Production::create([
            'production_name'         => $request->production_name,
            'production_date'         => $request->production_date,
            'total_potential_revenue' => $request->total_revenue,
            'save_template'           => $saveTemplate,
        ]);

        // Create production details
        foreach ($request->recipe_id as $i => $recipeId) {
            ProductionDetail::create([
                'production_id'     => $production->id,
                'recipe_id'         => $recipeId,
                'pastry_chef_id'    => $request->pastry_chef_id[$i],
                'quantity'          => $request->quantity[$i],
                'execution_time'    => $request->execution_time[$i],
                'equipment_ids'     => implode(',', $request->equipment_ids[$i] ?? []),
                'potential_revenue' => $request->potential_revenue[$i],
            ]);
        }

        return redirect()
            ->route('production.index')
            ->with('success', 'Production saved successfully!');
    }

    /**
     * Show the form for editing the specified production.
     */
    public function edit($id)
    {
        $production = Production::with('details')->findOrFail($id);

        return view('frontend.production.create', [
            'production' => $production,
            'recipes'    => Recipe::all(),
            'chefs'      => PastryChef::all(),
            'equipments' => Equipment::all(),
            'templates'  => Production::where('save_template', true)
            ->pluck('production_name', 'id'),
        ]);
    }

    /**
     * Update the specified production in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'production_name'      => 'required|string|max:255',
            'production_date'      => 'required|date',
            'template_action'      => 'required|in:none,template,both',
            'recipe_id'            => 'required|array',
            'pastry_chef_id'       => 'required|array',
            'quantity'             => 'required|array',
            'execution_time'       => 'required|array',
            'equipment_ids'        => 'required|array',
            'potential_revenue'    => 'required|array',
            'total_revenue'        => 'required|numeric|min:0',
        ]);

        $production   = Production::findOrFail($id);
        $saveTemplate = in_array($request->template_action, ['template', 'both']);

        // Update production header
        $production->update([
            'production_name'         => $request->production_name,
            'production_date'         => $request->production_date,
            'total_potential_revenue' => $request->total_revenue,
            'save_template'           => $saveTemplate,
        ]);

        // Delete old details and recreate
        $production->details()->delete();

        foreach ($request->recipe_id as $i => $recipeId) {
            ProductionDetail::create([
                'production_id'     => $production->id,
                'recipe_id'         => $recipeId,
                'pastry_chef_id'    => $request->pastry_chef_id[$i],
                'quantity'          => $request->quantity[$i],
                'execution_time'    => $request->execution_time[$i],
                'equipment_ids'     => implode(',', $request->equipment_ids[$i] ?? []),
                'potential_revenue' => $request->potential_revenue[$i],
            ]);
        }

        return redirect()
            ->route('production.index')
            ->with('success', 'Production updated successfully!');
    }

    /**
     * Remove the specified production from storage.
     */
    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete(); // details cascade if FK set

        return redirect()
            ->route('production.index')
            ->with('success', 'Production deleted successfully!');
    }
}
