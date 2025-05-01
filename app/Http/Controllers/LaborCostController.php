<?php

namespace App\Http\Controllers;

use App\Models\LaborCost;
use Illuminate\Http\Request;

class LaborCostController extends Controller
{
    /**
     * Show the form to edit/create labor cost.
     */

    public function index()
    {
        // Grab the one and only record, or null
        $laborCost = LaborCost::first();
        return view('frontend.labor-cost.index', compact('laborCost'));
    }
    /**
     * Handle form submission, creating or updating the record.
     */
// app/Http/Controllers/LaborCostController.php

public function store(Request $request)
{
    $data = $request->validate([
        // these three must be present
        'num_chefs'     => 'required|integer|min:1',
        'opening_days'  => 'required|integer|min:1',
        'hours_per_day' => 'required|integer|min:0',

        // all the cost categories may be blank or zero
        'electricity'        => 'nullable|numeric|min:0',
        'ingredients'        => 'nullable|numeric|min:0',
        'leasing_loan'       => 'nullable|numeric|min:0',
        'packaging'          => 'nullable|numeric|min:0',
        'owner'              => 'nullable|numeric|min:0',
        'van_rental'         => 'nullable|numeric|min:0',
        'chefs'              => 'nullable|numeric|min:0',
        'shop_assistants'    => 'nullable|numeric|min:0',
        'other_salaries'     => 'nullable|numeric|min:0',
        'taxes'              => 'nullable|numeric|min:0',
        'other_categories'   => 'nullable|numeric|min:0',
        'driver_salary'      => 'nullable|numeric|min:0',

        // these are outputs, we can also default them to zero
        'monthly_bep'           => 'nullable|numeric',
        'daily_bep'             => 'nullable|numeric',
        'shop_cost_per_min'     => 'nullable|numeric',
        'external_cost_per_min' => 'nullable|numeric',
    ]);

    // **THIS SECTION**: coerce any null cost‐fields to 0
    foreach ([
        'electricity','ingredients','leasing_loan','packaging','owner',
        'van_rental','chefs','shop_assistants','other_salaries','taxes',
        'other_categories','driver_salary',
        'monthly_bep','daily_bep','shop_cost_per_min','external_cost_per_min'
    ] as $field) {
        $data[$field] = $data[$field] ?? 0;
    }

    // Now update or create your single LaborCost row
    \App\Models\LaborCost::updateOrCreate([], $data);

    return redirect()
        ->route('labor-cost.index')
        ->with('success', 'Labor & BEP details saved.');
}

}
