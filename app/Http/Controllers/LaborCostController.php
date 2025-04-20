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
        // Grab the first (and only) record, or null
         
        return view('frontend.labor-cost.index');
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
            'electricity'           => 'required|numeric',
            'ingredients'           => 'required|numeric',
            'leasing_loan'          => 'required|numeric',
            'packaging'             => 'required|numeric',
            'owner'                 => 'required|numeric',
            'van_rental'            => 'required|numeric',
            'chefs'                 => 'required|numeric',
            'shop_assistants'       => 'required|numeric',
            'other_salaries'        => 'required|numeric',
            'taxes'                 => 'required|numeric',
            'other_categories'      => 'required|numeric',
            'driver_salary'         => 'required|numeric',
            'monthly_bep'           => 'required|numeric',
            'daily_bep'             => 'required|numeric',
            'shop_cost_per_min'     => 'required|numeric',
            'external_cost_per_min' => 'required|numeric',
        ]);

        LaborCost::create($data);
        return redirect()
            ->route('labor-cost.index')
            ->with('success', 'Labour cost details saved.');
    }
}
