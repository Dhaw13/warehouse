<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
        new Middleware('permission:view role',only:['index']),
        new Middleware('permission:edit role',only:['edit']),
        new Middleware('permission:create role',only:['create']),
        new Middleware('permission:delete role',only:['destroy']),
        ];
    }

    public function index () {
        $roles = Role::orderBy('id','asc')->paginate(5);
        return view ('roles.index',[
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view ('roles.create',[
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()){
            $roles = Role::create(['name' => $request->name]);

            if(!empty($request->permission)){
                foreach($request->permission as $name){
                    $roles->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.index')->with('success', 'role success added');

        }else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
            
    }

    public function edit($id)
    {
        $roles = Role::findOrFail($id);
        $hasPermissions = $roles->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'asc')->get();

        return view ('roles.edit',[
            'roles' => $roles,
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions
        ]);
    }

    public function update($id, Request $request)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('roles.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $role->name = $request->name;
        $role->save();

        // ✅ Selalu sync, bahkan jika array kosong
        $permissions = $request->input('permission', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy($id)
    {
    $roles= Role::find($id);

    if (! $roles) {
        return redirect()
            ->route('roles.index')
            ->with('error', 'Permission tidak ditemukan.');
    }

    $name = $roles->name;
    $roles->delete();

    return redirect()
        ->route('roles.index')
        ->with('success', "Role '{$name}' berhasil dihapus.");
    }
}

