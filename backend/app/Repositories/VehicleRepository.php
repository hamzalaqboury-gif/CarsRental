<?php

namespace App\Repositories;

use App\Models\Vehicle;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleRepository implements VehicleRepositoryInterface
{
    public function findById(int $id): ?Vehicle
    {
        return Vehicle::with('creator')->find($id);
    }

    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $vehicle->update($data);
        return $vehicle->fresh();
    }

    public function delete(Vehicle $vehicle): bool
    {
        return $vehicle->delete();
    }

    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator
    {
        $query = Vehicle::with('creator')->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function getAvailableForDates(string $startDate, string $endDate, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 12;

        $bookedIds = \DB::table('reservations')
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->whereNull('deleted_at')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->pluck('vehicle_id');

        $query = Vehicle::with('creator')
            ->available()
            ->whereNotIn('id', $bookedIds)
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function countAvailable(): int
    {
        return Vehicle::available()->count();
    }

    public function countTotal(): int
    {
        return Vehicle::count();
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (!empty($filters['brand'])) {
            $query->where('brand', 'like', "%{$filters['brand']}%");
        }

        if (!empty($filters['transmission'])) {
            $query->where('transmission', $filters['transmission']);
        }

        if (!empty($filters['fuel_type'])) {
            $query->where('fuel_type', $filters['fuel_type']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price_per_day', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price_per_day', '<=', $filters['max_price']);
        }

        if (isset($filters['is_available'])) {
            $query->where('is_available', $filters['is_available']);
        }
    }
}
