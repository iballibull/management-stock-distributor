<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Owner',
                'password' => Hash::make('password'),
                'email' => 'owner@gmail.com',
                'role_id' => 1,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email' => 'admin@gmail.com',
                'role_id' => 2,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Sales',
                'password' => Hash::make('password'),
                'email' => 'sales@gmail.com',
                'role_id' => 3,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ]
        ]);
    }
}
