<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the logged‑in user’s departments.
     */
    public function index()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;
    
        $groupUserIds = \App\Models\User::where('created_by', $groupRootId)
                            ->pluck('id')
                            ->push($groupRootId);
    
        $departments = \App\Models\Department::with('user') // eager load creator
                            ->whereIn('user_id', $groupUserIds)
                            ->orWhereHas('user.roles', fn($q) => $q->where('name', 'super')) // allow super-created
                            ->latest()
                            ->get();
    
        return view('frontend.departments.index', compact('departments'));
    }
    

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('frontend.departments.create');
    }

    /**
     * Store a newly created department for this user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data['user_id'] = Auth::id();

        Department::create($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created!');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        if ($department->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('frontend.departments.create', compact('department'));
    }

    /**
     * Update the specified department (only if it belongs to the user).
     */
    public function update(Request $request, Department $department)
    {
        if ($department->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated!');
    }
    public function show(Department $department)
    {
        return view('frontend.departments.show', compact('department'));
    }
    /**
     * Remove the specified department (only if it belongs to the user).
     */
    public function destroy(Department $department)
    {
        if ($department->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted!');
    }
}
