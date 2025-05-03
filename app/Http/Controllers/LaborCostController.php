<?php

namespace App\Http\Controllers;

use App\Models\LaborCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaborCostController extends Controller
{
    /**
     * Show the form to edit/create labor cost for the logged‑in user.
     */
    public function index()
    {
        // Grab the current user's LaborCost record (or null)
        $laborCost = LaborCost::where('user_id', Auth::id())->first();

        return view('frontend.labor-cost.index', compact('laborCost'));
    }

    /**
     * Handle form submission, creating or updating the record for this user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'num_chefs'     => 'required|integer|min:1',
            'opening_days'  => 'required|integer|min:1',
            'hours_per_day' => 'required|integer|min:0',

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

            'monthly_bep'           => 'nullable|numeric',
            'daily_bep'             => 'nullable|numeric',
            'shop_cost_per_min'     => 'nullable|numeric',
            'external_cost_per_min' => 'nullable|numeric',
        ]);

        // Coerce any null cost‑fields to 0
        foreach ([
            'electricity','ingredients','leasing_loan','packaging','owner',
            'van_rental','chefs','shop_assistants','other_salaries','taxes',
            'other_categories','driver_salary',
            'monthly_bep','daily_bep','shop_cost_per_min','external_cost_per_min'
        ] as $field) {
            $data[$field] = $data[$field] ?? 0;
        }

        // Stamp with the current user's ID
        $data['user_id'] = Auth::id();

        // Update or create the LaborCost row scoped to this user
        LaborCost::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return redirect()
            ->route('labor-cost.index')
            ->with('success', 'Labor & BEP details saved.');
    }
}
