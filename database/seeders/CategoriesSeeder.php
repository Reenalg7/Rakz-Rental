<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Sedan',   'description' => 'Comfortable 4-door cars for city and highway driving.'],
            ['name' => 'SUV',     'description' => 'Spacious sport utility vehicles for families and off-road.'],
            ['name' => 'Van',     'description' => 'High-capacity vans for group travel and tours.'],
            ['name' => 'Compact', 'description' => 'Fuel-efficient small cars for urban driving.'],
        ];

        foreach ($items as $it) {
            Category::updateOrCreate(['name' => $it['name']], $it);
        }
    }
}
