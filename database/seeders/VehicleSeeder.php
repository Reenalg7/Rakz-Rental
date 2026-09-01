<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'name' => 'Toyota Corolla',
            'category' => 'Sedan',
            'daily_rate' => 80.00,
            'status' => 'available',
        ]);

        Vehicle::create([
            'name' => 'Toyota RAV4',
            'category' => 'SUV',
            'daily_rate' => 120.00,
            'status' => 'available',
        ]);

        Vehicle::create([
            'name' => 'Toyota Hiace',
            'category' => 'Van',
            'daily_rate' => 150.00,
            'status' => 'available',
        ]);
    }
    
}
