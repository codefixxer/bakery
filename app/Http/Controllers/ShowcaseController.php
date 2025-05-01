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


    /**
 * Return one template’s rows as JSON.
 */
/**
 * Return one template’s data as JSON.
 */
/**
 * Return one template’s data as JSON.
 */
public function getTemplate($id)
{
    $showcase = Showcase::with('recipes')->findOrFail($id);

    $rows = $showcase->recipes->map(function($r) {
        return [
            'recipe_id'        => $r->recipe_id,
            'price'            => $r->price,
            'quantity'         => $r->quantity,
            'sold'             => $r->sold,
            'reuse'            => $r->reuse,
            'waste'            => $r->waste,
            'potential_income' => $r->potential_income,
            'actual_revenue'   => $r->actual_revenue,
        ];
    });

    return response()->json([
        'showcase_name'   => $showcase->showcase_name,
        'showcase_date'   => $showcase->showcase_date->format('Y-m-d'),
        'template_action' => $showcase->template_action,
        'rows'            => $rows,
    ]);
}



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laborCost = LaborCost::first();
        $showcases = Showcase::with('recipes')->latest()->get();

        return view('frontend.showcase.index', compact('showcases', 'laborCost'));
    }

    /**
     * Show the form for creating a new showcase.
     */
    public function create()
    {
        $laborCost   = LaborCost::first();
        $departments = Department::all();
        $recipes     = Recipe::where('labor_cost_mode', 'shop')->get();
        $isEdit      = false;
        $templates   = Showcase::where('save_template', true)
                               ->pluck('showcase_name', 'id');

        return view('frontend.showcase.create', compact(
            'departments', 'recipes', 'isEdit', 'laborCost', 'templates'
        ));
    }

    /**
     * Store a newly created showcase in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'showcase_name'           => 'required|string|max:255',
            'showcase_date'           => 'required|date',
            'template_action'         => 'required|in:none,template,both',
            'break_even'              => 'nullable|numeric',
            'items'                   => 'required|array',
            'items.*.recipe_id'       => 'required|exists:recipes,id',
            'items.*.quantity'        => 'required|integer|min:0',
            'items.*.sold'            => 'required|integer|min:0',
            'items.*.reuse'           => 'required|integer|min:0',
            'items.*.waste'           => 'required|integer|min:0',
            'items.*.potential_income'=> 'nullable|numeric',
            'items.*.actual_revenue'  => 'nullable|numeric',
        ]);

        // mark as template if selected
        $data['save_template'] = in_array($data['template_action'], ['template', 'both']);

        // compute row‐level and summary totals
        $totalActual    = 0;
        $totalPotential = 0;
        foreach ($data['items'] as &$item) {
            $price = $item['price'] ?? 0;
            $qty   = $item['quantity'];
            $sold  = $item['sold'];
            $item['potential_income'] = round($price * $qty, 2);
            $item['actual_revenue']   = round($price * $sold, 2);
            $totalPotential += $item['potential_income'];
            $totalActual    += $item['actual_revenue'];
        }
        unset($item);

        $data['total_revenue']            = $totalActual;
        $data['plus']                     = round($totalActual - ($data['break_even'] ?? 0), 2);
        $data['real_margin']              = $totalActual > 0
            ? round(($data['plus'] / $totalActual) * 100, 2)
            : 0;
        $data['potential_income_average'] = count($data['items'])
            ? round($totalPotential / count($data['items']), 2)
            : 0;

        $showcase = Showcase::create($data);

        foreach ($data['items'] as $item) {
            $item['showcase_id'] = $showcase->id;
            ShowcaseRecipe::create($item);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase created successfully.');
    }

    /**
     * Display the specified showcase.
     */
    public function show(Showcase $showcase)
    {
        $showcase->load('recipes.recipe.department');
        return view('frontend.showcase.show', compact('showcase'));
    }

    /**
     * Show the form for editing the specified showcase.
     */
    public function edit(Showcase $showcase)
    {
        $laborCost   = LaborCost::first();
        $departments = Department::all();
        $recipes     = Recipe::where('labor_cost_mode', 'shop')->get();
        $isEdit      = true;
        $templates   = Showcase::where('save_template', true)
                               ->pluck('showcase_name', 'id');

        $showcase->load('recipes');

        return view('frontend.showcase.create', compact(
            'showcase', 'departments', 'recipes', 'isEdit', 'laborCost', 'templates'
        ));
    }

    /**
     * Update the specified showcase in storage.
     */
    public function update(Request $request, Showcase $showcase)
    {
        $data = $request->validate([
            'showcase_name'           => 'required|string|max:255',
            'showcase_date'           => 'required|date',
            'template_action'         => 'required|in:none,template,both',
            'break_even'              => 'nullable|numeric',
            'items'                   => 'required|array',
            'items.*.recipe_id'       => 'required|exists:recipes,id',
            'items.*.quantity'        => 'required|integer|min:0',
            'items.*.sold'            => 'required|integer|min:0',
            'items.*.reuse'           => 'required|integer|min:0',
            'items.*.waste'           => 'required|integer|min:0',
            'items.*.potential_income'=> 'nullable|numeric',
            'items.*.actual_revenue'  => 'nullable|numeric',
        ]);

        $data['save_template'] = in_array($data['template_action'], ['template', 'both']);

        $totalActual    = 0;
        $totalPotential = 0;
        foreach ($data['items'] as $item) {
            $totalActual    += $item['actual_revenue']   ?? 0;
            $totalPotential += $item['potential_income'] ?? 0;
        }

        $data['total_revenue']            = $totalActual;
        $data['plus']                     = round($totalActual - ($data['break_even'] ?? 0), 2);
        $data['real_margin']              = $totalActual > 0
            ? round(($data['plus'] / $totalActual) * 100, 2)
            : 0;
        $data['potential_income_average'] = count($data['items'])
            ? round($totalPotential / count($data['items']), 2)
            : 0;

        $showcase->update($data);

        $showcase->recipes()->delete();
        foreach ($data['items'] as $item) {
            $item['showcase_id'] = $showcase->id;
            ShowcaseRecipe::create($item);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase updated successfully.');
    }

    /**
     * Remove the specified showcase from storage.
     */
    public function destroy(Showcase $showcase)
    {
        $showcase->delete();
        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase deleted successfully.');
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
