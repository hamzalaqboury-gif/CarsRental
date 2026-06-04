<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\ReservationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PaymentService $paymentService,
        private ReservationService $reservationService
    ) {}

    public function createPaypalOrder(Request $request): JsonResponse
    {
        $request->validate(['reservation_id' => ['required', 'exists:reservations,id']]);

        $reservation = $this->reservationService->find($request->reservation_id);

        if (!$reservation) {
            return $this->notFound('Reservation not found.');
        }

        if ($reservation->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if ($reservation->isPaid()) {
            return $this->error('Reservation is already paid.', 422);
        }

        try {
            $result = $this->paymentService->createPaypalOrder($reservation);
            return $this->success($result, 'PayPal order created.');
        } catch (\Exception $e) {
            return $this->error('Failed to create PayPal order: ' . $e->getMessage(), 500);
        }
    }

    public function paypalSuccess(Request $request): JsonResponse
    {
        $request->validate([
            'token'   => ['required', 'string'],
            'PayerID' => ['required', 'string'],
        ]);

        try {
            $result = $this->paymentService->capturePaypalPayment($request->token, $request->PayerID);
            return $this->success($result['payment'], 'Payment completed successfully.');
        } catch (\Exception $e) {
            return $this->error('Payment failed: ' . $e->getMessage(), 500);
        }
    }

    public function paypalCancel(Request $request): JsonResponse
    {
        if ($request->token) {
            $this->paymentService->handlePaypalCancel($request->token);
        }

        return $this->error('Payment was cancelled.', 400);
    }

    public function mockPay(Request $request): JsonResponse
    {
        $request->validate(['reservation_id' => ['required', 'exists:reservations,id']]);

        $reservation = $this->reservationService->find($request->reservation_id);

        if (!$reservation) {
            return $this->notFound('Reservation not found.');
        }

        if ($reservation->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if ($reservation->isPaid()) {
            return $this->error('Reservation is already paid.', 422);
        }

        if (!$reservation->isConfirmed() && !$reservation->isPending()) {
            return $this->error('Reservation must be confirmed or pending before payment.', 422);
        }

        $payment = $this->paymentService->processMockPayment($reservation);

        return $this->success($payment, 'Mock payment processed successfully.');
    }
}
