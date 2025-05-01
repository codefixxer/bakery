<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::orderBy('date','desc')->paginate(15);
        return view('frontend.incomes.index', compact('incomes'));
    }

    public function create()
    {
        return view('frontend.incomes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        Income::create($data);

        return redirect()->route('incomes.index')
                         ->with('success','Income recorded!');
    }


    public function storeRange(Request $request)
    {
        // range‐sum income
        $data = $request->validate([
            'from'   => 'required|date',
            'to'     => 'required|date|after_or_equal:from',
            'amount' => 'required|numeric|min:0',
        ]);

        // create one Income entry with date = from
        $inc = Income::create([
            'date'   => $data['from'],
            'amount' => $data['amount'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success'=>true,'income'=>$inc],201);
        }
        return back()->with('success','Range added to income!');
    }

    public function edit(Income $income)
    {
        return view('frontend.incomes.create', compact('income'));
    }

    public function update(Request $request, Income $income)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        $income->update($data);

        return redirect()->route('incomes.index')
                         ->with('success','Income updated!');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return back()->with('success','Income removed.');
    }
}
