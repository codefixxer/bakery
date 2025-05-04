<?php

namespace App\Http\Controllers;

use App\Models\Showcase;
use App\Models\ExternalSupply;
use App\Models\Income;
use App\Models\RecipeCategory;  // ← make sure to import
use App\Models\Department;      // ← make sure to import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordFilterController extends Controller
{
    /**
     * Show the filter form + records that this user hasn’t yet added to their income,
     * along with only this user’s categories & departments.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $from   = $request->query('from');
        $to     = $request->query('to');
    
        $categories  = RecipeCategory::where('user_id', $userId)->orderBy('name')->get();
        $departments = Department::where('user_id', $userId)->orderBy('name')->get();
    
        $incomeDates = Income::where('user_id', $userId)
            ->pluck('date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();
    
        $showcaseRecords = Showcase::with('recipes.recipe')
            ->when($from, fn($q) => $q->whereDate('showcase_date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('showcase_date', '<=', $to))
            ->whereNotIn(DB::raw('DATE(showcase_date)'), $incomeDates)
            ->orderBy('showcase_date')
            ->get();
    
        $externalRecords = ExternalSupply::with([
                'client',
                'recipes.recipe',
                'returnedGoods.recipes.supplyLine.recipe', // important
            ])
            ->when($from, fn($q) => $q->whereDate('supply_date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('supply_date', '<=', $to))
            ->whereNotIn(DB::raw('DATE(supply_date)'), $incomeDates)
            ->orderBy('supply_date')
            ->get();
    
        return view('frontend.records.index', [
            'showcaseRecords' => $showcaseRecords,
            'externalRecords' => $externalRecords,
            'from'            => $from,
            'to'              => $to,
            'categories'      => $categories,
            'departments'     => $departments,
        ]);
    }
    
    /**
     * Take the filtered rows and insert them into this user’s income.
     */
    public function addFiltered(Request $request)
    {
        $data = $request->validate([
            'showcase'            => 'array',
            'showcase.*.date'     => 'required_with:showcase|date',
            'showcase.*.amount'   => 'required_with:showcase|numeric',
            'external'            => 'array',
            'external.*.date'     => 'required_with:external|date',
            'external.*.amount'   => 'required_with:external|numeric',
        ]);

        if (
            empty($data['showcase'] ?? []) &&
            empty($data['external'] ?? [])
        ) {
            return redirect()->back()
                             ->withErrors('At least one record must be provided.');
        }

        $toInsert = [];
        $userId   = Auth::id();

        foreach ($data['showcase'] ?? [] as $row) {
            $toInsert[] = [
                'date'    => $row['date'],
                'amount'  => $row['amount'],
                'user_id' => $userId,
            ];
        }

        foreach ($data['external'] ?? [] as $row) {
            $toInsert[] = [
                'date'    => $row['date'],
                'amount'  => $row['amount'],
                'user_id' => $userId,
            ];
        }

        Income::insert($toInsert);

        return redirect()->back()
                         ->with('success', 'Income records added.');
    }
}
