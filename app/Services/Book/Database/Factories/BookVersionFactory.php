<?php

namespace  App\Services\Book\Database\Factories;

use App\Data\Models\Book;
use App\Data\Models\BookVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\Book\Enums\VersionStatusEnum;

/**
 * @extends Factory<BookVersion>
 */
class BookVersionFactory extends Factory
{

    protected $model = BookVersion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [

            'book_id' => Book::factory(),
            'condition' => $this->faker->randomElement(['new', 'used', 'damaged']),

            'repair_history' => json_encode([
                'repair_description' => $this->faker->sentence(),
                'repair_date' => $this->faker->date(),
                'repaired_by' => $this->faker->name(),
            ]),

            // 'status' => $this->faker->randomElement(VersionStatusEnum::values()),
            'status' => "0",
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),


        ];
    }
}
