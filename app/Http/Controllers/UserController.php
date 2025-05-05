<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $query = User::with('roles');

        if (! Auth::user()->hasRole('super')) {
            $query->where('created_by', Auth::id());
        }

        $users = $query->paginate(10);

        return view('frontend.user-management.users.index', compact('users'));
    }

    public function create()
    {
        $currentUser = Auth::user();
    
        if ($currentUser->hasRole('super')) {
            // Super may create any role *except* super
            $roles = Role::where('name', '!=', 'super')->get();
        } else {
            // Non‑super may create anything except super *and* admin
            $roles = Role::whereNotIn('name', ['super','admin'])->get();
        }
    
        $user   = new User();
        $isEdit = false;
    
        return view('frontend.user-management.users.create', compact('roles','user','isEdit'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);
    
        // Find the selected role
        $role = Role::findOrFail($data['role']);
    
        // If you’re creating an “admin”, created_by must be NULL.
        // Otherwise, it’s the current user’s ID.
        $createdBy = $role->name === 'admin'
                     ? null
                     : Auth::id();
    
        // Create the user
        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'created_by' => $createdBy,
        ]);
    
        // Assign the role
        $user->syncRoles($role);
    
        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }
    public function toggleStatus(User $user)
    {
        if (!Auth::user()->hasRole('super')) {
            abort(403);
        }
    
        $user->status = !$user->status;
        $user->save();
    
        // Deactivate or activate roles created by this user
        $relatedUsers = User::where('created_by', $user->id)->get();
        foreach ($relatedUsers as $relatedUser) {
            $relatedUser->status = $user->status;
            $relatedUser->save();
        }
    
        return redirect()->back()->with('success', 'User status updated.');
    }
    
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->hasRole('super')) {
            $roles = Role::all();
        } else {
            $roles = Role::whereNotIn('name', ['super', 'admin'])->get();
        }

        $isEdit = true;

        return view('frontend.user-management.users.create', compact('roles', 'user', 'isEdit'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $role = Role::findOrFail($data['role']);
        $user->syncRoles($role);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted.');
    }
}
