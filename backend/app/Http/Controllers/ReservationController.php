<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Services\ReservationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use ApiResponse;

    public function __construct(private ReservationService $reservationService) {}

    public function index(Request $request): JsonResponse
    {
        $user    = auth()->user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin', 'manager']);

        if ($isAdmin) {
            $filters    = $request->only(['status', 'user_id', 'vehicle_id', 'start_date', 'end_date']);
            $paginator  = $this->reservationService->listAll($filters, $request->integer('per_page', 15));
        } else {
            $filters   = $request->only(['status']);
            $paginator = $this->reservationService->listForUser($user->id, $request->integer('per_page', 15), $filters);
        }

        return $this->paginated($paginator, 'Reservations retrieved.');
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        try {
            $reservation = $this->reservationService->create($request->validated());
            return $this->created($reservation->load(['vehicle', 'user']), 'Reservation created successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        $reservation = $this->reservationService->find($id);

        if (!$reservation) {
            return $this->notFound('Reservation not found.');
        }

        $user = auth()->user();
        if (!$user->hasAnyRole(['super-admin', 'admin', 'manager']) && $reservation->user_id !== $user->id) {
            return $this->forbidden();
        }

        return $this->success($reservation);
    }

    public function confirm(int $id): JsonResponse
    {
        $reservation = $this->reservationService->find($id);

        if (!$reservation) {
            return $this->notFound('Reservation not found.');
        }

        try {
            $updated = $this->reservationService->confirm($reservation);
            return $this->success($updated, 'Reservation confirmed.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $reservation = $this->reservationService->find($id);

        if (!$reservation) {
            return $this->notFound('Reservation not found.');
        }

        $user = auth()->user();
        if (!$user->hasAnyRole(['super-admin', 'admin', 'manager']) && $reservation->user_id !== $user->id) {
            return $this->forbidden();
        }

        try {
            $updated = $this->reservationService->cancel($reservation, $request->reason ?? '');
            return $this->success($updated, 'Reservation cancelled.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        $available = $this->reservationService->checkAvailability(
            $request->vehicle_id,
            $request->start_date,
            $request->end_date
        );

        return $this->success(['available' => $available]);
    }
}
