<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
        $createdBy = $admin?->id ?? 1;

        $vehicles = [
            [
                'brand'         => 'Toyota',
                'model'         => 'Camry',
                'category'      => 'sedan',
                'transmission'  => 'automatic',
                'fuel_type'     => 'petrol',
                'price_per_day' => 65.00,
                'description'   => 'Comfortable midsize sedan, perfect for business travel.',
                'is_available'  => true,
                'year'          => 2023,
                'seats'         => 5,
                'color'         => 'Silver',
                'license_plate' => 'ABC-1001',
            ],
            [
                'brand'         => 'Ford',
                'model'         => 'Explorer',
                'category'      => 'suv',
                'transmission'  => 'automatic',
                'fuel_type'     => 'petrol',
                'price_per_day' => 95.00,
                'description'   => 'Spacious SUV ideal for family road trips.',
                'is_available'  => true,
                'year'          => 2023,
                'seats'         => 7,
                'color'         => 'White',
                'license_plate' => 'ABC-1002',
            ],
            [
                'brand'         => 'Tesla',
                'model'         => 'Model 3',
                'category'      => 'sedan',
                'transmission'  => 'automatic',
                'fuel_type'     => 'electric',
                'price_per_day' => 120.00,
                'description'   => 'Premium electric vehicle with autopilot features.',
                'is_available'  => true,
                'year'          => 2024,
                'seats'         => 5,
                'color'         => 'Red',
                'license_plate' => 'ABC-1003',
            ],
            [
                'brand'         => 'BMW',
                'model'         => '5 Series',
                'category'      => 'luxury',
                'transmission'  => 'automatic',
                'fuel_type'     => 'petrol',
                'price_per_day' => 180.00,
                'description'   => 'Luxury sedan with premium interior and cutting-edge tech.',
                'is_available'  => true,
                'year'          => 2024,
                'seats'         => 5,
                'color'         => 'Black',
                'license_plate' => 'ABC-1004',
            ],
            [
                'brand'         => 'Chevrolet',
                'model'         => 'Silverado',
                'category'      => 'truck',
                'transmission'  => 'automatic',
                'fuel_type'     => 'diesel',
                'price_per_day' => 110.00,
                'description'   => 'Heavy-duty pickup truck for work and adventure.',
                'is_available'  => true,
                'year'          => 2022,
                'seats'         => 5,
                'color'         => 'Blue',
                'license_plate' => 'ABC-1005',
            ],
            [
                'brand'         => 'Honda',
                'model'         => 'Civic',
                'category'      => 'economy',
                'transmission'  => 'manual',
                'fuel_type'     => 'petrol',
                'price_per_day' => 45.00,
                'description'   => 'Fuel-efficient compact car, great value for money.',
                'is_available'  => true,
                'year'          => 2022,
                'seats'         => 5,
                'color'         => 'Gray',
                'license_plate' => 'ABC-1006',
            ],
            [
                'brand'         => 'Mercedes-Benz',
                'model'         => 'Sprinter',
                'category'      => 'van',
                'transmission'  => 'automatic',
                'fuel_type'     => 'diesel',
                'price_per_day' => 130.00,
                'description'   => 'Large cargo/passenger van with ample space.',
                'is_available'  => true,
                'year'          => 2023,
                'seats'         => 9,
                'color'         => 'White',
                'license_plate' => 'ABC-1007',
            ],
            [
                'brand'         => 'Porsche',
                'model'         => 'Cayenne',
                'category'      => 'luxury',
                'transmission'  => 'automatic',
                'fuel_type'     => 'hybrid',
                'price_per_day' => 250.00,
                'description'   => 'Ultra-luxury sports SUV with hybrid powertrain.',
                'is_available'  => true,
                'year'          => 2024,
                'seats'         => 5,
                'color'         => 'Midnight Blue',
                'license_plate' => 'ABC-1008',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(
                ['license_plate' => $vehicle['license_plate']],
                array_merge($vehicle, ['created_by' => $createdBy])
            );
        }

        $this->command->info('Vehicles seeded.');
    }
}
