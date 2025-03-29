<?php

namespace  App\Services\Book\Database\Factories;

use App\Data\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{

    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' =>fake()->unique()->word,
            'author' =>fake()->lastName(),

        ];
    }

}
