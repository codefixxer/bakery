<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        // start with the base query
        $query = User::with('roles');
    
        // if the current user is _not_ a super‑admin, restrict to their own users
        if (! auth()->user()->hasRole('super')) {
            $query->where('created_by', auth()->id());
        }
    
        // paginate whatever the query returns
        $users = $query->paginate(10);
    
        return view('frontend.user-management.users.index', compact('users'));
    }
    

    public function create()
    {
        // all other roles are always shown
        $query = Role::whereNotIn('name', ['admin', 'super']);
    
        // if the logged‑in user *can* add admins, include those too
        if (auth()->user()->can('can add admin')) {
            // remove the restriction
            $roles = Role::all();
        } else {
            $roles = $query->get();
        }
    
        $user   = new User();
        $isEdit = false;
    
        return view('frontend.user-management.users.create', compact('roles', 'user', 'isEdit'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'created_by' => auth()->id(),
        ]);

        $role = Role::findOrFail($data['role']);
        $user->syncRoles($role);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->hasRole('superadmin')) {
            $roles = Role::all(); // Superadmin can edit any role
        } else {
            // Regular admins can't assign/edit to roles with admin/super in the name
            $roles = Role::where(function ($query) {
                $query->whereRaw("LOWER(name) NOT LIKE ?", ['%admin%'])
                      ->whereRaw("LOWER(name) NOT LIKE ?", ['%super%']);
            })->get();
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
