<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            Author::create([
                'id' => fake()->uuid(),
                'name' => fake()->name(),
                'biography' => fake()->optional()->paragraph(),
                'profilePhotoUrl' => fake()->optional()->imageUrl(),
            ]);
        }
    }
}
