<?php

namespace Database\Seeders\User;

use App\Models\User\User;
use Carbon\Carbon;
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
        $now = Carbon::now();
        $password = Hash::make('password');

        $data = [
            [
                'name' => 'Owner',
                'password' => $password,
                'email' => 'owner@gmail.com',
                'role_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Admin',
                'password' => $password,
                'email' => 'admin@gmail.com',
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'password' => $password,
                'email' => 'sales@gmail.com',
                'role_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];

        User::insert($data);
    }
}
