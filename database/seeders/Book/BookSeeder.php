<?php

namespace Database\Seeders\Book;

use App\Models\Book\Book;
use Illuminate\Support\Arr;
use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use Illuminate\Database\Seeder;
use App\Models\Book\EducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $books = [
            [
                'category_id' => 2,
                'education_level_id' => 10,
                'curriculum_id' => 1,
                'title' => 'Biologi',
                'image' => 'bookImages/biologi.png',
                'price' => 10000,
                'grade_number' => '12',
                'semester' => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => 2,
                'education_level_id' => 10,
                'curriculum_id' => 2,
                'title' => 'geografi',
                'image' => 'bookImages/geografi.png',
                'price' => 12000,
                'grade_number' => '12',
                'semester' => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => 3,
                'education_level_id' => 2,
                'curriculum_id' => 1,
                'title' => 'Mengenal Dirimu',
                'image' => 'bookImages/mengenal_dirimu.png',
                'price' => 9800,
                'grade_number' => 'BESAR',
                'semester' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => 1,
                'education_level_id' => 11,
                'curriculum_id' => 1,
                'title' => 'Pendidikan Pancasila',
                'image' => 'bookImages/pendidikan_pancasila.png',
                'price' => 8000,
                'grade_number' => '2',
                'semester' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        Book::insert($books);
    }
}
