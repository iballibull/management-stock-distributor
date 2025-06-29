<?php

namespace Database\Seeders\Book;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Book\EducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['name' => 'PAUD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SMP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MTS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SMA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SMK', 'created_at' => $now, 'updated_at' => $now],
        ];

        EducationLevel::insert($data);
    }
}
