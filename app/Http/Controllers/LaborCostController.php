<?php

namespace App\Http\Controllers;

use App\Models\LaborCost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaborCostController extends Controller
{
    /**
     * Show the form to view/edit the shared LaborCost for your group.
     */
    public function index()
    {
        $user = Auth::user();
        // Determine the “owner” of this labor‑cost row: admin (created_by = null) or the root admin of your group
        $groupOwnerId = $user->created_by ?? $user->id;

        // Fetch that single record (or null if none yet)
        $laborCost = LaborCost::where('user_id', $groupOwnerId)
                              ->latest('updated_at')
                              ->first();

        return view('frontend.labor-cost.index', compact('laborCost'));
    }

    /**
     * Create or update the LaborCost row shared by your group.
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

        // Normalize nulls to zero
        foreach ([
            'electricity','ingredients','leasing_loan','packaging','owner',
            'van_rental','chefs','shop_assistants','other_salaries','taxes',
            'other_categories','driver_salary',
            'monthly_bep','daily_bep','shop_cost_per_min','external_cost_per_min'
        ] as $field) {
            $data[$field] = $data[$field] ?? 0;
        }

        // Determine the same group owner as in index():
        $user = Auth::user();
        $groupOwnerId = $user->created_by ?? $user->id;
        $data['user_id'] = $groupOwnerId;

        // Update existing or create if none yet, scoped to the group owner's user_id
        LaborCost::updateOrCreate(
            ['user_id' => $groupOwnerId],
            $data
        );

        return redirect()
            ->route('labor-cost.index')
            ->with('success', 'Labor & BEP details saved for your group.');
    }
}
