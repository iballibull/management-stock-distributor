<?php

namespace Database\Seeders\Book;

use App\Models\Book\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'name' => 'UMUM',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'PILIHAN',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'MUATAN LOKAL',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        Category::insert($data);
    }
}
