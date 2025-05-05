<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Recipe;
use App\Models\ExternalSupply;
use App\Models\ExternalSupplyRecipe;
use App\Models\ReturnedGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ReturnedGoodController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;

        $groupUserIds = \App\Models\User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId);

        $clients = Client::whereIn('user_id', $groupUserIds)
            ->orderBy('name')
            ->get();

        $rgQuery = ReturnedGood::with(['client', 'externalSupply', 'recipes'])
            ->whereIn('user_id', $groupUserIds);

        if ($request->filled('client_id')) {
            $rgQuery->where('client_id', $request->client_id);
        }

        if ($request->filled('start_date')) {
            $rgQuery->where('return_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $rgQuery->where('return_date', '<=', $request->end_date);
        }

        $returnedGoods = $rgQuery->orderBy('return_date', 'desc')->paginate(15);

        $sups = ExternalSupply::whereIn('user_id', $groupUserIds)
            ->selectRaw('supply_date as date, SUM(total_amount) as total_supply')
            ->groupBy('supply_date')
            ->get();

        $returnsByDate = $returnedGoods->groupBy(fn($rg) => Carbon::parse($rg->return_date)->format('Y-m-d'));

        $report = $sups->map(function ($s) use ($returnsByDate) {
            $dateKey = Carbon::parse($s->date)->format('Y-m-d');
            $totalReturn = 0;

            if ($returnsByDate->has($dateKey)) {
                $returnedGoodsOnDate = $returnsByDate->get($dateKey);

                $totalReturn = $returnedGoodsOnDate
                    ->flatMap(function ($rg) {
                        return $rg->recipes;
                    })
                    ->sum('total_amount');
            }

            return (object)[
                'date'         => $s->date,
                'total_supply' => $s->total_supply,
                'total_return' => $totalReturn,
                'net'          => $s->total_supply - $totalReturn,
            ];
        });

        $grandSupply = $sups->sum('total_supply');
        $grandReturn = collect($returnedGoods->items())
            ->flatMap->recipes
            ->sum('total_amount');
        $grandNet = $grandSupply - $grandReturn;

        return view('frontend.returned-goods.index', compact(
            'clients', 'returnedGoods', 'report', 'grandSupply', 'grandReturn', 'grandNet'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;

        $groupUserIds = \App\Models\User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId);

        $externalSupplyId = $request->query('external_supply_id');

        if (!$externalSupplyId) {
            abort(Response::HTTP_BAD_REQUEST, 'Missing external_supply_id');
        }

        $externalSupply = ExternalSupply::with(['client', 'recipes.recipe', 'recipes.returns'])
            ->whereIn('user_id', $groupUserIds)
            ->findOrFail($externalSupplyId);

        return view('frontend.returned-goods.form', compact('externalSupply'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'           => 'required|exists:clients,id',
            'external_supply_id'  => 'required|exists:external_supplies,id',
            'return_date'         => 'required|date',
            'recipes'             => 'required|array|min:1',
            'recipes.*.qty'       => 'required|integer|min:0',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($data, $userId) {
            $rg = ReturnedGood::create([
                'client_id'           => $data['client_id'],
                'external_supply_id'  => $data['external_supply_id'],
                'return_date'         => $data['return_date'],
                'total_amount'        => 0,
                'user_id'             => $userId,
            ]);

            $grandTotal = 0;

            foreach ($data['recipes'] as $lineId => $row) {
                $line = ExternalSupplyRecipe::findOrFail($lineId);
                $toReturn = (int)$row['qty'];
                $remaining = $line->remaining_qty;

                if ($toReturn > $remaining) {
                    $recipeName = optional($line->recipe)->recipe_name ?? "Unknown Recipe";
                    abort(Response::HTTP_UNPROCESSABLE_ENTITY,
                        "Cannot return more than {$remaining} for {$recipeName}");
                }

                if ($toReturn <= 0) {
                    continue;
                }

                $lineTotal = round($line->price * $toReturn, 2);

                $rg->recipes()->create([
                    'external_supply_recipe_id' => $line->id,
                    'price'                     => $line->price,
                    'qty'                       => $toReturn,
                    'total_amount'              => $lineTotal,
                ]);

                $grandTotal += $lineTotal;
            }

            $rg->update(['total_amount' => $grandTotal]);
        });

        return redirect()
            ->route('returned-goods.index')
            ->with('success', 'Return recorded!');
    }

    public function edit(ReturnedGood $returnedGood)
    {
        if ($returnedGood->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $userId = Auth::id();

        $clients = Client::where('user_id', $userId)->orderBy('name')->get();
        $recipes = Recipe::where('user_id', $userId)->orderBy('recipe_name')->get();

        return view('frontend.returned-goods.form', compact(
            'returnedGood', 'clients', 'recipes'
        ));
    }

    public function update(Request $request, ReturnedGood $returnedGood)
    {
        if ($returnedGood->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'client_id'           => 'required|exists:clients,id',
            'return_date'         => 'required|date',
            'recipes'             => 'required|array|min:1',
            'recipes.*.qty'       => 'required|integer|min:1',
            'recipes.*.price'     => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $returnedGood) {
            $returnedGood->update([
                'client_id'    => $data['client_id'],
                'return_date'  => $data['return_date'],
                'total_amount' => 0,
            ]);

            $returnedGood->recipes()->delete();
            $grandTotal = 0;

            foreach ($data['recipes'] as $line) {
                $lineTotal = round($line['price'] * $line['qty'], 2);
                $returnedGood->recipes()->create([
                    'recipe_id'    => $line['id'],
                    'price'        => $line['price'],
                    'qty'          => $line['qty'],
                    'total_amount' => $lineTotal,
                ]);
                $grandTotal += $lineTotal;
            }

            $returnedGood->update(['total_amount' => $grandTotal]);
        });

        return redirect()
            ->route('returned-goods.index')
            ->with('success', 'Returned goods updated!');
    }

    public function destroy(ReturnedGood $returnedGood)
    {
        if ($returnedGood->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $returnedGood->delete();

        return redirect()
            ->route('returned-goods.index')
            ->with('success', 'Returned goods deleted!');
    }
}
