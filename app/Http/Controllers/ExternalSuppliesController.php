<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Recipe;
use App\Models\LaborCost;
use Illuminate\Http\Request;
use App\Models\ExternalSupply;
use App\Http\Controllers\Controller;

class ExternalSuppliesController extends Controller
{
    public function index()
    {
        $externalSupplies = ExternalSupply::with(['client', 'recipes.recipe'])
                                          ->latest()
                                          ->get();

        return view('frontend.external-supplies.index', compact('externalSupplies'));
    }

    public function create()
    {
        $laborCost = LaborCost::first();
        $clients   = Client::all();
        $recipes = Recipe::where('labor_cost_mode', 'external')->get();

        return view('frontend.external-supplies.create', compact('laborCost', 'clients', 'recipes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'           => 'required|exists:clients,id',
            'supply_date'         => 'required|date',
            'total_amount'        => 'required|numeric|min:0',
            'recipes'             => 'required|array|min:1',
            'recipes.*.id'        => 'required|exists:recipes,id',
            'recipes.*.category'  => 'nullable|string',
            'recipes.*.price'     => 'required|numeric|min:0',
            'recipes.*.qty'       => 'required|integer|min:0',
            'recipes.*.total_amount' => 'required|numeric|min:0',
        ]);

        // Create the ExternalSupply
        $supply = ExternalSupply::create([
            'client_id'    => $data['client_id'],
            'supply_date'  => $data['supply_date'],
            'total_amount' => $data['total_amount'],
        ]);

        // Attach each recipe
        foreach ($data['recipes'] as $recipeData) {
            $supply->recipes()->create([
                'recipe_id'    => $recipeData['id'],
                'category'     => $recipeData['category'] ?? '',
                'price'        => $recipeData['price'],
                'qty'          => $recipeData['qty'],
                'total_amount' => $recipeData['total_amount'],
            ]);
        }

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply saved successfully!');
    }

    public function edit(ExternalSupply $externalSupply)
    {
        $externalSupply->load(['recipes', 'recipes.recipe']);
        $clients   = Client::all();
        $recipes   = Recipe::all();
        $laborCost = LaborCost::first();

        return view('frontend.external-supplies.create', compact(
            'externalSupply',
            'clients',
            'recipes',
            'laborCost'
        ));
    }

    public function update(Request $request, ExternalSupply $externalSupply)
    {
        $data = $request->validate([
            'client_id'           => 'required|exists:clients,id',
            'supply_date'         => 'required|date',
            'total_amount'        => 'required|numeric|min:0',
            'recipes'             => 'required|array|min:1',
            'recipes.*.id'        => 'required|exists:recipes,id',
            'recipes.*.category'  => 'nullable|string',
            'recipes.*.price'     => 'required|numeric|min:0',
            'recipes.*.qty'       => 'required|integer|min:0',
            'recipes.*.total_amount' => 'required|numeric|min:0',
        ]);

        // Update the ExternalSupply
        $externalSupply->update([
            'client_id'    => $data['client_id'],
            'supply_date'  => $data['supply_date'],
            'total_amount' => $data['total_amount'],
        ]);

        // Remove old recipe entries
        $externalSupply->recipes()->delete();

        // Recreate recipe entries
        foreach ($data['recipes'] as $recipeData) {
            $externalSupply->recipes()->create([
                'recipe_id'    => $recipeData['id'],
                'category'     => $recipeData['category'] ?? '',
                'price'        => $recipeData['price'],
                'qty'          => $recipeData['qty'],
                'total_amount' => $recipeData['total_amount'],
            ]);
        }

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply updated successfully.');
    }

    public function destroy(ExternalSupply $externalSupply)
    {
        // Delete related recipe rows, then the supply
        $externalSupply->recipes()->delete();
        $externalSupply->delete();

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply deleted!');
    }
}
