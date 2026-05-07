<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('id_role', 'R02')->get();
        $books = Book::all();

        if ($clients->isEmpty() || $books->isEmpty()) {
            return;
        }

        foreach ($books as $book) {
            // Créer entre 0 et 10 avis par livre
            $reviewCount = rand(0, 10);

            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'id' => fake()->uuid(),
                    'rating' => rand(1, 5),
                    'comment' => fake()->optional(0.8)->paragraph(),
                    'id_user' => $clients->random()->id,
                    'id_book' => $book->id,
                ]);
            }
        }
    }
}
