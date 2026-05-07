<?php

namespace Database\Seeders;

use App\Models\MetadataAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetadataAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Format',
                'description' => 'Le format physique du livre (Poche, Relié, Broché)',
                'dataType' => 'text',
            ],
            [
                'name' => 'Langue',
                'description' => 'La langue principale du livre',
                'dataType' => 'text',
            ],
            [
                'name' => 'Nombre de pages',
                'description' => 'Le nombre total de pages',
                'dataType' => 'number',
            ],
            [
                'name' => 'Poids',
                'description' => 'Le poids du livre en grammes',
                'dataType' => 'number',
            ],
            [
                'name' => 'Dimensions',
                'description' => 'Les dimensions du livre (ex: 15x20 cm)',
                'dataType' => 'text',
            ],
            [
                'name' => 'Éditeur',
                'description' => 'La maison d\'édition',
                'dataType' => 'text',
            ],
            [
                'name' => 'Disponible en numérique',
                'description' => 'Indique si une version ebook existe',
                'dataType' => 'boolean',
            ],
            [
                'name' => 'Date de dernière réédition',
                'description' => 'La date de la version la plus récente',
                'dataType' => 'date',
            ],
        ];

        foreach ($attributes as $attr) {
            MetadataAttribute::updateOrCreate(
                ['name' => $attr['name']],
                MetadataAttribute::factory()->make($attr)->toArray()
            );
        }
    }
}
