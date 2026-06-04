<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $startDate  = fake()->dateTimeBetween('+1 day', '+30 days');
        $endDate    = fake()->dateTimeBetween($startDate, '+60 days');
        $totalDays  = $startDate->diff($endDate)->days ?: 1;
        $pricePerDay = fake()->randomFloat(2, 40, 300);

        return [
            'user_id'        => User::factory(),
            'vehicle_id'     => Vehicle::factory(),
            'start_date'     => $startDate->format('Y-m-d'),
            'end_date'       => $endDate->format('Y-m-d'),
            'total_days'     => $totalDays,
            'total_price'    => $pricePerDay * $totalDays,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ];
    }
}
