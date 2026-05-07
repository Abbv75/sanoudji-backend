<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();
        $categories = Category::all();

        if ($authors->isEmpty() || $categories->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $book = Book::create([
                'id' => fake()->uuid(),
                'title' => fake()->sentence(3),
                'description' => fake()->optional()->paragraph(),
                'isbn' => fake()->optional()->isbn13(),
                'price' => fake()->numberBetween(1000, 10000), // En centimes ou unité entière
                'stock' => fake()->numberBetween(0, 100),
                'coverUrl' => fake()->optional()->imageUrl(),
                'publicationDate' => fake()->optional()->date(),
                'id_author' => $authors->random()->id,
            ]);

            // Attacher 1 à 3 catégories aléatoires
            $randomCategories = $categories->random(rand(1, 3))->pluck('id');
            $book->categories()->sync($randomCategories);
        }
    }
}
