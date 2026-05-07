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
        $password = Hash::make('password');

        $roles = Role::all()->pluck('id');

        foreach ($roles as $role) {
            User::create([
                'id' => fake()->uuid(),
                'firstName' => fake()->firstName(),
                'lastName' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $password,
                'phone' => fake()->optional()->phoneNumber(),
                'profilePhotoUrl' => fake()->optional()->imageUrl(),
                'id_role' => $role,
            ]);
        }
    }
}
