<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un administrateur
        User::create([
            'firstName' => 'Admin',
            'lastName' => 'Sanoudji',
            'email' => 'admin@sanoudji.com',
            'password' => Hash::make('12345678'),
            'id_role' => 'R01',
        ]);

        // Création d'un client spécifique
        User::create([
            'firstName' => 'Client',
            'lastName' => 'Test',
            'email' => 'client@sanoudji.com',
            'password' => Hash::make('12345678'),
            'id_role' => 'R02',
        ]);

        $roles = Role::all()->pluck('id');

        foreach ($roles as $role) {
            User::factory(10)->create([
                'id_role' => $role,
            ]);
        }
    }
}
