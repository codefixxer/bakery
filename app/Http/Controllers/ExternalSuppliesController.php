<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Recipe;
use App\Models\LaborCost;
use App\Models\ReturnedGood;
use Illuminate\Http\Request;
use App\Models\ExternalSupply;
use App\Http\Controllers\Controller;

class ExternalSuppliesController extends Controller
{
    public function index()
    {
        // 1) Load all supplies
        $supplies = ExternalSupply::with('client', 'recipes.recipe')->get();

        // 2) Load all returns
        $returns = ReturnedGood::with('client', 'recipes.recipe')->get();

        // 3) Build a single flat collection of “entries”
        $all = collect();

        foreach ($supplies as $supply) {
            $all->push([
                'type'                => 'supply',
                'client'              => $supply->client->name,
                'date'                => $supply->supply_date->toDateString(),
                'external_supply_id'  => $supply->id,
                'lines'               => $supply->recipes,
                'revenue'             => $supply->total_amount,
            ]);
        }

        foreach ($returns as $return) {
            // pull the actual supply‐line models out of the returnedGoodRecipe pivot
            $returnedLines = $return->recipes->map(fn($r) => $r->supplyLine);
        
            $all->push([
                'type'               => 'return',
                'client'             => $return->client->name,
                'date'               => $return->return_date->toDateString(),
                'external_supply_id' => $return->external_supply_id,
                'lines'              => $returnedLines,           // normalized
                'revenue'            => -1 * $return->total_amount,
            ]);
        }
        

        // 4) Sort most recent first, then group by client → date
        $grouped = $all
            ->sortByDesc('date')
            ->groupBy('client')
            ->map(fn($byClient) => $byClient->groupBy('date'));

        // 5) Send to view
        return view('frontend.external-supplies.index', [
            'all' => $grouped,
        ]);
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
