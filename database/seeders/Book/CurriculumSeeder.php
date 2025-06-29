<?php

namespace Database\Seeders\Book;

use App\Models\Book\Curriculum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Curriculum::insert([
            [
                'name' => 'KURIKULUM MERDEKA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KURIKULUM 2013',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
