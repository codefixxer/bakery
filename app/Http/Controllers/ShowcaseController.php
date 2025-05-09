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
                'recipes.recipe',
                'user'
            ])
            ->whereIn('user_id', $groupUserIds)
            ->latest()
            ->get();

        return view('frontend.showcase.index', compact('showcases'));
    }

    public function create()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;

        $groupUserIds = User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId);

        $recipes    = Recipe::whereIn('user_id', $groupUserIds)->get();
        $laborCost  = LaborCost::first();
        $templates  = Showcase::where('save_template', true)
                              ->whereIn('user_id', $groupUserIds)
                              ->pluck('showcase_name', 'id');

        $isEdit = false;

        return view('frontend.showcase.create', compact(
            'recipes', 'laborCost', 'templates', 'isEdit'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'showcase_date'           => 'required|date',
            'template_action'         => 'nullable|in:none,template,both',
            'items'                   => 'required|array|min:1',
            'items.*.recipe_id'       => 'required|exists:recipes,id',
            'items.*.price'           => 'required|numeric|min:0',
            'items.*.quantity'        => 'required|integer|min:0',
            'items.*.sold'            => 'required|integer|min:0',
            'items.*.reuse'           => 'required|integer|min:0',
            'items.*.waste'           => 'required|integer|min:0',
            'items.*.potential_income'=> 'required|numeric|min:0',
            'items.*.actual_revenue'  => 'required|numeric|min:0',
        ]);

        if (in_array($request->template_action, ['template', 'both'])) {
            $request->validate([
                'showcase_name' => 'required|string|max:255'
            ]);
        }

        $userId        = Auth::id();
        $data          = $request->all();
        $data['save_template'] = in_array($data['template_action'], ['template', 'both']);

        $totalRevenue   = array_sum(array_column($data['items'], 'actual_revenue'));
        $totalPotential = array_sum(array_column($data['items'], 'potential_income'));
        $breakEven      = LaborCost::first()?->daily_bep ?? 0;
        $plus           = round($totalRevenue - $breakEven, 2);
        $realMargin     = $totalRevenue > 0
                            ? round(($plus / $totalRevenue) * 100, 2)
                            : 0;
        $potentialAvg   = count($data['items'])
                            ? round($totalPotential / count($data['items']), 2)
                            : 0;

        $showcase = Showcase::create([
            'showcase_name'             => $data['showcase_name'] ?? null,
            'showcase_date'             => $data['showcase_date'],
            'template_action'           => $data['template_action'],
            'save_template'             => $data['save_template'],
            'break_even'                => $breakEven,
            'total_revenue'             => $totalRevenue,
            'plus'                      => $plus,
            'real_margin'               => $realMargin,
            'potential_income_average'  => $potentialAvg,
            'user_id'                   => $userId,
        ]);

        foreach ($data['items'] as $item) {
            ShowcaseRecipe::create([
                'showcase_id'     => $showcase->id,
                'recipe_id'       => $item['recipe_id'],
                'price'           => $item['price'],
                'quantity'        => $item['quantity'],
                'sold'            => $item['sold'],
                'reuse'           => $item['reuse'],
                'waste'           => $item['waste'],
                'potential_income'=> $item['potential_income'],
                'actual_revenue'  => $item['actual_revenue'],
                'user_id'         => $userId,
            ]);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase created successfully.');
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
            'potential_income'=> $r->potential_income,
            'actual_revenue'  => $r->actual_revenue,
        ]);

        return response()->json([
            'showcase_name'   => $template->showcase_name,
            'showcase_date'   => $template->showcase_date->format('Y-m-d'),
            'template_action' => $template->template_action,
            'rows'            => $rows,
        ]);
    }

    public function edit(Showcase $showcase)
    {
        abort_if($showcase->user_id !== Auth::id(), 403);

        $userId    = Auth::id();
        $recipes   = Recipe::where('user_id', $userId)->get();
        $laborCost = LaborCost::first();
        $templates = Showcase::where('save_template', true)
                             ->where('user_id', $userId)
                             ->pluck('showcase_name', 'id');

        $isEdit = true;
        $showcase->load('recipes');

        return view('frontend.showcase.create', compact(
            'showcase', 'recipes', 'laborCost', 'templates', 'isEdit'
        ));
    }

    public function update(Request $request, Showcase $showcase)
    {
        abort_if($showcase->user_id !== Auth::id(), 403);

        $request->validate([
            'showcase_date'           => 'required|date',
            'template_action'         => 'nullable|in:none,template,both',
            'items'                   => 'required|array|min:1',
            'items.*.recipe_id'       => 'required|exists:recipes,id',
            'items.*.price'           => 'required|numeric|min:0',
            'items.*.quantity'        => 'required|integer|min:0',
            'items.*.sold'            => 'required|integer|min:0',
            'items.*.reuse'           => 'required|integer|min:0',
            'items.*.waste'           => 'required|integer|min:0',
            'items.*.potential_income'=> 'required|numeric|min:0',
            'items.*.actual_revenue'  => 'required|numeric|min:0',
        ]);

        if (in_array($request->template_action, ['template', 'both'])) {
            $request->validate([
                'showcase_name' => 'required|string|max:255'
            ]);
        }

        $data          = $request->all();
        $data['save_template'] = in_array($data['template_action'], ['template', 'both']);

        $totalRevenue   = array_sum(array_column($data['items'], 'actual_revenue'));
        $totalPotential = array_sum(array_column($data['items'], 'potential_income'));
        $breakEven      = LaborCost::first()?->daily_bep ?? 0;
        $plus           = round($totalRevenue - $breakEven, 2);
        $realMargin     = $totalRevenue > 0
                            ? round(($plus / $totalRevenue) * 100, 2)
                            : 0;
        $potentialAvg   = count($data['items'])
                            ? round($totalPotential / count($data['items']), 2)
                            : 0;

        $showcase->update([
            'showcase_name'             => $data['showcase_name'] ?? null,
            'showcase_date'             => $data['showcase_date'],
            'template_action'           => $data['template_action'],
            'save_template'             => $data['save_template'],
            'break_even'                => $breakEven,
            'total_revenue'             => $totalRevenue,
            'plus'                      => $plus,
            'real_margin'               => $realMargin,
            'potential_income_average'  => $potentialAvg,
        ]);

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
                'potential_income'=> $item['potential_income'],
                'actual_revenue'  => $item['actual_revenue'],
                'user_id'         => Auth::id(),
            ]);
        }

        return redirect()
            ->route('showcase.index')
            ->with('success', 'Showcase updated successfully.');
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
