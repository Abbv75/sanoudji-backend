<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
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

        // Création de 400 livres
        Book::factory(400)->create([
            'id_author' => fn() => $authors->random()->id,
        ])->each(function ($book) use ($categories) {
            // Pour chaque livre, on attache entre 0 et 10 catégories aléatoires
            $randomCategories = $categories->random(rand(0, 10))->pluck('id');
            $book->categories()->attach($randomCategories);
        });
    }
}
