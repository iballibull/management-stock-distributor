<?php

namespace Database\Seeders\BookTransaction;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\BookTransaction\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        Semester::insert([
            [
                'name' => 'SEMESTER 1 2025',
                'year' => 2025,
                'semester_number' => '1',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SEMESTER 2 2025',
                'year' => 2025,
                'semester_number' => '2',
                'start_date' => '2025-01-01',
                'end_date' => '2025-06-30',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
