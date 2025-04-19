<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PastryChef;

class PastryChefController extends Controller
{
    // Show all chefs
    public function index()
    {
        $pastryChefs = PastryChef::latest()->get(); // fetch all chefs
    
        return view('frontend.pastry-chefs.index', compact('pastryChefs'));
    }
    

    // Show create form
    public function create()
    {
        return view('frontend.pastry-chefs.create');
    }

    // Store new chef
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        PastryChef::create($request->only('name', 'email', 'phone'));

        return redirect()->route('pastry-chefs.index')->with('success', 'Chef added successfully!');
    }

    // Show edit form
    public function edit(PastryChef $pastryChef)
{
    return view('frontend.pastry-chefs.create', compact('pastryChef'));
}
    

    // Update existing chef
    public function update(Request $request, PastryChef $pastryChef)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $pastryChef->update($request->only('name', 'email', 'phone'));

        return redirect()->route('pastry-chefs.index')->with('success', 'Chef updated successfully!');
    }

    // Delete a chef
    public function destroy(PastryChef $pastryChef)
    {
        $pastryChef->delete();

        return redirect()->route('pastry-chefs.index')->with('success', 'Chef deleted successfully!');
    }
}
