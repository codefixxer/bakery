<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    // List all equipment
    public function index()
    {
        // rename to $equipments
        $equipments = Equipment::latest()->get();
        return view('frontend.equipment.index', compact('equipments'));
    }

    // Show create form
    public function create()
    {
        return view('frontend.equipment.create'); // shared form
    }

    // Store new equipment
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        Equipment::create($request->only('name'));

        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully!');
    }

    // Show edit form
    public function edit(Equipment $equipment)
    {
        return view('frontend.equipment.create', compact('equipment')); // shared form
    }

    // Update existing equipment
    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $equipment->update($request->only('name', 'type'));

        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully!');
    }

    // Delete equipment
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully!');
    }
}
