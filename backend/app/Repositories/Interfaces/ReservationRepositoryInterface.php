<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation;
    public function create(array $data): Reservation;
    public function update(Reservation $reservation, array $data): Reservation;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function paginateForUser(int $userId, int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function hasOverlap(int $vehicleId, string $startDate, string $endDate, ?int $excludeId = null): bool;
    public function getRevenueStats(): array;
    public function getStatusCounts(): array;
}
