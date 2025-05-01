<?php

// app/Http/Controllers/ReturnedGoodController.php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Recipe;
use App\Models\ReturnedGood;
use Illuminate\Http\Request;
use App\Models\ExternalSupply;
use App\Models\ReturnedGoodRecipe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ExternalSupplyRecipe;

class ReturnedGoodController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all clients for filtering
        $clients = Client::orderBy('name')->get();
        
        // Filtered, paginated returns with related external supplies
        $rgQuery = ReturnedGood::with('client', 'externalSupply');  // Added externalSupply relationship
    
        if ($request->filled('client_id')) {
            $rgQuery->where('client_id', $request->client_id);
        }
        if ($request->filled('start_date')) {
            $rgQuery->where('return_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $rgQuery->where('return_date', '<=', $request->end_date);
        }
    
        // Fetch the returns
        $returnedGoods = $rgQuery->orderBy('return_date', 'desc')->paginate(15);
    
        // Fetch external supplies for comparison (just as you did for returns)
        $sups = ExternalSupply::selectRaw('supply_date as date, SUM(total_amount) as total_supply')
            ->groupBy('supply_date')
            ->get();
        
        // Combine external supply and return data for daily comparison
        $report = $sups->map(fn($supply) => (object)[
            'date' => $supply->date,
            'total_supply' => $supply->total_supply,
            'total_return' => $returnedGoods->where('return_date', $supply->date)->sum('total_amount'),
            'net' => $supply->total_supply - $returnedGoods->where('return_date', $supply->date)->sum('total_amount'),
        ]);
        
        // Grand totals
        $grandSupply = $sups->sum('total_supply');
        $grandReturn = $returnedGoods->sum('total_amount');
        $grandNet = $grandSupply - $grandReturn;
    
        return view('frontend.returned-goods.index', compact(
            'clients', 'returnedGoods', 'report', 'grandSupply', 'grandReturn', 'grandNet'
        ));
    }
    
    

    // public function create()
    // {
    //     // so the view always has a $returnedGood (with ->exists === false)
    //     $returnedGood = new ReturnedGood;

    //     $clients = Client::all();
    //     $recipes = Recipe::orderBy('recipe_name')->get();

    //     return view('frontend.returned-goods.create', compact('returnedGood','clients','recipes'));
    // }


    public function create(Request $request)
    {
        $externalSupplyId = $request->query('external_supply_id');
        $externalSupply   = ExternalSupply::with('recipes.recipe')
                                         ->findOrFail($externalSupplyId);
    
        return view('frontend.returned-goods.form', [
            'client'             => $externalSupply->client,
            'recipes'            => $externalSupply->recipes,
            'external_supply_id' => $externalSupplyId,
            'externalSupply'     => $externalSupply,    // ← add this
        ]);
    }
    

  

public function store(Request $request)
{
    // 1) Create the master return record
    $return = ReturnedGood::create([
        'client_id'           => $request->client_id,
        'external_supply_id'  => $request->external_supply_id,  // ← here!
        'return_date'         => $request->return_date,
        'total_amount'        => 0,
      ]);
      

    // 2) Loop each supply‐line returned
    $grandTotal = 0;
    foreach ($request->recipes as $supplyLineId => $data) {
        $qty       = (int) ($data['qty'] ?? 0);
        if ($qty <= 0) {
            continue;
        }
        $price     = (float) $data['price'];
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
        $clients = Client::all();
        $recipes = Recipe::orderBy('recipe_name')->get();
        return view('frontend.returned-goods.create', compact('returnedGood','clients','recipes'));
    }

    public function update(Request $request, ReturnedGood $returnedGood)
    {
        $data = $request->validate([
            'client_id'             => 'required|exists:clients,id',
            'return_date'           => 'required|date',
            'recipes'               => 'required|array|min:1',
            'recipes.*.id'          => 'required|exists:recipes,id',
            'recipes.*.price'       => 'required|numeric|min:0',
            'recipes.*.qty'         => 'required|integer|min:1',
            'recipes.*.total_amount'=> 'required|numeric|min:0',
        ]);

        DB::transaction(function() use($data,$returnedGood) {
            $returnedGood->update([
                'client_id'   => $data['client_id'],
                'return_date' => $data['return_date'],
                'total_amount'=> array_sum(array_column($data['recipes'],'total_amount')),
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
                         ->with('success','Returned goods updated.');
    }

    public function destroy(ReturnedGood $returnedGood)
    {
        $returnedGood->delete();
        return redirect()->route('returned-goods.index')
                         ->with('success','Returned goods deleted.');
    }
}
