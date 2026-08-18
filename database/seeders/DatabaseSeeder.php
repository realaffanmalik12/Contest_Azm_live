<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@smartsociety.test',
            'password' => Hash::make('Admin@123'),
            'phone' => '03000000001',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Demo Resident',
            'username' => 'resident',
            'email' => 'resident@smartsociety.test',
            'password' => Hash::make('Resident@123'),
            'phone' => '03000000002',
            'role' => 'resident',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Demo Guard',
            'username' => 'guard',
            'email' => 'guard@smartsociety.test',
            'password' => Hash::make('Guard@123'),
            'phone' => '03000000003',
            'role' => 'guard',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Demo Maintenance',
            'username' => 'maintenance',
            'email' => 'maintenance@smartsociety.test',
            'password' => Hash::make('Maint@123'),
            'phone' => '03000000004',
            'role' => 'maintenance',
            'status' => 'active',
        ]);
    }
}