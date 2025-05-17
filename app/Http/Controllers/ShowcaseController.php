<?php

namespace App\Http\Controllers;

use App\Models\Showcase;
use App\Models\ShowcaseRecipe;
use App\Models\Recipe;
use App\Models\LaborCost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowcaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;

        $groupUserIds = User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId);

        $showcases = Showcase::with([
            'recipes.recipe.ingredients.ingredient',
            'recipes.recipe'  // for price, sell_mode, recipe_weight, total_pieces
        ])
            ->whereIn('user_id', $groupUserIds)
            ->latest()
            ->get();



        return view('frontend.showcase.index', compact('showcases'));
    }




 public function create()
{
    $user        = Auth::user();
    $groupRootId = $user->created_by ?? $user->id;

    // get the group's labor cost record
    $laborCost     = LaborCost::where('user_id', $groupRootId)->first();
    $laborCostRate = $laborCost;

    // all user IDs in this group
    $groupUserIds = User::where('created_by', $groupRootId)
        ->pluck('id')
        ->push($groupRootId);

    // fetch valid recipes with ingredients
    $recipes = Recipe::with('ingredients')
        ->whereIn('user_id', $groupUserIds)
        ->where(function ($q) {
            $q->where(fn($q2) =>
                    $q2->where('sell_mode','kg')
                        ->where('selling_price_per_kg','>',0)
                )
                ->orWhere(fn($q2) =>
                    $q2->where('sell_mode','piece')
                        ->where('selling_price_per_piece','>',0)
                );
        })
        ->get();

    // compute each recipe's batch costs
    $recipes->each(function ($r) use ($laborCostRate) {
        $rate = $r->labor_cost_mode === 'external'
            ? ($laborCostRate->external_cost_per_min ?? 0)
            : ($laborCostRate->shop_cost_per_min     ?? 0);
        $r->batch_labor_cost = round($r->labour_time_min * $rate, 2);
        $r->batch_ing_cost   = $r->ingredients_cost_per_batch;
    });

    // load full Showcase models for "Choose Template"
    $templates = Showcase::where('save_template', true)
        ->whereIn('user_id', $groupUserIds)
        ->get();

    // flag for view
    $isEdit = false;

    return view('frontend.showcase.create', compact(
        'recipes',
        'laborCost',
        'laborCostRate',
        'templates',
        'isEdit'
    ));
}


    // public function create()
    // {
    //     $user = Auth::user();
    //     $groupRootId = $user->created_by ?? $user->id;

    //     $groupUserIds = User::where('created_by', $groupRootId)
    //         ->pluck('id')
    //         ->push($groupRootId);

    //     // $recipes    = Recipe::whereIn('user_id', $groupUserIds)->get();
    //     // $laborCost  = LaborCost::first();
    //     $recipes = Recipe::whereIn('user_id', $groupUserIds)
    //         ->where(function ($q) {
    //             $q->where(function ($q2) {
    //                 // if we sell by kg, price_per_kg must be > 0
    //                 $q2->where('sell_mode', 'kg')
    //                     ->where('selling_price_per_kg', '>', 0);
    //             })
    //                 ->orWhere(function ($q2) {
    //                     // if we sell by piece, price_per_piece must be > 0
    //                     $q2->where('sell_mode', 'piece')
    //                         ->where('selling_price_per_piece', '>', 0);
    //                 });
    //         })
    //         ->get();

    //     $groupRootId = $user->created_by ?? $user->id;
    //     $laborCost = \App\Models\LaborCost::where('user_id', $groupRootId)->first();

    //     $templates  = Showcase::where('save_template', true)
    //         ->whereIn('user_id', $groupUserIds)
    //         ->pluck('showcase_name', 'id');

    //     $isEdit = false;

    //     return view('frontend.showcase.create', compact(
    //         'recipes',
    //         'laborCost',
    //         'templates',
    //         'isEdit'
    //     ));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'showcase_name'              => 'nullable|string|max:255',
            'showcase_date'              => 'required|date',
            'template_action'            => 'nullable|in:none,template,both',
            // your item lines…
            'items'                      => 'required|array|min:1',
            'items.*.recipe_id'          => 'required|exists:recipes,id',
            'items.*.price'              => 'required|numeric|min:0',
            'items.*.quantity'           => 'required|integer|min:0',
            'items.*.sold'               => 'required|integer|min:0',
            'items.*.reuse'              => 'required|integer|min:0',
            'items.*.waste'              => 'required|integer|min:0',
            'items.*.potential_income'   => 'required|numeric|min:0',
            'items.*.actual_revenue'     => 'required|numeric|min:0',
            // now validating the summary fields from the form:
            'total_revenue'              => 'required|numeric|min:0',
            'plus'                       => 'required|numeric',
            'real_margin'                => 'required|numeric',
        ]);

        if (in_array($request->template_action, ['template', 'both'])) {
            // enforce name when saving as template
            $request->validate(['showcase_name' => 'required|string|max:255']);
        }

        $data     = $request->all();
        $userId   = Auth::id();
        $saveTpl  = in_array($data['template_action'], ['template', 'both']);

        // create the Showcase from the form inputs:
        $showcase = Showcase::create([
            'showcase_name'             => $data['showcase_name'] ?? null,
            'showcase_date'             => $data['showcase_date'],
            'template_action'           => $data['template_action'],
            'save_template'             => $saveTpl,
            'break_even'                => $data['break_even'],               // already on form
            'total_revenue'             => $data['total_revenue'],
            'plus'                      => $data['plus'],
            'potential_income_average'  => 0,
            'real_margin'               => $data['real_margin'],
            'user_id'                   => $userId,
        ]);

        // persist each line
        foreach ($data['items'] as $item) {
            ShowcaseRecipe::create([
                'showcase_id'     => $showcase->id,
                'recipe_id'       => $item['recipe_id'],
                'price'           => $item['price'],
                'quantity'        => $item['quantity'],
                'sold'            => $item['sold'],
                'reuse'           => $item['reuse'],
                'waste'           => $item['waste'],
                'potential_income' => $item['potential_income'],
                'actual_revenue'  => $item['actual_revenue'],
                'user_id'         => $userId,
            ]);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase created successfully.');
    }

    public function update(Request $request, Showcase $showcase)
    {
        // only the owner can edit
        abort_if($showcase->user_id !== Auth::id(), 403);

        // same validation rules as store()
        $request->validate([
            'showcase_name'              => 'nullable|string|max:255',
            'showcase_date'              => 'required|date',
            'template_action'            => 'nullable|in:none,template,both',

            // item lines
            'items'                      => 'required|array|min:1',
            'items.*.recipe_id'          => 'required|exists:recipes,id',
            'items.*.price'              => 'required|numeric|min:0',
            'items.*.quantity'           => 'required|integer|min:0',
            'items.*.sold'               => 'required|integer|min:0',
            'items.*.reuse'              => 'required|integer|min:0',
            'items.*.waste'              => 'required|integer|min:0',
            'items.*.potential_income'   => 'required|numeric|min:0',
            'items.*.actual_revenue'     => 'required|numeric|min:0',

            // summary fields from the form
            'total_revenue'              => 'required|numeric|min:0',
            'plus'                       => 'required|numeric',
            'real_margin'                => 'required|numeric',
        ]);

        // if saving as a template, name becomes required
        if (in_array($request->template_action, ['template', 'both'])) {
            $request->validate([
                'showcase_name' => 'required|string|max:255',
            ]);
        }

        $data    = $request->all();
        $userId  = Auth::id();
        $saveTpl = in_array($data['template_action'], ['template', 'both']);

        // update the parent exactly as store() does
        $showcase->update([
            'showcase_name'             => $data['showcase_name']             ?? null,
            'showcase_date'             => $data['showcase_date'],
            'template_action'           => $data['template_action'],
            'save_template'             => $saveTpl,
            'break_even'                => $data['break_even'],               // pulled from form
            'total_revenue'             => $data['total_revenue'],            // from form
            'plus'                      => $data['plus'],                     // from form
            'potential_income_average'  => 0,                                  // same as store()
            'real_margin'               => $data['real_margin'],              // from form
            'user_id'                   => $userId,
        ]);

        // drop & recreate all the lines, just like store()
        $showcase->recipes()->delete();
        foreach ($data['items'] as $item) {
            ShowcaseRecipe::create([
                'showcase_id'     => $showcase->id,
                'recipe_id'       => $item['recipe_id'],
                'price'           => $item['price'],
                'quantity'        => $item['quantity'],
                'sold'            => $item['sold'],
                'reuse'           => $item['reuse'],
                'waste'           => $item['waste'],
                'potential_income' => $item['potential_income'],
                'actual_revenue'  => $item['actual_revenue'],
                'user_id'         => $userId,
            ]);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase updated successfully.');
    }


    public function getTemplate($id)
    {
        $template = Showcase::with('recipes')
            ->where('id', $id)
            ->where('save_template', true)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $rows = $template->recipes->map(fn($r) => [
            'recipe_id'       => $r->recipe_id,
            'price'           => $r->price,
            'quantity'        => $r->quantity,
            'sold'            => $r->sold,
            'reuse'           => $r->reuse,
            'waste'           => $r->waste,
            'potential_income' => $r->potential_income,
            'actual_revenue'  => $r->actual_revenue,
        ]);

        return response()->json([
        'showcase_name'   => $template->showcase_name,
        'showcase_date'   => $template->showcase_date->format('Y-m-d'),
        'template_action' => $template->template_action,
          'break_even'      => $template->break_even,      // ← add this
        'details'         => $rows,  // <-- changed from 'rows' to 'details'
    ]);
    }

  public function edit(Showcase $showcase)
{
    abort_if($showcase->user_id !== Auth::id(), 403);

    $userId    = Auth::id();
    $recipes   = Recipe::where('user_id', $userId)->get();
    $laborCost = LaborCost::first();

    // ← define $templates just like in create()
    $templates = Showcase::where('save_template', true)
        ->where('user_id', $userId)
        ->get();

    $isEdit = true;
    $showcase->load('recipes');

    return view('frontend.showcase.create', compact(
        'showcase',
        'recipes',
        'laborCost',
        'templates',   // ← make sure this is here
        'isEdit'
    ));
}



    public function destroy(Showcase $showcase)
    {
        abort_if($showcase->user_id !== Auth::id(), 403);

        $showcase->recipes()->delete();
        $showcase->delete();

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase deleted successfully.');
    }

    public function show(Showcase $showcase)
    {
        $showcase->load('recipes.recipe', 'user');
        return view('frontend.showcase.show', compact('showcase'));
    }
}
