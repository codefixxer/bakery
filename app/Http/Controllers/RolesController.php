<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
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
            'name'        => 'required|string|max:50|unique:roles,name',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $data['name']]);


     
            
            if (! empty($data['permissions'])) {
                // fetch the real Permission models for those IDs
                $perms = Permission::whereIn('id', $data['permissions'])->get();
                // and sync with the Role
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

    // Load the selected Permission *names* and sync
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
