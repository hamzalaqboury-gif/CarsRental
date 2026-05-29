<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Vehicle;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleService
{
    public function __construct(
        private VehicleRepositoryInterface $vehicleRepo,
        private FileUploadService $fileService
    ) {}

    public function list(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->vehicleRepo->paginate($perPage, $filters);
    }

    public function getAvailableForDates(string $startDate, string $endDate, array $filters = []): LengthAwarePaginator
    {
        return $this->vehicleRepo->getAvailableForDates($startDate, $endDate, $filters);
    }

    public function find(int $id): ?Vehicle
    {
        return $this->vehicleRepo->findById($id);
    }

    public function create(array $data, ?object $image = null): Vehicle
    {
        if ($image) {
            $data['image'] = $this->fileService->uploadVehicleImage($image);
        }

        $data['created_by'] = auth()->id();

        $vehicle = $this->vehicleRepo->create($data);

        ActivityLog::log('vehicle_created', null, Vehicle::class, $vehicle->id, [], $data);

        return $vehicle;
    }

    public function update(Vehicle $vehicle, array $data, ?object $image = null): Vehicle
    {
        $oldData = $vehicle->toArray();

        if ($image) {
            if ($vehicle->image) {
                $this->fileService->delete($vehicle->image);
            }
            $data['image'] = $this->fileService->uploadVehicleImage($image);
        }

        $updated = $this->vehicleRepo->update($vehicle, $data);

        ActivityLog::log('vehicle_updated', null, Vehicle::class, $vehicle->id, $oldData, $data);

        return $updated;
    }

    public function delete(Vehicle $vehicle): bool
    {
        if ($vehicle->image) {
            $this->fileService->delete($vehicle->image);
        }

        ActivityLog::log('vehicle_deleted', null, Vehicle::class, $vehicle->id, $vehicle->toArray());

        return $this->vehicleRepo->delete($vehicle);
    }

    public function stats(): array
    {
        return [
            'total'     => $this->vehicleRepo->countTotal(),
            'available' => $this->vehicleRepo->countAvailable(),
        ];
    }
}
