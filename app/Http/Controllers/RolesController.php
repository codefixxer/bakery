<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('frontend.user-management.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('frontend.user-management.roles.create', [
            'isEdit'      => false,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name',
            'permissions'  => 'sometimes|array',
            'permissions.*'=> 'exists:permissions,id',
        ]);

        // stamp with the current user's ID
        $data['user_id'] = Auth::id();

        // create the role (make sure 'user_id' is fillable on your Role model)
        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'web',
            'user_id'    => $data['user_id'],
        ]);

        if (! empty($data['permissions'])) {
            $perms = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($perms);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('frontend.user-management.roles.create', [
            'isEdit'      => true,
            'role'        => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name,' . $role->id,
            'permissions'  => 'sometimes|array',
            'permissions.*'=> 'exists:permissions,id',
        ]);

        $role->name = $data['name'];
        $role->save();

        if (! empty($data['permissions'])) {
            $names = Permission::whereIn('id', $data['permissions'])
                               ->pluck('name')
                               ->toArray();
            $role->syncPermissions($names);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted.');
    }
}
