<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Showcase;
use App\Models\LaborCost;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Models\ShowcaseRecipe;
use App\Http\Controllers\Controller;

class ShowcaseController extends Controller
{


    public function recipeSales(Request $request)
    {
        $recipeId     = $request->query('recipe_id');
        $categoryId   = $request->query('category_id');
        $departmentId = $request->query('department_id');
        $startDate    = $request->query('start_date', now()->subMonth()->toDateString());
        $endDate      = $request->query('end_date', now()->toDateString());
    
        // Get the filters
        $recipes     = Recipe::pluck('recipe_name','id');
        $categories  = RecipeCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
    
        // Get records and group by recipe and then by date
        $records = ShowcaseRecipe::with(['recipe.category','recipe.department','showcase'])
            ->when($recipeId,     fn($q) => $q->where('recipe_id', $recipeId))
            ->when($categoryId,   fn($q) => $q->whereHas('recipe', fn($q) => $q->where('category_id', $categoryId)))
            ->when($departmentId, fn($q) => $q->whereHas('recipe', fn($q) => $q->where('department_id', $departmentId)))
            ->whereHas('showcase', fn($q) =>
                $q->whereBetween('showcase_date', [$startDate, $endDate])
            )
            ->get();
    
        // Group records by recipe, then by date
        $recordsByRecipe = $records->groupBy('recipe_id')->map(function ($rows) {
            return $rows->groupBy(fn($r) => $r->showcase->showcase_date->format('Y-m-d'));
        });
    
        // Return to view with grouped data
        return view('frontend.showcase.recipe-sales', compact(
            'recipes', 'categories', 'departments', 
            'recordsByRecipe', 'recipeId', 'categoryId', 'departmentId', 
            'startDate', 'endDate'
        ));
    }
    
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve the first LaborCost record
        $laborCost = LaborCost::first();
    
        // Retrieve all showcases along with their related recipes
        $showcases = Showcase::with('recipes')->latest()->get();
    
        // Pass the daily_bep value from the LaborCost table to the view
        return view('frontend.showcase.index', compact('showcases', 'laborCost'));
    }
    

    /**
     * Show the form for creating a new showcase.
     */
    public function create()
    {        $laborCost = LaborCost::first();

        $departments = Department::all();
        $isEdit      = false;
        $recipes = Recipe::where('labor_cost_mode', 'shop')->get();
        return view('frontend.showcase.create', compact('departments', 'recipes', 'isEdit','laborCost'));
    }

    /**
     * Store a newly created showcase in storage.
     */
    public function store(Request $request)
    {
        // Validate the main fields and the items array.
        $data = $request->validate([
            'showcase_date'   => 'required|date',
            'template_action' => 'nullable|string',
            'break_even'      => 'nullable|numeric',
            'items'           => 'required|array',
            'items.*.recipe_id'    => 'required|exists:recipes,id',
            'items.*.category'     => 'nullable|string',
            'items.*.price'        => 'nullable|numeric',
            'items.*.quantity'     => 'required|integer|min:0',
            'items.*.sold'         => 'required|integer|min:0',
            'items.*.reuse'        => 'required|integer|min:0',
            'items.*.waste'        => 'required|integer|min:0',
            // If these fields are supplied, they are numeric; otherwise, we will compute them:
            'items.*.potential_income' => 'nullable|numeric',
            'items.*.actual_revenue'   => 'nullable|numeric',
        ]);
    
        // Initialize totals
        $totalActualRevenue = 0;
        $totalPotentialIncome = 0;
    
        // Loop through items to compute per-row values if not already set.
        foreach ($data['items'] as &$item) {
            // If price is sent via the form (or in our case, read from the recipe selection),
            // we compute potential_income as: price * quantity, and actual_revenue as: price * sold.
            $price = isset($item['price']) ? (float)$item['price'] : 0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 0;
            $sold = isset($item['sold']) ? (int)$item['sold'] : 0;
    
            // Compute and override the values from the form (or use them if you're sure they are calculated)
            $item['potential_income'] = round($price * $quantity, 2);
            $item['actual_revenue']   = round($price * $sold, 2);
    
            $totalPotentialIncome += $item['potential_income'];
            $totalActualRevenue   += $item['actual_revenue'];
        }
        unset($item); // break reference
    
        // Now compute summary values for the Showcase record.
        $breakEven = isset($data['break_even']) ? (float)$data['break_even'] : 0;
        $data['total_revenue'] = $totalActualRevenue;
        $data['plus'] = round($totalActualRevenue - $breakEven, 2);
        $data['real_margin'] = $totalActualRevenue > 0 
            ? round(($data['plus'] / $totalActualRevenue) * 100, 2)
            : 0;
    
        // Optionally compute an average potential income if needed
        $itemCount = count($data['items']);
        $data['potential_income_average'] = $itemCount > 0 ? round($totalPotentialIncome / $itemCount, 2) : 0;
    
        // Create the Showcase record.
        $showcase = Showcase::create($data);
    
        // Create each ShowcaseRecipe record.
        foreach ($data['items'] as $itemData) {
            $itemData['showcase_id'] = $showcase->id;
            ShowcaseRecipe::create($itemData);
        }
    
        return redirect()->route('showcase.index')
            ->with('success', 'Showcase created successfully.');
    }
    
    

    /**
     * Display the specified showcase.
     */
    public function show(Showcase $showcase)
    {
        // eager-load recipe → department
        $showcase->load('recipes.recipe.department');
        return view('frontend.showcase.show', compact('showcase'));
    }

    /**
     * Show the form for editing the specified showcase.
     */
    public function edit(Showcase $showcase)
    {
        $departments = Department::all();
        $recipes     = Recipe::all();
        $isEdit      = true;
        $showcase->load('recipes');
        return view('frontend.showcase.create', compact('showcase', 'departments', 'recipes', 'isEdit'));
    }




    /**
     * Update the specified showcase in storage.
     */
    public function update(Request $request, Showcase $showcase)
    {
        $data = $request->validate([
            'showcase_date'   => 'required|date',
            'template_action' => 'nullable|string',
            'break_even'      => 'nullable|numeric',
            'items'           => 'required|array',
            'items.*.recipe_id'    => 'required|exists:recipes,id',
            'items.*.quantity'     => 'required|integer|min:0',
            'items.*.sold'         => 'required|integer|min:0',
            'items.*.reuse'        => 'required|integer|min:0',
            'items.*.waste'        => 'required|integer|min:0',
            'items.*.potential_income' => 'nullable|numeric',
            'items.*.actual_revenue'   => 'nullable|numeric',
        ]);

        // Initialize computed totals.
        $totalActualRevenue = 0;
        $totalPotentialIncome = 0;
        foreach ($data['items'] as $item) {
            $potential = isset($item['potential_income']) ? (float)$item['potential_income'] : 0;
            $actual    = isset($item['actual_revenue']) ? (float)$item['actual_revenue'] : 0;
            $totalPotentialIncome += $potential;
            $totalActualRevenue   += $actual;
        }
        $breakEven = isset($data['break_even']) ? (float)$data['break_even'] : 0;
        $data['total_revenue'] = $totalActualRevenue;
        $data['plus']          = $totalActualRevenue - $breakEven;
        $data['real_margin']   = $totalActualRevenue > 0 
                                 ? round((($data['plus'] / $totalActualRevenue) * 100), 2)
                                 : 0;
        $countItems = count($data['items']);
        $data['potential_income_average'] = $countItems > 0 ? round($totalPotentialIncome / $countItems, 2) : 0;

        $showcase->update($data);

        // Delete previous related items and re-create them.
        $showcase->recipes()->delete();
        foreach ($data['items'] as $item) {
            $item['showcase_id'] = $showcase->id;
            ShowcaseRecipe::create($item);
        }

        return redirect()->route('showcase.index')->with('success', 'Showcase updated successfully.');
    }

    /**
     * Remove the specified showcase from storage.
     */
    public function destroy(Showcase $showcase)
    {
        $showcase->delete();
        return redirect()->route('showcase.index')->with('success', 'Showcase deleted successfully.');
    }


    
    /**
     * Custom method to manage a showcase.
     */
    public function manage(Showcase $showcase)
    {
        $showcase->load('recipes');
        
        return view('frontend.showcase.create', compact('showcase'));
    }
}
