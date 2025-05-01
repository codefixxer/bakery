<?php

namespace App\Http\Controllers;

use App\Models\Showcase;
use App\Models\ExternalSupply;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordFilterController extends Controller
{
    /**
     * Show the filter form + records.
     */
    public function index(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');
    
        // Get all dates already in the income table
        $incomeDates = Income::pluck('date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
    
        // 1) Filter Showcase records by date and exclude dates already in income
        $showcaseRecords = Showcase::with('recipes.recipe')
            ->when($from, fn($q) => $q->whereDate('showcase_date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('showcase_date', '<=', $to))
            ->whereNotIn(DB::raw('DATE(showcase_date)'), $incomeDates)
            ->orderBy('showcase_date')
            ->get();
    
        // 2) Filter ExternalSupply records by date and exclude income dates
        $externalRecords = ExternalSupply::with([
                'client',
                'recipes.recipe',
                'returnedGoods.recipes.recipe',
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
        ]);
    }
    


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
    
        // If both are missing or empty, reject
        if (
            (!isset($data['showcase']) || count($data['showcase']) === 0) &&
            (!isset($data['external']) || count($data['external']) === 0)
        ) {
            return redirect()->back()->withErrors('At least one of Showcase or External records must be provided.');
        }
    
        $toInsert = [];
    
        if (!empty($data['showcase'])) {
            foreach ($data['showcase'] as $row) {
                $toInsert[] = [
                    'date'   => $row['date'],
                    'amount' => $row['amount'],
                ];
            }
        }
    
        if (!empty($data['external'])) {
            foreach ($data['external'] as $row) {
                $toInsert[] = [
                    'date'   => $row['date'],
                    'amount' => $row['amount'],
                ];
            }
        }
    
        Income::insert($toInsert);
    
        return redirect()->back()->with('success', 'Income records added.');
    }
    
}
