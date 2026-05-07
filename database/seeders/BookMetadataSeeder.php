<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookMetadata;
use App\Models\MetadataAttribute;
use Illuminate\Database\Seeder;

class BookMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();
        $attributes = MetadataAttribute::all();

        if ($books->isEmpty() || $attributes->isEmpty()) {
            return;
        }

        foreach ($books as $book) {
            // Assigner 2 à 4 métadonnées au hasard pour chaque livre
            $randomAttributes = $attributes->random(rand(0, 6));

            foreach ($randomAttributes as $attr) {
                $value = '';
                switch ($attr->dataType) {
                    case 'number':
                        $value = (string) fake()->numberBetween(10, 1000);
                        break;
                    case 'boolean':
                        $value = fake()->boolean() ? '1' : '0';
                        break;
                    case 'date':
                        $value = fake()->date();
                        break;
                    case 'text':
                    default:
                        $value = fake()->word();
                        if ($attr->name === 'Dimensions') $value = '15x21 cm';
                        if ($attr->name === 'Langue') $value = 'Français';
                        if ($attr->name === 'Format') $value = 'Poche';
                        break;
                }

                BookMetadata::create([
                    'id' => fake()->uuid(),
                    'id_book' => $book->id,
                    'id_metadata_attribute' => $attr->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
