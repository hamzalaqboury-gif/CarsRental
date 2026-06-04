<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Services\VehicleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ApiResponse;

    public function __construct(private VehicleService $vehicleService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search', 'category', 'brand', 'transmission',
            'fuel_type', 'min_price', 'max_price', 'is_available',
            'start_date', 'end_date',
        ]);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $paginator = $this->vehicleService->getAvailableForDates(
                $request->start_date,
                $request->end_date,
                $filters
            );
        } else {
            $paginator = $this->vehicleService->list($filters, $request->integer('per_page', 12));
        }

        return $this->paginated($paginator, 'Vehicles retrieved.');
    }

    public function show(int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->find($id);

        if (!$vehicle) {
            return $this->notFound('Vehicle not found.');
        }

        return $this->success($vehicle);
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = $this->vehicleService->create(
            $request->validated(),
            $request->file('image')
        );

        return $this->created($vehicle, 'Vehicle created successfully.');
    }

    public function update(UpdateVehicleRequest $request, int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->find($id);

        if (!$vehicle) {
            return $this->notFound('Vehicle not found.');
        }

        $updated = $this->vehicleService->update($vehicle, $request->validated(), $request->file('image'));

        return $this->success($updated, 'Vehicle updated successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->find($id);

        if (!$vehicle) {
            return $this->notFound('Vehicle not found.');
        }

        $this->vehicleService->delete($vehicle);

        return $this->success(null, 'Vehicle deleted successfully.');
    }
}
