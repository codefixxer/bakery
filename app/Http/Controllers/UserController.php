<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('frontend.user-management.users.index', compact('users'));
    }

    public function create()
    {
        $roles  = Role::all();
        $user   = new User();        // <— empty user so blade can always call ->roles
        $isEdit = false;
    
        return view('frontend.user-management.users.create', compact(
            'roles', 'user', 'isEdit'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles'    => 'sometimes|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('frontend.user-management.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('frontend.user-management.users.create', [
            'isEdit' => true,
            'user'   => $user,
            'roles'  => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles'    => 'sometimes|array',
            'roles.*'  => 'exists:roles,id',
        ]);
    
        $user->name  = $data['name'];
        $user->email = $data['email'];
    
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
    
        $user->save();
    
        // turn IDs into names, then sync
        $names = Role::whereIn('id', $data['roles'] ?? [])->pluck('name')->all();
        $user->syncRoles($names);
    
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
