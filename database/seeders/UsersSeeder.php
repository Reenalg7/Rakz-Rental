<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates demo credentials used for the viva/demonstration.
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rakzrental.com'],
            [
                'name' => 'Rakz Admin',
                'password' => Hash::make('admin123'),
                'phone' => '+94 71 000 0000',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@rakzrental.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('customer123'),
                'phone' => '+94 77 111 2222',
                'address' => 'No. 10, Galle Road, Colombo',
                'nic' => '200012345678',
                'driver_license' => 'B1234567',
                'role' => 'customer',
            ]
        );
    }
}
