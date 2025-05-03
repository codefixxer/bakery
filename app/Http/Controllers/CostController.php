<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Income;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CostController extends Controller
{
    public function create()
    {
        $userId     = Auth::id();
        $categories = CostCategory::where('user_id', $userId)
                                  ->orderBy('name')
                                  ->get();

        return view('frontend.costs.create', compact('categories'));
    }

    public function show(Cost $cost)
    {
        return view('frontend.costs.show', compact('cost'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier'        => 'required|string|max:255',
            'cost_identifier' => 'nullable|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'due_date'        => 'required|date',
            'category_id'     => 'required|exists:cost_categories,id',
            'other_category'  => 'nullable|string|max:255',
        ]);

        $data['user_id'] = Auth::id();

        Cost::create($data);

        return redirect()->route('costs.index')
                         ->with('success', 'Cost added!');
    }

    public function index(Request $request)
    {
        $userId     = Auth::id();
        $categories = CostCategory::where('user_id', $userId)
                                  ->orderBy('name')
                                  ->get();

        $filter = $request->query('filter_month', now()->format('Y-m'));
        [$year, $month] = explode('-', $filter);

        $costs = Cost::with('category')
                     ->where('user_id', $userId)
                     ->orderBy('due_date', 'desc')
                     ->get();

        return view('frontend.costs.index', compact('costs', 'filter', 'categories'));
    }

    public function edit(Cost $cost)
    {
        if ($cost->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $userId     = Auth::id();
        $categories = CostCategory::where('user_id', $userId)
                                  ->orderBy('name')
                                  ->get();

        return view('frontend.costs.create', compact('cost', 'categories'));
    }

    public function update(Request $request, Cost $cost)
    {
        if ($cost->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'supplier'        => 'required|string|max:255',
            'cost_identifier' => 'nullable|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'due_date'        => 'required|date',
            'category_id'     => 'required|exists:cost_categories,id',
            'other_category'  => 'nullable|string|max:255',
        ]);

        $cost->update($data);

        return redirect()->route('costs.index')
                         ->with('success', 'Cost updated successfully!');
    }

    public function destroy(Cost $cost)
    {
        if ($cost->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $cost->delete();

<<<<<<< HEAD
        return redirect()->route('costs.index')
                         ->with('success', 'Cost deleted successfully!');
=======
<<<<<<< HEAD
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
>>>>>>> 1b6143c (Hammad Changes)
    }

    public function dashboard(Request $request)
{
    $userId = Auth::id();

    $availableYears = Cost::where('user_id', $userId)
        ->selectRaw('YEAR(due_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    $year     = $request->query('y', now()->year);
    $month    = $request->query('m', now()->month);
    $lastYear = $year - 1;

    $categories = CostCategory::where('user_id', $userId)
        ->orderBy('name')
        ->get();

    $raw = Cost::where('user_id', $userId)
        ->whereYear('due_date', $year)
        ->whereMonth('due_date', $month)
        ->selectRaw('category_id, SUM(amount) as total')
        ->groupBy('category_id')
        ->pluck('total', 'category_id');

    // Grouped costs for this year and last year (monthly)
    $costsThisYear = Cost::where('user_id', $userId)
        ->whereYear('due_date', $year)
        ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

    $costsLastYear = Cost::where('user_id', $userId)
        ->whereYear('due_date', $lastYear)
        ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

<<<<<<< HEAD
=======
=======
        return redirect()->route('costs.index')
                         ->with('success', 'Cost deleted successfully!');
    }

    public function dashboard(Request $request)
{
    $userId = Auth::id();

    $availableYears = Cost::where('user_id', $userId)
        ->selectRaw('YEAR(due_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    $year     = $request->query('y', now()->year);
    $month    = $request->query('m', now()->month);
    $lastYear = $year - 1;

    $categories = CostCategory::where('user_id', $userId)
        ->orderBy('name')
        ->get();

    $raw = Cost::where('user_id', $userId)
        ->whereYear('due_date', $year)
        ->whereMonth('due_date', $month)
        ->selectRaw('category_id, SUM(amount) as total')
        ->groupBy('category_id')
        ->pluck('total', 'category_id');

    // Grouped costs for this year and last year (monthly)
    $costsThisYear = Cost::where('user_id', $userId)
        ->whereYear('due_date', $year)
        ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

    $costsLastYear = Cost::where('user_id', $userId)
        ->whereYear('due_date', $lastYear)
        ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

>>>>>>> 1b6143c (Hammad Changes)
    // Year-to-date totals
    $totalCostYear = $costsThisYear->sum();
    $totalCostLastYear = $costsLastYear->sum();

    $incomeThisYearMonthly = [];
    $incomeLastYearMonthly = [];
    $netByMonth = [];

    for ($m = 1; $m <= 12; $m++) {
        $income = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->sum('amount');

        $incomeLast = Income::where('user_id', $userId)
            ->whereYear('date', $lastYear)
            ->whereMonth('date', $m)
            ->sum('amount');

        $incomeThisYearMonthly[$m] = $income;
        $incomeLastYearMonthly[$m] = $incomeLast;

        $netByMonth[$m] = $income - ($costsThisYear[$m] ?? 0);
    }

    $totalIncomeYear = array_sum($incomeThisYearMonthly);
    $totalIncomeLastYear = array_sum($incomeLastYearMonthly);
    $netYear = $totalIncomeYear - $totalCostYear;
    $netLastYear = $totalIncomeLastYear - $totalCostLastYear;

    $bestMonth = collect($netByMonth)->sortDesc()->keys()->first();
    $bestNet   = $netByMonth[$bestMonth] ?? 0;

    $worstMonth = collect($netByMonth)->sort()->keys()->first();
    $worstNet   = $netByMonth[$worstMonth] ?? 0;

    $incomeThisMonth = Income::where('user_id', $userId)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->sum('amount');

    $incomeLastYearSame = Income::where('user_id', $userId)
        ->whereYear('date', $lastYear)
        ->whereMonth('date', $month)
        ->sum('amount');

    return view('frontend.costs.dashboard', compact(
        'availableYears', 'year', 'month', 'lastYear', 'categories',
        'raw', 'incomeThisMonth', 'incomeLastYearSame',
        'costsThisYear', 'costsLastYear', 'netByMonth',
        'incomeThisYearMonthly', 'incomeLastYearMonthly',
        'totalCostYear', 'totalIncomeYear', 'netYear',
        'totalCostLastYear', 'totalIncomeLastYear', 'netLastYear',
        'bestMonth', 'bestNet', 'worstMonth', 'worstNet'
    ));
}
<<<<<<< HEAD
=======
>>>>>>> a57eb44 (Hammad Changes)
>>>>>>> 1b6143c (Hammad Changes)
}
