<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepo,
        private VehicleRepositoryInterface $vehicleRepo
    ) {}

    public function listAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reservationRepo->paginate($perPage, $filters);
    }

    public function listForUser(int $userId, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->reservationRepo->paginateForUser($userId, $perPage, $filters);
    }

    public function find(int $id): ?Reservation
    {
        return $this->reservationRepo->findById($id);
    }

    public function create(array $data): Reservation
    {
        $vehicle = $this->vehicleRepo->findById($data['vehicle_id']);

        if (!$vehicle) {
            throw new \Exception('Vehicle not found', 404);
        }

        if (!$vehicle->is_available) {
            throw new \Exception('Vehicle is not available for rental', 422);
        }

        if ($this->reservationRepo->hasOverlap($data['vehicle_id'], $data['start_date'], $data['end_date'])) {
            throw new \Exception('Vehicle is already booked for the selected dates', 422);
        }

        $startDate  = Carbon::parse($data['start_date']);
        $endDate    = Carbon::parse($data['end_date']);
        $totalDays  = $startDate->diffInDays($endDate);

        if ($totalDays < 1) {
            throw new \Exception('Minimum rental period is 1 day', 422);
        }

        $totalPrice = $vehicle->price_per_day * $totalDays;

        return DB::transaction(function () use ($data, $totalDays, $totalPrice) {
            $reservation = $this->reservationRepo->create([
                'user_id'         => $data['user_id'] ?? auth()->id(),
                'vehicle_id'      => $data['vehicle_id'],
                'start_date'      => $data['start_date'],
                'end_date'        => $data['end_date'],
                'total_days'      => $totalDays,
                'total_price'     => $totalPrice,
                'status'          => 'pending',
                'payment_status'  => 'unpaid',
                'pickup_location' => $data['pickup_location'] ?? null,
                'return_location' => $data['return_location'] ?? null,
                'notes'           => $data['notes'] ?? null,
            ]);

            ActivityLog::log('reservation_created', null, Reservation::class, $reservation->id);

            return $reservation;
        });
    }

    public function confirm(Reservation $reservation): Reservation
    {
        if (!$reservation->isPending()) {
            throw new \Exception('Only pending reservations can be confirmed', 422);
        }

        return $this->reservationRepo->update($reservation, [
            'status'       => 'confirmed',
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);
    }

    public function cancel(Reservation $reservation, string $reason = ''): Reservation
    {
        if ($reservation->isPaid()) {
            throw new \Exception('Paid reservations cannot be cancelled directly. Please request a refund.', 422);
        }

        return DB::transaction(function () use ($reservation, $reason) {
            $updated = $this->reservationRepo->update($reservation, [
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => $reason,
            ]);

            ActivityLog::log('reservation_cancelled', null, Reservation::class, $reservation->id);

            return $updated;
        });
    }

    public function markAsPaid(Reservation $reservation): Reservation
    {
        return $this->reservationRepo->update($reservation, [
            'status'         => 'paid',
            'payment_status' => 'paid',
        ]);
    }

    public function checkAvailability(int $vehicleId, string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        return !$this->reservationRepo->hasOverlap($vehicleId, $startDate, $endDate, $excludeId);
    }

    public function getDashboardStats(): array
    {
        return array_merge(
            $this->reservationRepo->getRevenueStats(),
            ['status_counts' => $this->reservationRepo->getStatusCounts()]
        );
    }
}
