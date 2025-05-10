<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cost;
use App\Models\User;
use App\Models\Income;
use App\Models\Recipe;
use App\Models\Showcase;
use App\Models\ShowcaseRecipe;
use App\Models\ProductionDetail;
use App\Models\ReturnedGoodRecipe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ExternalSupplyRecipe;
use Illuminate\Support\Facades\Auth;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        // 1) Determine visible users
        if (is_null($user->created_by)) {
            $children = User::where('created_by', $user->id)->pluck('id');
            $visibleUserIds = $children->isEmpty()
                ? collect([$user->id])
                : $children->push($user->id);
        } else {
            $visibleUserIds = collect([$user->id, $user->created_by]);
        }

           // 2) Core metrics
        $totalUsers = User::whereIn('id', $visibleUserIds)->count();
        $totalRecipes = Recipe::whereIn('user_id', $visibleUserIds)->count();
        $totalShowcases = Showcase::whereIn('user_id', $visibleUserIds)->count();

        $year = Carbon::now()->year;
        $totalSaleThisYear = Showcase::whereIn('user_id', $visibleUserIds)
            ->whereYear('showcase_date', $year)
            ->sum('total_revenue');

        $totalWasteThisYear = ShowcaseRecipe::whereIn('user_id', $visibleUserIds)
            ->whereHas('showcase', fn($q) => $q->whereYear('showcase_date', $year))
            ->sum('waste');

        $totalProfitThisYear = Showcase::whereIn('user_id', $visibleUserIds)
            ->whereYear('showcase_date', $year)
            ->sum('real_margin');

        // 2) Earnings metrics
        $sales      = Showcase::whereIn('user_id', $visibleUserIds)->sum('total_revenue');
        $plus       = Showcase::whereIn('user_id', $visibleUserIds)->sum('plus');
        $realMargin = Showcase::whereIn('user_id', $visibleUserIds)->sum('real_margin');

        // 3) Monthly Sales Chart (current year)
        $monthlyData = Showcase::selectRaw("MONTH(showcase_date) AS month, SUM(total_revenue) AS total")
            ->whereIn('user_id', $visibleUserIds)
            ->whereYear('showcase_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $monthlyData->pluck('month')
            ->map(fn($m) => Carbon::createFromDate(null, $m, 1)->format('M'))
            ->toArray();
        $values = $monthlyData->pluck('total')->toArray();

        $chart = (new LarapexChart)->barChart()
            ->setTitle('Monthly Sales')
            ->setSubtitle('Current Year')
            ->addData('Revenue', $values)
            ->setXAxis($labels);

        // 4) Cost vs Income Chart (this month)
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();
        $monthlyCost   = Cost::whereIn('user_id', $visibleUserIds)
            ->whereBetween('due_date', [$start, $end])->sum('amount');
        $monthlyIncome = Income::whereIn('user_id', $visibleUserIds)
            ->whereBetween('date', [$start, $end])->sum('amount');

        $comparisonChart = (new LarapexChart)->barChart()
            ->setTitle('Costs vs Income')
            ->setSubtitle(Carbon::now()->format('F Y'))
            ->addData('Cost', [$monthlyCost])
            ->addData('Income', [$monthlyIncome])
            ->setXAxis([Carbon::now()->format('F')]);

        // 5) Yearly Costs Comparison
        $thisYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;
        $yearlyCostThis = Cost::whereIn('user_id', $visibleUserIds)
            ->whereYear('due_date', $thisYear)->sum('amount');
        $yearlyCostLast = Cost::whereIn('user_id', $visibleUserIds)
            ->whereYear('due_date', $lastYear)->sum('amount');

        $yearlyCostChart = (new LarapexChart)->barChart()
            ->setTitle('Yearly Costs Comparison')
            ->setSubtitle("$lastYear vs $thisYear")
            ->addData('Costs', [$yearlyCostLast, $yearlyCostThis])
            ->setXAxis([$lastYear, $thisYear]);

        // 6) Yearly Income Comparison
        $yearlyIncomeThis = Income::whereIn('user_id', $visibleUserIds)
            ->whereYear('date', $thisYear)->sum('amount');
        $yearlyIncomeLast = Income::whereIn('user_id', $visibleUserIds)
            ->whereYear('date', $lastYear)->sum('amount');

        $yearlyIncomeChart = (new LarapexChart)->barChart()
            ->setTitle('Yearly Income Comparison')
            ->setSubtitle("$lastYear vs $thisYear")
            ->addData('Income', [$yearlyIncomeLast, $yearlyIncomeThis])
            ->setXAxis([$lastYear, $thisYear]);

        // 7) Top 5 Sold & Wasted Products
        $topSold = ShowcaseRecipe::with(['recipe' => function ($query) {
            $query->select('id', 'recipe_name');
        }])
            ->whereIn('user_id', $visibleUserIds)
            ->selectRaw('recipe_id, SUM(sold) as sold')
            ->groupBy('recipe_id')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        $topWasted = ShowcaseRecipe::with(['recipe' => function ($query) {
            $query->select('id', 'recipe_name');
        }])
            ->whereIn('user_id', $visibleUserIds)
            ->selectRaw('recipe_id, SUM(waste) as waste')
            ->groupBy('recipe_id')
            ->orderByDesc('waste')
            ->take(5)
            ->get();

        // 8) Charts for Sold & Wasted
$soldValues = $topSold->pluck('sold')->map(fn($v) => (int) $v)->toArray();
$soldLabels = $topSold->map(fn($item) => $item->recipe->recipe_name ?? 'Unknown')->toArray();

$soldPieChart = (new LarapexChart)->donutChart()
    ->setTitle('Sold Distribution')
    ->addData($soldValues)
    ->setLabels($soldLabels);

        $wastedLabels = $topWasted->map(fn($item) => $item->recipe->recipe_name ?? 'Unknown')->toArray();
        $wastedValues = $topWasted->pluck('waste')->toArray();
        $wastedPieChart = (new LarapexChart)->donutChart()
            ->setTitle('Waste Distribution')
            ->addData($wastedValues)
            ->setLabels($wastedLabels);
            










            // Controller
$totalSupplied = ExternalSupplyRecipe::whereIn('user_id',$visibleUserIds)->sum('qty');
$totalReturned = ReturnedGoodRecipe::join('returned_goods','returned_goods.id','returned_good_recipes.returned_good_id')
    ->whereIn('returned_goods.user_id',$visibleUserIds)
    ->sum('qty');

$returnRateChart = (new LarapexChart)->pieChart()
    ->setTitle('Return vs. Used')
    ->addData([$totalReturned, $totalSupplied - $totalReturned])
    ->setLabels(['Returned','Used']);

    // Controller
$totalSupplied = ExternalSupplyRecipe::whereIn('user_id',$visibleUserIds)->sum('qty');
$totalReturned = ReturnedGoodRecipe::join('returned_goods','returned_goods.id','returned_good_recipes.returned_good_id')
    ->whereIn('returned_goods.user_id',$visibleUserIds)
    ->sum('qty');

$returnRateChart = (new LarapexChart)->pieChart()
    ->setTitle('Return vs. Used')
    ->addData([$totalReturned, $totalSupplied - $totalReturned])
    ->setLabels(['Returned','Used']);


    // Controller
$prodByChef = ProductionDetail::selectRaw('pastry_chef_id, SUM(quantity) AS qty')
    ->whereIn('user_id',$visibleUserIds)
    ->groupBy('pastry_chef_id')
    ->with('chef:id,name')
    ->orderByDesc('qty')
    ->get();

$chefLabels = $prodByChef->pluck('chef.name')->toArray();
$chefValues = $prodByChef->pluck('qty')->toArray();

$chefChart = (new LarapexChart)->barChart()
    ->setTitle('Production by Chef')
    ->addData('Units', $chefValues)
    ->setXAxis($chefLabels);



    // Controller
$prodTrend = ProductionDetail::selectRaw('MONTH(production_date) AS m, SUM(quantity) AS produced')
    ->join('productions','productions.id','production_details.production_id')
    ->whereIn('production_details.user_id',$visibleUserIds)
    ->whereYear('productions.production_date',$year)
    ->groupBy('m')->orderBy('m')->get();

$wasteTrend = ShowcaseRecipe::selectRaw('MONTH(showcase_date) AS m, SUM(waste) AS waste')
    ->join('showcases','showcases.id','showcase_recipes.showcase_id')
    ->whereIn('showcase_recipes.user_id',$visibleUserIds)
    ->whereYear('showcases.showcase_date',$year)
    ->groupBy('m')->orderBy('m')->get();

$labels = $prodTrend->pluck('m')->map(fn($m)=>Carbon::create(null,$m,1)->format('M'))->toArray();
$prodVals = $prodTrend->pluck('produced')->toArray();
$wasteVals= $wasteTrend->pluck('waste')->toArray();

$prodWasteChart = (new LarapexChart)->areaChart()
    ->setTitle('Production vs. Waste')
    ->addData('Produced', $prodVals)
    ->addData('Wasted',  $wasteVals)
    ->setXAxis($labels);

 $costByCategory = Cost::join('cost_categories', 'costs.category_id', '=', 'cost_categories.id')
        ->whereIn('costs.user_id', $visibleUserIds)
        ->groupBy('cost_categories.id', 'cost_categories.name')
        ->select(
            'cost_categories.name as category',
            DB::raw('SUM(costs.amount) as total')
        )
        ->get();

    $categoryLabels = $costByCategory->pluck('category')->toArray();
    $categoryValues = $costByCategory->pluck('total')->map(fn($v) => (float) $v)->toArray();

    $costCategoryChart = (new LarapexChart)->donutChart()
        ->setTitle('Cost Breakdown by Category')
        ->addData($categoryValues)
        ->setLabels($categoryLabels);









        $fullMonthlyData = $monthlyData->map(function($row) {
    return [
        // we'll compare these ISO dates in JS
        'date'  => Carbon::createFromDate(null, $row->month, 1)->format('Y-m-d'),
        'total' => $row->total,
    ];
})->toArray();











 // 8) Full datasets for JS date‐range filtering
        $fullSoldData = ShowcaseRecipe::with(['recipe','showcase'])
            ->whereIn('user_id', $visibleUserIds)
            ->get()
            ->map(fn($r) => [
                'recipe_name' => $r->recipe->recipe_name,
                'sold'        => $r->sold,
                'date'        => $r->showcase->showcase_date->format('Y-m-d'),
            ])->toArray();

        $fullWastedData = ShowcaseRecipe::with(['recipe','showcase'])
            ->whereIn('user_id', $visibleUserIds)
            ->get()
            ->map(fn($r) => [
                'recipe_name' => $r->recipe->recipe_name,
                'waste'       => $r->waste,
                'date'        => $r->showcase->showcase_date->format('Y-m-d'),
            ])->toArray();









        // 9) Render dashboard
        return view('dashboard', compact(
            'sales', 'plus', 'realMargin',
            'chart', 'comparisonChart', 'yearlyCostChart', 'yearlyIncomeChart',
            'topSold', 'topWasted',
            'soldPieChart', 'wastedPieChart',            'totalUsers',
            'totalRecipes',
            'totalShowcases',
            'totalSaleThisYear',
            'totalWasteThisYear',
            'totalProfitThisYear','year',   'totalSupplied', 'totalReturned', 'returnRateChart',
            'chefChart', 'prodWasteChart',    'costCategoryChart' 



            ,'fullMonthlyData','fullSoldData','fullWastedData'
        ));
    }
}
