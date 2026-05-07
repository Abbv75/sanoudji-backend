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
        $roles = Role::all()->pluck('id');

        foreach ($roles as $role) {
            User::factory(50)->create([
                'id_role' => $role,
            ]);
        }
    }
}
