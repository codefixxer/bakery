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
    public function store(Request $request)
    {
        $data = $request->validate([
            'num_chefs'             => 'required|integer|min:1',
            'opening_days'          => 'required|integer|min:1',
            'hours_per_day'         => 'required|integer|min:0',

            'electricity'           => 'required|numeric|min:0',
            'ingredients'           => 'required|numeric|min:0',
            'leasing_loan'          => 'required|numeric|min:0',
            'packaging'             => 'required|numeric|min:0',
            'owner'                 => 'required|numeric|min:0',
            'van_rental'            => 'required|numeric|min:0',
            'chefs'                 => 'required|numeric|min:0',
            'shop_assistants'       => 'required|numeric|min:0',
            'other_salaries'        => 'required|numeric|min:0',
            'taxes'                 => 'required|numeric|min:0',
            'other_categories'      => 'required|numeric|min:0',
            'driver_salary'         => 'required|numeric|min:0',

            'monthly_bep'           => 'required|numeric',
            'daily_bep'             => 'required|numeric',
            'shop_cost_per_min'     => 'required|numeric',
            'external_cost_per_min' => 'required|numeric',
        ]);

        // find the first row or create it, then update with $data
        LaborCost::updateOrCreate([], $data);

        return redirect()
            ->route('labor-cost.index')
            ->with('success', 'Labor & BEP details saved.');
    }
}
