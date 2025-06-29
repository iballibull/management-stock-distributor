<?php

namespace Database\Seeders\Book;

use App\Models\Book\Curriculum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'name' => 'KURIKULUM MERDEKA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'KURIKULUM 2013',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];

        Curriculum::insert($data);
    }
}
