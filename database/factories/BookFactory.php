<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
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
        return [
            'id' => fake()->uuid(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'isbn' => fake()->optional()->isbn13(),
            'price' => fake()->numberBetween(1000, 10000),
            'stock' => fake()->numberBetween(0, 100),
            'coverUrl' => fake()->optional()->imageUrl(),
            'publicationDate' => fake()->optional()->date(),
            // id_author will be handled in seeder
        ];
    }
}
