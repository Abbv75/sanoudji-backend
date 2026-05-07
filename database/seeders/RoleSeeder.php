<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 'R01',
                'name' => 'administrateur',
                'description' => 'Administrateur avec un accès complet',
            ],
            [
                'id' => 'R02',
                'name' => 'client',
                'description' => 'Client régulier',
            ],
        ];

        foreach ($data as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
