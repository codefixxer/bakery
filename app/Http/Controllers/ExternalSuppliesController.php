<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Recipe;
use App\Models\LaborCost;
use App\Models\ReturnedGood;
use Illuminate\Http\Request;
use App\Models\ExternalSupply;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExternalSuppliesController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;
    
        $groupUserIds = User::where('created_by', $groupRootId)
                            ->pluck('id')
                            ->push($groupRootId);
    
        $supplies = ExternalSupply::with('client', 'recipes.recipe', 'user')
            ->whereIn('user_id', $groupUserIds)
            ->get();
    
        $returns = ReturnedGood::with('client', 'recipes.supplyLine.recipe', 'user')
            ->whereIn('user_id', $groupUserIds)
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
                'created_by'         => $supply->user->name ?? '—',
            ]);
        }
    
        foreach ($returns as $return) {
            $returnedLines = $return->recipes->map(function ($r) {
                $recipe = $r->supplyLine->recipe ?? null;
    
                return (object)[
                    'recipe'       => $recipe,
                    'qty'          => $r->qty,
                    'total_amount' => $r->total_amount,
                ];
            });
    
            $all->push([
                'type'               => 'return',
                'client'             => $return->client->name,
                'date'               => $return->return_date->toDateString(),
                'external_supply_id' => $return->external_supply_id,
                'lines'              => $returnedLines,
                'revenue'            => -1 * $return->total_amount,
                'created_by'         => $return->user->name ?? '—',
            ]);
        }
    
        $grouped = $all
            ->sortByDesc('date')
            ->groupBy('client')
            ->map(fn($byClient) => $byClient->groupBy('date'));
    
        return view('frontend.external-supplies.index', ['all' => $grouped]);
    }
    
    
    

    public function create()
    {
        $laborCost = LaborCost::first();
        $clients   = Client::all();
        $recipes   = Recipe::where('labor_cost_mode', 'external')->get();
        $templates = ExternalSupply::where('save_template', true)
                                   ->pluck('supply_name', 'id');

        return view('frontend.external-supplies.create', compact(
            'laborCost', 'clients', 'recipes', 'templates'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supply_name'            => 'required_if:template_action,template,both|max:255',
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
            'user_id'       => auth()->id(),
        ]);
        foreach ($data['recipes'] as $row) {
            $supply->recipes()->create([
                'recipe_id'    => $row['id'],
                'category'     => $row['category'] ?? '',
                'price'        => $row['price'],
                'qty'          => $row['qty'],
                'total_amount' => $row['total_amount'],
                'user_id'      => auth()->id(), // ← THIS LINE IS MISSING
            ]);
        }
        

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply saved successfully!');
    }

    public function getTemplate($id)
    {
        $supply = ExternalSupply::with('recipes')->findOrFail($id);

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

    public function edit(ExternalSupply $externalSupply)
    {
        $externalSupply->load('recipes.recipe');

        $laborCost = LaborCost::first();
        $clients   = Client::all();
        $recipes   = Recipe::where('labor_cost_mode', 'external')->get();
        $templates = ExternalSupply::where('save_template', true)
                                   ->pluck('supply_name', 'id');

        return view('frontend.external-supplies.create', compact(
            'externalSupply', 'laborCost', 'clients', 'recipes', 'templates'
        ));
    }

    public function update(Request $request, ExternalSupply $externalSupply)
    {
        $data = $request->validate([
            'supply_name'            => 'required_if:template_action,template,both|max:255',
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
                'user_id'      => auth()->id(),
            ]);
        }

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply updated successfully!');
    }

    public function destroy(ExternalSupply $externalSupply)
    {
        $externalSupply->recipes()->delete();
        $externalSupply->delete();

        return redirect()
            ->route('external-supplies.index')
            ->with('success', 'External supply deleted!');
    }
}
