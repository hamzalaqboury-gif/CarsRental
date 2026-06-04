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
                'image'         => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1536700503339-1e771d4c14d4?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1606611013016-969c19ba27bb?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800&q=80',
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
                'image'         => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(
                ['license_plate' => $vehicle['license_plate']],
                array_merge($vehicle, ['created_by' => $createdBy])
            );
        }

        $this->command->info('Vehicles seeded.');
    }
}
