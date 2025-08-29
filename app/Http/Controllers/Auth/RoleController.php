<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('e-com.roles-permissions.index', compact('users','roles', 'permissions'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'array'
    ]);

    $role = Role::create(['name' => $request->name]);

    if ($request->has('permissions')) {
         $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
    $role->syncPermissions($permissions);
    }
    return $this->getLatestRecords('Roles Add successfully');
}
    public function edit($id)
{
    $role = Role::with('permissions')->findOrFail($id);

    return response()->json([
        'id' => $role->id,
        'name' => $role->name,
        'permissions' => $role->permissions
    ]);
}
public function update(Request $request,)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'id' => 'required|exists:roles,id',
        'permissions' => 'array'
    ]);
    $role = Role::findOrFail($request->id);
    $role->update(['name' => $role->name]);
    $permissions = Permission::whereIn('id', $request->permissions ?? [])->pluck('name')->toArray();
    $role->syncPermissions($permissions ?? []);
    return $this->getLatestRecords('Role updated successfully');
}
public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:roles,id']);

        $roles = Role::findOrFail($request->id);
        $roles->delete();

        return $this->getLatestRecords('Employee deleted successfully');
    }
private function getLatestRecords($message = 'Roles fetched successfully')
    {
       $roles = Role::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.roles-permissions.data-table', compact('roles'))->render(),
            'message' => $message,
        ]);
    }
}
