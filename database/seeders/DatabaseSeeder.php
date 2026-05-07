<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            UserSeeder::class,
            AuthorSeeder::class,
            CategorySeeder::class,
            MetadataAttributeSeeder::class,
            BookSeeder::class,
            BookMetadataSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
