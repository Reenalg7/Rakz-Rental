<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    public function run(): void
    {
        // Get vehicle categories
        $suv = Category::where('name', 'SUV')->first();
        $van = Category::where('name', 'Van')->first();
        $compact = Category::where('name', 'Compact')->first();

        // Rakz Rental actual vehicle fleet
        $fleet = [

            // ---------------------------------------------------------
            // 1. Toyota Wish - LR3180
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR3180',
                'name' => 'Toyota Wish',
                'make' => 'Toyota',
                'model' => 'Wish',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 150,
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Comfortable and practical vehicle suitable for family trips.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 2. Hyundai Tucson - LR3181
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR3181',
                'name' => 'Hyundai Tucson',
                'make' => 'Hyundai',
                'model' => 'Tucson',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 220,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Spacious SUV suitable for family and long-distance travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 3. Toyota Hiace - LR3182
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR3182',
                'name' => 'Toyota Hiace',
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 250,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large van suitable for group transportation.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 4. Nissan NV150 - LR3183
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR3183',
                'name' => 'Nissan NV150',
                'make' => 'Nissan',
                'model' => 'NV150',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Affordable and practical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 5. Ford Ranger - LR3184
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR3184',
                'name' => 'Ford Ranger',
                'make' => 'Ford',
                'model' => 'Ranger',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 300,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'description' => 'Powerful vehicle suitable for both city and outdoor travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 6. Nissan NV150 - LR4038
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4038',
                'name' => 'Nissan NV150',
                'make' => 'Nissan',
                'model' => 'NV150',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Affordable and practical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 7. Toyota Wish - LR4039
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4039',
                'name' => 'Toyota Wish',
                'make' => 'Toyota',
                'model' => 'Wish',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 150,
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Comfortable vehicle suitable for family trips.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 8. Toyota Wish - LR4040
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4040',
                'name' => 'Toyota Wish',
                'make' => 'Toyota',
                'model' => 'Wish',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 150,
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Comfortable vehicle suitable for family trips.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 9. Hyundai Tucson - LR4041
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4041',
                'name' => 'Hyundai Tucson',
                'make' => 'Hyundai',
                'model' => 'Tucson',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 300,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Spacious SUV suitable for family travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 10. Nissan NV150 - LR4042
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4042',
                'name' => 'Nissan NV150',
                'make' => 'Nissan',
                'model' => 'NV150',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Affordable and practical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 11. Nissan NV150 - LR4250
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4250',
                'name' => 'Nissan NV150',
                'make' => 'Nissan',
                'model' => 'NV150',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Affordable and practical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 12. Nissan Urvan - LR4251
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4251',
                'name' => 'Nissan Urvan',
                'make' => 'Nissan',
                'model' => 'Urvan',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 300,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large passenger van suitable for group travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 13. Toyota Vitz - LR4252
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4252',
                'name' => 'Toyota Vitz',
                'make' => 'Toyota',
                'model' => 'Vitz',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Small and economical vehicle suitable for city driving.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 14. Nissan X-Trail - LR4270
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4270',
                'name' => 'Nissan X-Trail',
                'make' => 'Nissan',
                'model' => 'X-Trail',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 220,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Spacious SUV suitable for family and long-distance travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 15. Toyota Hiace - LR4287
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4287',
                'name' => 'Toyota Hiace',
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 450,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large van suitable for group transportation.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 16. Toyota Fielder - LR4725
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4725',
                'name' => 'Toyota Fielder',
                'make' => 'Toyota',
                'model' => 'Fielder',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Practical and economical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 17. Toyota Fielder - LR4726
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4726',
                'name' => 'Toyota Fielder',
                'make' => 'Toyota',
                'model' => 'Fielder',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Practical and economical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 18. Mazda BT50 - LR4727
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4727',
                'name' => 'Mazda BT50',
                'make' => 'Mazda',
                'model' => 'BT50',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 300,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'description' => 'Powerful pickup suitable for different types of travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 19. Isuzu Dmax - LR4728
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4728',
                'name' => 'Isuzu Dmax',
                'make' => 'Isuzu',
                'model' => 'Dmax',
                'year' => 2022,
                'category_id' => $suv?->id,
                'daily_rate' => 250,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'description' => 'Reliable pickup suitable for work and travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 20. Nissan Urvan - LR4729
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR4729',
                'name' => 'Nissan Urvan',
                'make' => 'Nissan',
                'model' => 'Urvan',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 300,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large passenger van suitable for group travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 21. Toyota Voxy - LR5355
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR5355',
                'name' => 'Toyota Voxy',
                'make' => 'Toyota',
                'model' => 'Voxy',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 220,
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Comfortable family van suitable for group travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 22. Nissan NV150 - LR5356
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR5356',
                'name' => 'Nissan NV150',
                'make' => 'Nissan',
                'model' => 'NV150',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 100,
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Affordable and practical vehicle for everyday travel.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 23. Toyota Wish - LR5357
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR5357',
                'name' => 'Toyota Wish',
                'make' => 'Toyota',
                'model' => 'Wish',
                'year' => 2022,
                'category_id' => $compact?->id,
                'daily_rate' => 150,
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'description' => 'Comfortable vehicle suitable for family trips.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 24. Toyota Hiace - LR5358
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR5358',
                'name' => 'Toyota Hiace',
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 250,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large van suitable for group transportation.',

                // Image will be added later
                // 'image_url' => null,
            ],

            // ---------------------------------------------------------
            // 25. Toyota Hiace - LR5359
            // ---------------------------------------------------------
            [
                'registration_no' => 'LR5359',
                'name' => 'Toyota Hiace',
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2022,
                'category_id' => $van?->id,
                'daily_rate' => 250,
                'seats' => 14,
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'description' => 'Large van suitable for group transportation.',

                // Image will be added later
                // 'image_url' => null,
            ],
        ];

        // Add or update each vehicle in the database
        foreach ($fleet as $vehicle) {
            Vehicle::updateOrCreate(
                ['registration_no' => $vehicle['registration_no']],
                array_merge($vehicle, [
                    'status' => 'available',
                ])
            );
        }
    }
}