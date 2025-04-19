<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Equipment;
use App\Models\PastryChef;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\ProductionDetail;
use App\Http\Controllers\Controller;


class ProductionController extends Controller
{


    public function index()
    {
        // Eager load details along with their associated Recipe and Chef
        $productions = Production::with(['details.recipe', 'details.chef'])->latest()->get();
        
        // Equipment map built for possible equipment display (optional)
        $equipmentMap = Equipment::pluck('name', 'id')->toArray(); 

        return view('frontend.production.index', compact('productions', 'equipmentMap'));
    }

    // Show the form to create a production entry
    public function create()
    {
        return view('frontend.production.create', [
            'recipes'    => Recipe::all(),
            'chefs'      => PastryChef::all(),
            'equipments' => Equipment::all(),
        ]);
    }

    // Store production data
    public function store(Request $request)
{
    // 1. Validate the request
    $request->validate([
        'production_date'      => 'required|date',
        'recipe_id'            => 'required|array',
        'pastry_chef_id'       => 'required|array',
        'quantity'             => 'required|array',
        'execution_time'       => 'required|array',
        'equipment_ids'        => 'required|array',
        'potential_revenue'    => 'required|array',
        'total_revenue'        => 'required|numeric|min:0',
    ]);

    // 2. Create the production header
    $production = Production::create([
        'production_date'           => $request->production_date,
        'total_potential_revenue'   => $request->total_revenue,
    ]);

    // 3. Create the production details (loop each row)
    foreach ($request->recipe_id as $index => $recipeId) {
        ProductionDetail::create([
            'production_id'     => $production->id,
            'recipe_id'         => $recipeId,
            'pastry_chef_id'    => $request->pastry_chef_id[$index],
            'quantity'          => $request->quantity[$index],
            'execution_time'    => $request->execution_time[$index],
            'equipment_ids' => implode(',', $request->equipment_ids[$index] ?? []),
            'potential_revenue' => $request->potential_revenue[$index],
        ]);
    }

    return redirect()->route('production.index')->with('success', 'Production saved successfully!');
}


public function edit($id)
{
    $production = Production::with('details')->findOrFail($id);

    return view('frontend.production.create', [
        'production' => $production,
        'recipes' => Recipe::all(),
        'chefs' => PastryChef::all(),
        'equipments' => Equipment::all(),
    ]);
}



public function update(Request $request, $id)
{
    $request->validate([
        'production_date'   => 'required|date',
        'recipe_id'         => 'required|array',
        'pastry_chef_id'    => 'required|array',
        'quantity'          => 'required|array',
        'execution_time'    => 'required|array',
        'equipment_ids'     => 'required|array',
        'potential_revenue' => 'required|array',
        'total_revenue'     => 'required|numeric|min:0',
    ]);

    $production = Production::findOrFail($id);
    $production->update([
        'production_date'           => $request->production_date,
        'total_potential_revenue'   => $request->total_revenue,
    ]);

    // Delete old details
    $production->details()->delete();

    // Save new details
    foreach ($request->recipe_id as $index => $recipeId) {
        ProductionDetail::create([
            'production_id'     => $production->id,
            'recipe_id'         => $recipeId,
            'pastry_chef_id'    => $request->pastry_chef_id[$index],
            'quantity'          => $request->quantity[$index],
            'execution_time'    => $request->execution_time[$index],
            'equipment_ids'     => implode(',', $request->equipment_ids[$index] ?? []),
            'potential_revenue' => $request->potential_revenue[$index],
        ]);
    }

    return redirect()->route('production.index')->with('success', 'Production updated successfully!');
}


public function destroy($id)
{
    $production = Production::findOrFail($id);
    $production->delete(); // Laravel will also delete details if FK is set to cascade

    return redirect()->route('production.index')->with('success', 'Production deleted successfully!');
}

}
