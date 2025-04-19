<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showcase;
use App\Models\ExternalSupply;

class RecordFilterController extends Controller
{
    public function index(Request $req)
    {
        // read filters
        $date = $req->query('date');
        $from = $req->query('from');
        $to   = $req->query('to');

        // base queries
        $scQuery = Showcase::with('recipes.recipe');
        $exQuery = ExternalSupply::with('client', 'recipes.recipe');

        if ($date) {
            $scQuery->whereDate('showcase_date', $date);
            $exQuery->whereDate('supply_date', $date);
        } elseif ($from && $to) {
            $scQuery->whereBetween('showcase_date', [$from, $to]);
            $exQuery->whereBetween('supply_date', [$from, $to]);
        }

        $showcaseRecords = $scQuery->orderBy('showcase_date')->get();
        $externalRecords = $exQuery->orderBy('supply_date')->get();

        return view('frontend.records.index', compact(
            'showcaseRecords',
            'externalRecords',
            'date','from','to'
        ));
    }
}
