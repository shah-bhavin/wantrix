<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        Role::firstOrCreate([
            'name' => 'Vendor Owner'
        ]);

        Role::firstOrCreate([
            'name' => 'Vendor Manager'
        ]);

        Role::firstOrCreate([
            'name' => 'Vendor User'
        ]);
    }
}
