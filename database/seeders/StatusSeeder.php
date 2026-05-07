<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 'S01', 'name' => 'disponible', 'description' => 'Le produit est disponible en stock'],
            ['id' => 'S02', 'name' => 'en rupture de stock', 'description' => 'Le produit n\'est plus disponible'],
            ['id' => 'S03', 'name' => 'en attente', 'description' => 'La commande est en attente de validation'],
            ['id' => 'S04', 'name' => 'en cours', 'description' => 'La commande est en cours de traitement'],
            ['id' => 'S05', 'name' => 'terminé', 'description' => 'La commande est livrée et terminée'],
            ['id' => 'S06', 'name' => 'annulé', 'description' => 'La commande a été annulée'],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['id' => $status['id']],
                $status
            );
        }
    }
}
