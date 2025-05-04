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
    /**
     * Show form to create a new cost.
     */
    public function create()
    {
        $userId     = Auth::id();
        $categories = CostCategory::where('user_id', $userId)
                                  ->orderBy('name')
                                  ->get();

        return view('frontend.costs.create', compact('categories'));
    }

    /**
     * Display a single cost.
     */
    public function show(Cost $cost)
    {
        return view('frontend.costs.show', compact('cost'));
    }

    /**
     * Persist a newly created cost.
     */
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

        return redirect()
            ->route('costs.index')
            ->with('success', 'Cost added!');
    }

    /**
     * Display a listing of costs.
     * Loads all categories for the add/edit form and all costs for the table.
     */
    public function index()
    {
        $userId     = Auth::id();
        $categories = CostCategory::where('user_id', $userId)
                                  ->orderBy('name')
                                  ->get();

        $costs = Cost::with('category')
            ->where('user_id', $userId)
            ->orderBy('due_date', 'desc')
            ->get();

        return view('frontend.costs.index', compact('costs', 'categories'));
    }

    /**
     * Show the form for editing the specified cost.
     */
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

    /**
     * Update the specified cost in storage.
     */
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

        return redirect()
            ->route('costs.index')
            ->with('success', 'Cost updated successfully!');
    }

    /**
     * Remove the specified cost from storage.
     */
    public function destroy(Cost $cost)
    {
        if ($cost->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $cost->delete();

        return redirect()
            ->route('costs.index')
            ->with('success', 'Cost deleted successfully!');
    }

    /**
     * Dashboard showing cost/income summaries.
     */
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

        $totalCostYear     = $costsThisYear->sum();
        $totalCostLastYear = $costsLastYear->sum();

        $incomeThisYearMonthly = [];
        $incomeLastYearMonthly = [];
        $netByMonth            = [];

        for ($m = 1; $m <= 12; $m++) {
            $income     = Income::where('user_id', $userId)
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->sum('amount');
            $incomeLast = Income::where('user_id', $userId)
                ->whereYear('date', $lastYear)
                ->whereMonth('date', $m)
                ->sum('amount');

            $incomeThisYearMonthly[$m] = $income;
            $incomeLastYearMonthly[$m] = $incomeLast;
            $netByMonth[$m]            = $income - ($costsThisYear[$m] ?? 0);
        }

        $totalIncomeYear     = array_sum($incomeThisYearMonthly);
        $totalIncomeLastYear = array_sum($incomeLastYearMonthly);
        $netYear             = $totalIncomeYear - $totalCostYear;
        $netLastYear         = $totalIncomeLastYear - $totalCostLastYear;

        $bestMonth  = collect($netByMonth)->sortDesc()->keys()->first();
        $bestNet    = $netByMonth[$bestMonth] ?? 0;
        $worstMonth = collect($netByMonth)->sort()->keys()->first();
        $worstNet   = $netByMonth[$worstMonth] ?? 0;

        $incomeThisMonth    = Income::where('user_id', $userId)
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
}
