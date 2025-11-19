<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        

        Permission::create(['name'=>'create user']);
        Permission::create(['name'=>'edit user']);
        Permission::create(['name'=>'delete user']);

        Permission::create(['name'=>'create role']);
        Permission::create(['name'=>'edit role']);
        Permission::create(['name'=>'delete role']);        
        
        Permission::create(['name'=>'create permission']);
        Permission::create(['name'=>'edit permission']);
        Permission::create(['name'=>'delete permission']);
        
        Permission::create(['name'=>'view user']);
        Permission::create(['name'=>'view role']);
        Permission::create(['name'=>'view permission']);

     Role::create(['name'=>'super admin']);
     Role::create(['name'=>'kepala gudang']);
     Role::create(['name'=>'admin gudang']);
     Role::create(['name'=>'petugas gudang']);

        $roleSuperAdmin = Role::findByName('super admin');
        $roleSuperAdmin->givePermissionTo('edit role');
        $roleSuperAdmin->givePermissionTo('create user');
        $roleSuperAdmin->givePermissionTo('edit user');
        $roleSuperAdmin->givePermissionTo('delete user');
        $roleSuperAdmin->givePermissionTo('create role');

        $roleSuperAdmin->givePermissionTo('delete role');
        $roleSuperAdmin->givePermissionTo('create permission');
        $roleSuperAdmin->givePermissionTo('edit permission');
        $roleSuperAdmin->givePermissionTo('delete permission');
        

        $roleSuperAdmin->givePermissionTo('view role');
        $roleSuperAdmin->givePermissionTo('view user');
        $roleSuperAdmin->givePermissionTo('view permission');


    }
}
