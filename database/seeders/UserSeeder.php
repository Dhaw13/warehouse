<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'Superadmin@gmail.com',
            'password' => bcrypt ('superadmin123')
        ]);
        $superadmin->assignRole('super admin');

        $kepalagudang = User::create([
            'name' => 'kepalagudang',
            'email' => 'Kepalagudang@gmail.com',
            'password' => bcrypt ('kepalagudang123')
        ]);
        $kepalagudang->assignRole('kepala gudang');

        $Admingudang = User::create([
            'name' => 'admingudang',
            'email' => 'Admingudang@gmail.com',
            'password' => bcrypt ('admingudang123')
        ]);
        $Admingudang->assignRole('admin gudang');

        $Petugasgudang = User::create([
            'name' => 'petugasgudang',
            'email' => 'Petugasgudang@gmail.com',
            'password' => bcrypt ('petugasgudang123')
        ]);
        $Petugasgudang->assignRole('petugas gudang');
    }
   
}