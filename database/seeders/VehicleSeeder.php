<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'registration' => 'LR3180',
                'name' => 'Toyota Wish',
                'category' => 'MPV',
                'daily_rate' => 150,
                'status' => 'available',
            ],
            [
                'registration' => 'LR3181',
                'name' => 'Hyundai Tucson',
                'category' => 'SUV',
                'daily_rate' => 220,
                'status' => 'available',
            ],
            [
                'registration' => 'LR3182',
                'name' => 'Toyota Hiace',
                'category' => 'Van',
                'daily_rate' => 250,
                'status' => 'available',
            ],
            [
                'registration' => 'LR3183',
                'name' => 'Nissan NV150',
                'category' => 'Van',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR3184',
                'name' => 'Ford Ranger',
                'category' => 'Pickup',
                'daily_rate' => 300,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4038',
                'name' => 'Nissan NV150',
                'category' => 'Van',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4039',
                'name' => 'Toyota Wish',
                'category' => 'MPV',
                'daily_rate' => 150,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4040',
                'name' => 'Toyota Wish',
                'category' => 'MPV',
                'daily_rate' => 150,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4041',
                'name' => 'Hyundai Tucson',
                'category' => 'SUV',
                'daily_rate' => 300,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4042',
                'name' => 'Nissan NV150',
                'category' => 'Van',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4250',
                'name' => 'Nissan NV150',
                'category' => 'Van',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4251',
                'name' => 'Nissan Urvan',
                'category' => 'Van',
                'daily_rate' => 300,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4252',
                'name' => 'Toyota Vitz',
                'category' => 'Hatchback',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4270',
                'name' => 'Nissan X-Trail',
                'category' => 'SUV',
                'daily_rate' => 220,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4287',
                'name' => 'Toyota Hiace',
                'category' => 'Van',
                'daily_rate' => 450,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4725',
                'name' => 'Toyota Fielder',
                'category' => 'Wagon',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4726',
                'name' => 'Toyota Fielder',
                'category' => 'Wagon',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4727',
                'name' => 'Mazda BT50',
                'category' => 'Pickup',
                'daily_rate' => 300,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4728',
                'name' => 'Isuzu Dmax',
                'category' => 'Pickup',
                'daily_rate' => 250,
                'status' => 'available',
            ],
            [
                'registration' => 'LR4729',
                'name' => 'Nissan Urvan',
                'category' => 'Van',
                'daily_rate' => 300,
                'status' => 'available',
            ],
            [
                'registration' => 'LR5355',
                'name' => 'Toyota Voxy',
                'category' => 'MPV',
                'daily_rate' => 220,
                'status' => 'available',
            ],
            [
                'registration' => 'LR5356',
                'name' => 'Nissan NV150',
                'category' => 'Van',
                'daily_rate' => 100,
                'status' => 'available',
            ],
            [
                'registration' => 'LR5357',
                'name' => 'Toyota Wish',
                'category' => 'MPV',
                'daily_rate' => 150,
                'status' => 'available',
            ],
            [
                'registration' => 'LR5358',
                'name' => 'Toyota Hiace',
                'category' => 'Van',
                'daily_rate' => 250,
                'status' => 'available',
            ],
            [
                'registration' => 'LR5359',
                'name' => 'Toyota Hiace',
                'category' => 'Van',
                'daily_rate' => 250,
                'status' => 'available',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(
                ['registration' => $vehicle['registration']],
                $vehicle
            );
        }
    }
}