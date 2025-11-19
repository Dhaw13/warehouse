<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view user', only: ['index']),
            new Middleware('permission:edit user', only: ['edit', 'update']),
            new Middleware('permission:create user', only: ['create', 'store']),
            new Middleware('permission:delete user', only: ['destroy']),
        ];
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withInput()
                ->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'asc')->get();
        $hasRoles = $user->roles->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'hasRoles'));
    }

 public function update($id, Request $request)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:8',
    ]);

    if ($validator->fails()) {
        return redirect()->route('users.edit', $user->id)
            ->withInput()
            ->withErrors($validator);
    }

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

   
    $roleIds = $request->input('roles', []);
    $roles = Role::whereIn('id', $roleIds)->get();
    $user->syncRoles($roles);

    return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
}
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User tidak ditemukan.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "User '{$name}' berhasil dihapus.");
    }
}