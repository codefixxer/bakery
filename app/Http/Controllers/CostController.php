<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Income;
// use App\Models\CostCostCategory;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CostController extends Controller
{
    public function create()
    {
        $categories = CostCategory::all();
        return view('frontend.costs.create', compact('categories'));
    }



    

 


    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier'       => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'category_id'    => 'required|exists:cost_categories,id',
            'other_category' => 'nullable|string|max:255',
        ]);

        Cost::create($data);

        return redirect()->route('costs.dashboard')
                         ->with('success','Cost added!');
    }



    public function index()
{
    $costs = Cost::with('category')->latest()->get();
    return view('frontend.costs.index', compact('costs'));
}

public function edit(Cost $cost)
{
    $categories = CostCategory::all();
    return view('frontend.costs.create', compact('cost', 'categories'));
}


public function update(Request $request, Cost $cost)
{
    $data = $request->validate([
        'supplier'     => 'required|string|max:255',
        'amount'       => 'required|numeric|min:0',
        'due_date'     => 'required|date',
        'category_id'  => 'required|exists:categories,id',
        'other_category' => 'nullable|string|max:255',
    ]);

    $cost->update($data);

    return redirect()->route('costs.index')->with('success', 'Cost updated successfully!');
}


public function destroy(\App\Models\Cost $cost)
{
    $cost->delete();
    return redirect()->route('costs.index')->with('success', 'Cost deleted successfully!');
}








public function dashboard(Request $request)
{
    $year     = $request->query('y', now()->year);
    $month    = $request->query('m', now()->month);
    $lastYear = $year - 1;

    $categories = CostCategory::orderBy('name')->get();

    // 1) per‐category for this month (as before)
    $raw = Cost::whereYear('due_date', $year)
        ->whereMonth('due_date', $month)
        ->selectRaw('category_id, SUM(amount) as total')
        ->groupBy('category_id')
        ->pluck('total', 'category_id');

    // 2) monthly cost comparison
    $costsThisYear = Cost::whereYear('due_date', $year)
        ->selectRaw('MONTH(due_date) as m, SUM(amount) as total')
        ->groupBy('m')
        ->pluck('total', 'm');

    $costsLastYear = Cost::whereYear('due_date', $lastYear)
        ->selectRaw('MONTH(due_date) as m, SUM(amount) as total')
        ->groupBy('m')
        ->pluck('total', 'm');

    // 3) income this month vs last year (assuming you have an Income model)
    $incomeThisMonth    = Income::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->sum('amount');

    $incomeLastYearSame = Income::whereYear('date', $lastYear)
        ->whereMonth('date', $month)
        ->sum('amount');

    // 4) NEW: totals for the whole year
    $totalCostYear   = Cost::whereYear('due_date', $year)->sum('amount');
    $totalIncomeYear = Income::whereYear('date', $year)->sum('amount');

    return view('frontend.costs.dashboard', compact(
        'year','month','lastYear',
        'categories','raw',
        'costsThisYear','costsLastYear',
        'incomeThisMonth','incomeLastYearSame',
        'totalCostYear','totalIncomeYear'
    ));
}



}
