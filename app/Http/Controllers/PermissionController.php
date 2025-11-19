<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permission', only: ['index']),
            new Middleware('permission:edit permission', only: ['edit']),
            new Middleware('permission:create permission', only: ['create']),
            new Middleware('permission:delete permission', only: ['destroy']),
        ];
    }

    public function index()
    {
        $permissions = Permission::orderBy('id', 'asc')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan');
        }

        return redirect()->route('permissions.create')->withInput()->withErrors($validator);
    }

    public function edit(string $id)
    {
        $permissions = Permission::findOrFail($id);
        return view('permissions.edit', compact('permissions'));
    }

    public function update(Request $request, $id)
    {
        $permissions = Permission::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id,
        ]);

        if ($validator->passes()) {
            $permissions->update(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui');
        }

        return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
    }

    public function destroy($id)
    {
        $permissions = Permission::find($id);

        if (!$permissions) {
            return redirect()->route('permissions.index')->with('error', 'Permission tidak ditemukan.');
        }

        $name = $permissions->name;
        $permissions->delete();

        return redirect()->route('permissions.index')->with('success', "Permission '{$name}' berhasil dihapus.");
    }
}
