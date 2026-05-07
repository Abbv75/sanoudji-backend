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
            $reviewCount = rand(0, 300);

            if ($reviewCount > 0) {
                Review::factory($reviewCount)->create([
                    'id_user' => fn() => $clients->random()->id,
                    'id_book' => $book->id,
                ]);
            }
        }
    }
}
