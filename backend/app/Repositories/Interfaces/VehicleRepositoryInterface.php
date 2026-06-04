<?php

namespace App\Repositories\Interfaces;

use App\Models\Vehicle;
use Illuminate\Pagination\LengthAwarePaginator;

interface VehicleRepositoryInterface
{
    public function findById(int $id): ?Vehicle;
    public function create(array $data): Vehicle;
    public function update(Vehicle $vehicle, array $data): Vehicle;
    public function delete(Vehicle $vehicle): bool;
    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator;
    public function getAvailableForDates(string $startDate, string $endDate, array $filters = []): LengthAwarePaginator;
    public function countAvailable(): int;
    public function countTotal(): int;
}
