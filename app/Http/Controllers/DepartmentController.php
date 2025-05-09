<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\User;
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

        // 1) Build your two‑level group of user IDs
        if (is_null($user->created_by)) {
            // Top‑level user: yourself + anyone you created
            $visibleUserIds = User::where('created_by', $user->id)
                                  ->pluck('id')
                                  ->push($user->id)
                                  ->unique();
        } else {
            // Child user: yourself + your creator
            $visibleUserIds = collect([$user->id, $user->created_by])->unique();
        }

        // 2) Fetch departments belonging to those users OR global ones (user_id NULL)
        $departments = Department::with('user')
                            ->where(function($q) use ($visibleUserIds) {
                                $q->whereIn('user_id', $visibleUserIds)
                                  ->orWhereNull('user_id');
                            })
                            ->latest()
                            ->paginate(10);

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
    $user = Auth::user();

    // Allow if user is the creator OR has admin or manager role
    if (
        $department->user_id !== $user->id &&
        !$user->hasRole('admin') &&
        !$user->hasRole('super') &&
        !$user->hasRole('master')
    ) {
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
