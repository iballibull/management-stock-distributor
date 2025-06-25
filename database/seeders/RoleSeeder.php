<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'Owner',
                'description' => 'Digunakan untuk pemilik bisnis',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Admin',
                'description' => 'Digunakan untuk admin kantor',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Sales',
                'description' => 'Digunakan untuk sales',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ]
        ]);
    }
}
