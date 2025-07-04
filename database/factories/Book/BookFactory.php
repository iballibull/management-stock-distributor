<?php

namespace Database\Factories\Book;

use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use App\Models\Book\EducationLevel;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = Category::pluck('id');
        $educationLevelIds = EducationLevel::pluck('id');
        $curriculumIds = Curriculum::pluck('id');

        return [
            'category_id' => Arr::random($categoryIds->toArray()),
            'education_level_id' => Arr::random($educationLevelIds->toArray()),
            'curriculum_id' => Arr::random($curriculumIds->toArray()),
            'title' => $this->faker->sentence(3),
            'image' => 'bookImages/default.png',
            'price' => $this->faker->numberBetween(10000, 100000),
            'grade_number' => $this->faker->numberBetween(1, 3),
            'semester' => $this->faker->randomElement([1, 2]),
        ];
    }

}
