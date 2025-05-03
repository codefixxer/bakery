<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Recipe;
use App\Models\LaborCost;
use App\Models\ReturnedGood;
use App\Models\ExternalSupply;

class ExternalSuppliesController extends Controller
{
    /**
     * Display a combined listing of this user’s supplies and returns, grouped by client and date.
     */
    public function index()
    {
        $userId = Auth::id();

        $supplies = ExternalSupply::with('client', 'recipes.recipe')
            ->where('user_id', $userId)
            ->get();

        $returns = ReturnedGood::with('client', 'recipes.recipe')
            ->where('user_id', $userId)
            ->get();

        $all = collect();

        foreach ($supplies as $supply) {
            $all->push([
                'type'               => 'supply',
                'client'             => $supply->client->name,
                'date'               => $supply->supply_date->toDateString(),
                'external_supply_id' => $supply->id,
                'lines'              => $supply->recipes,
                'revenue'            => $supply->total_amount,
            ]);
        }

        foreach ($returns as $return) {
            $returnedLines = $return->recipes->map(fn($r) => $r->supplyLine);
            $all->push([
                'type'               => 'return',
                'client'             => $return->client->name,
                'date'               => $return->return_date->toDateString(),
                'external_supply_id' => $return->external_supply_id,
                'lines'              => $returnedLines,
                'revenue'            => -1 * $return->total_amount,
            ]);
        }

        $grouped = $all
            ->sortByDesc('date')
            ->groupBy('client')
            ->map(fn($byClient) => $byClient->groupBy('date'));

        return view('frontend.external-supplies.index', ['all' => $grouped]);
    }
    public function show(ExternalSupply $externalSupply)
    {
        // Eager‐load client and line‐items (recipes)
        $externalSupply->load('client', 'lines.recipe');

        return view('frontend.external-supplies.show', compact('externalSupply'));
    }
    /**
     * Show the form for creating a new external supply.
     */
    public function create()
    {
        $userId    = Auth::id();
        $laborCost = LaborCost::first();

        $clients   = Client::where('user_id', $userId)->get();
        $recipes   = Recipe::where('labor_cost_mode', 'external')
                            ->where('user_id', $userId)
                            ->get();

        $templates = ExternalSupply::where('save_template', true)
                                   ->where('user_id', $userId)
                                   ->pluck('supply_name', 'id');

        return view('frontend.external-supplies.create', compact(
            'laborCost', 'clients', 'recipes', 'templates'
        ));
    }

    /**
     * Store a newly created external supply in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supply_name'            => 'nullable|string|max:255',
            'template_action'        => 'nullable|in:none,template,both',
            'client_id'              => 'required|exists:clients,id',
            'supply_date'            => 'required|date',
            'recipes'                => 'required|array|min:1',
            'recipes.*.id'           => 'required|exists:recipes,id',
            'recipes.*.price'        => 'required|numeric|min:0',
            'recipes.*.qty'          => 'required|integer|min:0',
            'recipes.*.total_amount' => 'required|numeric|min:0',
        ]);

        $saveTemplate = in_array($data['template_action'], ['template','both']);
        $totalAmount  = array_sum(array_column($data['recipes'], 'total_amount'));

        $supply = ExternalSupply::create([
            'client_id'     => $data['client_id'],
            'supply_name'   => $data['supply_name'],
            'supply_date'   => $data['supply_date'],
            'total_amount'  => $totalAmount,
            'save_template' => $saveTemplate,
            'user_id'       => Auth::id(),
        ]);

        foreach ($data['recipes'] as $row) {
            $supply->recipes()->create([
                'recipe_id'    => $row['id'],
                'category'     => $row['category'] ?? '',
                'price'        => $row['price'],
                'qty'          => $row['qty'],
                'total_amount' => $row['total_amount'],
                'user_id'      => Auth::id(),
            ]);
        }

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply saved successfully!');
    }

    /**
     * Return one template’s data as JSON (for AJAX).
     */
    public function getTemplate($id)
    {
        $userId = Auth::id();

        $supply = ExternalSupply::with('recipes')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $rows = $supply->recipes->map(fn($r) => [
            'recipe_id'    => $r->recipe_id,
            'price'        => $r->price,
            'qty'          => $r->qty,
            'total_amount' => $r->total_amount,
        ]);

        return response()->json([
            'supply_name'     => $supply->supply_name,
            'supply_date'     => $supply->supply_date->format('Y-m-d'),
            'template_action' => $supply->save_template ? 'template' : 'none',
            'rows'            => $rows,
        ]);
    }

    /**
     * Show the form for editing the specified external supply.
     */
    public function edit(ExternalSupply $externalSupply)
    {
        if ($externalSupply->user_id !== Auth::id()) {
            abort(403);
        }

        $userId     = Auth::id();
        $laborCost  = LaborCost::first();
        $clients    = Client::where('user_id', $userId)->get();
        $recipes    = Recipe::where('labor_cost_mode', 'external')
                             ->where('user_id', $userId)
                             ->get();
        $templates  = ExternalSupply::where('save_template', true)
                                    ->where('user_id', $userId)
                                    ->pluck('supply_name', 'id');

        $externalSupply->load('recipes.recipe');

        return view('frontend.external-supplies.create', compact(
            'externalSupply', 'laborCost', 'clients', 'recipes', 'templates'
        ));
    }

    /**
     * Update the specified external supply in storage.
     */
    public function update(Request $request, ExternalSupply $externalSupply)
    {
        if ($externalSupply->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'supply_name'            => 'required|string|max:255',
            'template_action'        => 'required|in:none,template,both',
            'client_id'              => 'required|exists:clients,id',
            'supply_date'            => 'required|date',
            'recipes'                => 'required|array|min:1',
            'recipes.*.id'           => 'required|exists:recipes,id',
            'recipes.*.price'        => 'required|numeric|min:0',
            'recipes.*.qty'          => 'required|integer|min:0',
            'recipes.*.total_amount' => 'required|numeric|min:0',
        ]);

        $saveTemplate = in_array($data['template_action'], ['template','both']);
        $totalAmount  = array_sum(array_column($data['recipes'], 'total_amount'));

        $externalSupply->update([
            'client_id'     => $data['client_id'],
            'supply_name'   => $data['supply_name'],
            'supply_date'   => $data['supply_date'],
            'total_amount'  => $totalAmount,
            'save_template' => $saveTemplate,
        ]);

        $externalSupply->recipes()->delete();
        foreach ($data['recipes'] as $row) {
            $externalSupply->recipes()->create([
                'recipe_id'    => $row['id'],
                'category'     => $row['category'] ?? '',
                'price'        => $row['price'],
                'qty'          => $row['qty'],
                'total_amount' => $row['total_amount'],
            ]);
        }

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply updated successfully!');
    }

    /**
     * Remove the specified external supply from storage.
     */
    public function destroy(ExternalSupply $externalSupply)
    {
        if ($externalSupply->user_id !== Auth::id()) {
            abort(403);
        }

        $externalSupply->recipes()->delete();
        $externalSupply->delete();

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply deleted!');
    }
}
