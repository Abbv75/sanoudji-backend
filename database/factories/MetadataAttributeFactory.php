<?php

namespace Database\Factories;

use App\Models\MetadataAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MetadataAttribute>
 */
class MetadataAttributeFactory extends Factory
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
            'name' => fake()->unique()->word(),
            'description' => fake()->optional()->sentence(),
            'dataType' => fake()->randomElement(['text', 'number', 'boolean', 'date']),
        ];
    }
}
