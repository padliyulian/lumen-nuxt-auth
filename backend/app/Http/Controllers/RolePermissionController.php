<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use App\Models\User;

class RolePermissionController extends Controller
{
    public function roleList()
    {
        $roles = Role::select('id', 'name')->get();
        return $roles;
    }

    public function permissionList()
    {
        $permissions = Permission::select('id', 'name')->get();
        return $permissions;
    }

    public function rolePermission($role)
    {   
        $hasPermission = DB::table('role_has_permissions')
            ->select('permissions.id', 'permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_id', $role)->get();
        return $hasPermission;
        // $role = Role::with('permissions')->findOrFail($role);
        // return $role;
    }

    public function setRolePermission(Request $request, $role)
    {
        // $this->validate($request, [
        //     'new_permission' => 'required'
        // ]);

        $role = Role::with('permissions')->findOrFail($role); 
        $role->syncPermissions($request->new_permission);
        return $role;
    }

    // public function setRoleUser(Request $request)
    // {
    //     $this->validate($request, [
    //         'user_id' => 'required|exists:users,id',
    //         'role' => 'required'
    //     ]);

    //     $user = User::find($request->user_id);
    //     $user->syncRoles([$request->role]);
    //     return response()->json(['status' => 'success']);
    // }

    // public function getUserPermissions()
    // {
    //     $role = auth()->user()->roles->pluck('name');
    //     $role_id = auth()->user()->roles->pluck('id');

    //     $hasPermission = DB::table('role_has_permissions')
    //         ->select('permissions.name')
    //         ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
    //         ->where('role_id', $role_id[0])->get();

    //     return response()->json(['status' => 'success', 'role' => $role, 'data' => $hasPermission]);
    // }
}
