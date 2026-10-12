<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Master seeder. Runs the sub-seeders in the correct order so FKs resolve.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,       // Admin + demo customer
            CategoriesSeeder::class,  // Sedan, SUV, Luxury, Van
            VehiclesSeeder::class,    // Fleet keyed by registration_no
        ]);
    }
}
