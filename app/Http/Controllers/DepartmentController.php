<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->get();
        return view('frontend.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('frontend.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Department::create(['name' => $request->name]);
        return redirect()->route('departments.index')->with('success', 'Department created!');
    }

    public function edit(Department $department)
    {
        return view('frontend.departments.create', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $department->update(['name' => $request->name]);
        return redirect()->route('departments.index')->with('success', 'Department updated!');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->back()->with('success', 'Department deleted!');
    }
}
