<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'brand'         => fake()->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Tesla', 'Chevrolet']),
            'model'         => fake()->word(),
            'category'      => fake()->randomElement(['sedan', 'suv', 'truck', 'van', 'luxury', 'economy']),
            'transmission'  => fake()->randomElement(['automatic', 'manual']),
            'fuel_type'     => fake()->randomElement(['petrol', 'diesel', 'electric', 'hybrid']),
            'price_per_day' => fake()->randomFloat(2, 40, 300),
            'description'   => fake()->sentence(10),
            'is_available'  => true,
            'year'          => fake()->numberBetween(2015, 2024),
            'seats'         => fake()->randomElement([4, 5, 7, 9]),
            'color'         => fake()->colorName(),
            'license_plate' => strtoupper(fake()->bothify('??-####')),
            'created_by'    => User::factory(),
        ];
    }
}
