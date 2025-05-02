<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('superadmin')) {
            $users = User::with('roles')->paginate(10);
        } else {
            $users = User::where('created_by', auth()->id())->with('roles')->paginate(10);
        }

        return view('frontend.user-management.users.index', compact('users'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('superadmin')) {
            $roles = Role::all(); // Superadmin sees all roles
        } else {
            $roles = Role::where('name', '!=', 'admin')->get(); // Regular admins can't assign admin
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
            $roles = Role::where('name', '!=', 'admin')->get(); // Regular admins can't assign admin
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
