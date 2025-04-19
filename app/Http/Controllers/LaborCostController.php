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
        $laborCost = LaborCost::first();
        return view('frontend.labor-cost.index', compact('laborCost'));
    }

    /**
     * Handle form submission, creating or updating the record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cost_per_minute' => 'required|numeric|min:0',
        ]);

        // update existing or create new (no matching attributes => always first record)
        LaborCost::updateOrCreate(
            [], // no conditions, so it picks first row or creates one
            ['cost_per_minute' => $data['cost_per_minute']]
        );

        return redirect()
            ->route('labor-cost.index')
            ->with('success', 'Labor cost per minute saved.');
    }
}
