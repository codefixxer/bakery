<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\User;
use App\Models\Income;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $user = Auth::user();

        // 1) Determine which user_ids you can see:
        if (is_null($user->created_by)) {
            // Root user: see yourself + any direct children
            $children = User::where('created_by', $user->id)->pluck('id');
            $visibleUserIds = $children->isEmpty()
                ? collect([$user->id])
                : $children->push($user->id);
        } else {
            // Child user: see yourself + your creator
            $visibleUserIds = collect([$user->id, $user->created_by]);
        }

        // 2) Fetch categories & costs belonging to that group
        $categories = CostCategory::with('user')
            ->whereIn('user_id', $visibleUserIds)
            ->orderBy('name')
            ->get();

        $costs = Cost::with(['category','user'])
            ->whereIn('user_id', $visibleUserIds)
            ->orderBy('due_date', 'desc')
            ->get();

        return view('frontend.costs.index', compact('categories','costs'));
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
        $user = Auth::user();

        //
        // 1) Build the list of visible user IDs:
        //
        if (is_null($user->created_by)) {
            // Root user: themselves + any direct children they created
            $children = User::where('created_by', $user->id)->pluck('id');
            $visibleUserIds = $children->isEmpty()
                ? collect([$user->id])
                : $children->push($user->id);
        } else {
            // Child user: themselves + their creator
            $visibleUserIds = collect([$user->id, $user->created_by]);
        }

        //
        // 2) Year / month filters
        //
        $year     = $request->query('y', now()->year);
        $month    = $request->query('m', now()->month);
        $lastYear = $year - 1;

        //
        // 3) Categories limited to your group
        //
        $categories = CostCategory::whereIn('user_id', $visibleUserIds)
            ->orderBy('name')
            ->get();

        //
        // 4) Available years for which this group has costs
        //
        $availableYears = Cost::whereIn('user_id', $visibleUserIds)
            ->selectRaw('YEAR(due_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        //
        // 5) Cost summary by category for selected month
        //
        $raw = Cost::whereIn('user_id', $visibleUserIds)
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        //
        // 6) Monthly cost totals for this year & last year
        //
        $costsThisYear = Cost::whereIn('user_id', $visibleUserIds)
            ->whereYear('due_date', $year)
            ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $costsLastYear = Cost::whereIn('user_id', $visibleUserIds)
            ->whereYear('due_date', $lastYear)
            ->selectRaw('MONTH(due_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $totalCostYear     = $costsThisYear->sum();
        $totalCostLastYear = $costsLastYear->sum();

        //
        // 7) Build incomes & net by month arrays
        //
        $incomeThisYearMonthly = [];
        $incomeLastYearMonthly = [];
        $netByMonth            = [];

        for ($m = 1; $m <= 12; $m++) {
            $income     = Income::whereIn('user_id', $visibleUserIds)
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->sum('amount');
            $incomeLast = Income::whereIn('user_id', $visibleUserIds)
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

        //
        // 8) Best / worst month
        //
        $bestMonth  = collect($netByMonth)->sortDesc()->keys()->first();
        $bestNet    = $netByMonth[$bestMonth] ?? 0;
        $worstMonth = collect($netByMonth)->sort()->keys()->first();
        $worstNet   = $netByMonth[$worstMonth] ?? 0;

        //
        // 9) Current month and last‐year‐same‐month incomes
        //
        $incomeThisMonth    = Income::whereIn('user_id', $visibleUserIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $incomeLastYearSame = Income::whereIn('user_id', $visibleUserIds)
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
