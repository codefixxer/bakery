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
            'cost_identifier' => 'nullable|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'category_id'    => 'required|exists:cost_categories,id',
            'other_category' => 'nullable|string|max:255',
        ]);

        Cost::create($data);

        return redirect()->route('costs.index')
            ->with('success', 'Cost added!');
    }



    public function index(Request $request)
    {

        $categories = CostCategory::all();

        // 1) Read filter_month like "2025-04", or default to current:
        $filter = $request->query('filter_month', now()->format('Y-m'));

        [$year, $month] = explode('-', $filter);

        // 2) Fetch only this year/month:
        $costs = Cost::with('category')->orderBy('due_date', 'desc')->get();


        return view('frontend.costs.index', compact('costs', 'filter', 'categories'));
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
            'cost_identifier' => 'nullable|string|max:255',
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







        $availableYears = Cost::selectRaw('YEAR(due_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $year     = $request->query('y', now()->year);
        $month    = $request->query('m', now()->month);
        $lastYear = $year - 1;

        $categories = CostCategory::orderBy('name')->get();

        // 1) per‐category totals for this month
        $raw = Cost::whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');













        // build costs for every available year, keyed by month
        $costsForYear = [];
        foreach ($availableYears as $yr) {
            $costsForYear[$yr] = Cost::whereYear('due_date', $yr)
                ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month');
        }

        $currentYear = now()->year;
        $lastYear    = $currentYear - 1;
        $incomeThisMonth    = Income::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
        $incomeLastYearSame = Income::whereYear('date', $lastYear)
            ->whereMonth('date', $month)
            ->sum('amount');

        $costsThisYear = Cost::whereYear('due_date', $currentYear)
            ->selectRaw('MONTH(due_date) as m, SUM(amount) as total')
            ->groupBy('m')
            ->pluck('total', 'm');

        $incomeThisYearMonthly = Income::whereYear('date', $year)
            ->selectRaw('MONTH(date) as m, SUM(amount) as total')
            ->groupBy('m')
            ->pluck('total', 'm');

        $incomeLastYearMonthly = Income::whereYear('date', $lastYear)
            ->selectRaw('MONTH(date) as m, SUM(amount) as total')
            ->groupBy('m')
            ->pluck('total', 'm');
        $thisYearComparison = collect(range(1, 12))
            ->mapWithKeys(fn($m) => [
                $m => [
                    'cost'   => $costsThisYear->get($m, 0),
                    'income' => $incomeThisYearMonthly->get($m, 0),
                    'net'    => $incomeThisYearMonthly->get($m, 0) - $costsThisYear->get($m, 0),
                ]
            ]);
        $bestMonth  = $thisYearComparison->sortByDesc('net')->keys()->first();
        $bestNet    = $thisYearComparison[$bestMonth]['net'];
        $worstMonth = $thisYearComparison->sortBy('net')->keys()->first();
        $worstNet   = $thisYearComparison[$worstMonth]['net'];
        // year‐to‐date totals

        $costsLastYear = Cost::whereYear('due_date', $lastYear)
            ->selectRaw('MONTH(due_date) as m, SUM(amount) as total')
            ->groupBy('m')
            ->pluck('total', 'm');

        $totalCostYear   = Cost::whereYear('due_date', $year)->sum('amount');
        $totalIncomeYear = Income::whereYear('date',    $year)->sum('amount');
        $netYear             = $totalIncomeYear - $totalCostYear;
        $totalCostLastYear   = Cost::whereYear('due_date', $lastYear)->sum('amount');
        $totalIncomeLastYear = Income::whereYear('date', $lastYear)->sum('amount');
        $netLastYear         = $totalIncomeLastYear - $totalCostLastYear;


        return view('frontend.costs.dashboard', compact(
            'availableYears',
            'year',
            'month',
            'lastYear',
            'categories',
            'raw',
            'incomeThisMonth',
            'incomeLastYearSame',
            'totalCostYear',
            'totalIncomeYear',
            'costsForYear',
            'bestMonth',
            'bestNet',
            'worstMonth',
            'worstNet',
            'costsThisYear',
            'incomeThisYearMonthly',
            'costsLastYear',
            'costsLastYear',
            'incomeLastYearMonthly',
            'netYear',
            'totalCostLastYear',
            'totalIncomeLastYear',
            'netLastYear'
        ));
    }
}
