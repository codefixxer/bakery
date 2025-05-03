<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Recipe;
use App\Models\ReturnedGood;
use App\Models\ExternalSupply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturnedGoodController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // only this user’s clients for the filter dropdown
        $clients = Client::where('user_id', $userId)
                         ->orderBy('name')
                         ->get();

        // base query for this user’s returned goods
        $rgQuery = ReturnedGood::with('client', 'externalSupply')
                               ->where('user_id', $userId);

        if ($request->filled('client_id')) {
            $rgQuery->where('client_id', $request->client_id);
        }
        if ($request->filled('start_date')) {
            $rgQuery->where('return_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $rgQuery->where('return_date', '<=', $request->end_date);
        }

        $returnedGoods = $rgQuery->orderBy('return_date', 'desc')
                                 ->paginate(15);

        // only this user’s supplies for comparison
        $sups = ExternalSupply::where('user_id', $userId)
            ->selectRaw('supply_date as date, SUM(total_amount) as total_supply')
            ->groupBy('supply_date')
            ->get();

        $report = $sups->map(fn($supply) => (object)[
            'date'         => $supply->date,
            'total_supply' => $supply->total_supply,
            'total_return' => $returnedGoods->where('return_date', $supply->date)->sum('total_amount'),
            'net'          => $supply->total_supply - $returnedGoods->where('return_date', $supply->date)->sum('total_amount'),
        ]);

        $grandSupply = $sups->sum('total_supply');
        $grandReturn = $returnedGoods->sum('total_amount');
        $grandNet    = $grandSupply - $grandReturn;

        return view('frontend.returned-goods.index', compact(
            'clients', 'returnedGoods', 'report', 'grandSupply', 'grandReturn', 'grandNet'
        ));
    }

    public function create(Request $request)
    {
        $userId = Auth::id();

        $externalSupplyId = $request->query('external_supply_id');
        $externalSupply   = ExternalSupply::with('recipes.recipe')
            ->where('user_id', $userId)
            ->findOrFail($externalSupplyId);

        return view('frontend.returned-goods.form', [
            'client'             => $externalSupply->client,
            'recipes'            => $externalSupply->recipes,
            'external_supply_id' => $externalSupplyId,
            'externalSupply'     => $externalSupply,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'             => 'required|exists:clients,id',
            'external_supply_id'    => 'required|exists:external_supplies,id',
            'return_date'           => 'required|date',
            'recipes'               => 'required|array|min:1',
            'recipes.*.price'       => 'required|numeric|min:0',
            'recipes.*.qty'         => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // 1) Create the master return record
        $return = ReturnedGood::create([
            'client_id'           => $data['client_id'],
            'external_supply_id'  => $data['external_supply_id'],
            'return_date'         => $data['return_date'],
            'total_amount'        => 0,
            'user_id'             => $userId,
        ]);

        // 2) Loop each supply‐line returned
        $grandTotal = 0;
        foreach ($request->recipes as $supplyLineId => $row) {
            $qty       = (int) $row['qty'];
            $price     = (float) $row['price'];
            $lineTotal = round($qty * $price, 2);

            $return->lines()->create([
                'external_supply_recipe_id' => $supplyLineId,
                'price'                     => $price,
                'qty'                       => $qty,
                'total_amount'              => $lineTotal,
            ]);

            $grandTotal += $lineTotal;
        }

        // 3) Update the overall total
        $return->update(['total_amount' => $grandTotal]);

        return redirect()->route('external-supplies.index');
    }

    public function edit(ReturnedGood $returnedGood)
    {
        $userId = Auth::id();
        if ($returnedGood->user_id !== $userId) {
            abort(403);
        }

        // only this user’s clients & recipes
        $clients = Client::where('user_id', $userId)
                         ->orderBy('name')
                         ->get();

        $recipes = Recipe::where('user_id', $userId)
                         ->orderBy('recipe_name')
                         ->get();

        return view('frontend.returned-goods.create', compact('returnedGood', 'clients', 'recipes'));
    }

    public function update(Request $request, ReturnedGood $returnedGood)
    {
        $userId = Auth::id();
        if ($returnedGood->user_id !== $userId) {
            abort(403);
        }

        $data = $request->validate([
            'client_id'             => 'required|exists:clients,id',
            'return_date'           => 'required|date',
            'recipes'               => 'required|array|min:1',
            'recipes.*.id'          => 'required|exists:recipes,id',
            'recipes.*.price'       => 'required|numeric|min:0',
            'recipes.*.qty'         => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($data, $returnedGood) {
            $returnedGood->update([
                'client_id'   => $data['client_id'],
                'return_date' => $data['return_date'],
                'total_amount'=> array_sum(array_column($data['recipes'], 'total_amount')),
            ]);

            $returnedGood->recipes()->delete();
            foreach ($data['recipes'] as $line) {
                $returnedGood->recipes()->create([
                    'recipe_id'    => $line['id'],
                    'price'        => $line['price'],
                    'qty'          => $line['qty'],
                    'total_amount'=> $line['total_amount'],
                ]);
            }
        });

        return redirect()->route('external-supplies.index')
                         ->with('success', 'Returned goods updated.');
    }

    public function destroy(ReturnedGood $returnedGood)
    {
        $userId = Auth::id();
        if ($returnedGood->user_id !== $userId) {
            abort(403);
        }

        $returnedGood->delete();

        return redirect()->route('returned-goods.index')
                         ->with('success', 'Returned goods deleted.');
    }
}
