<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentService
{
    private string $baseUrl;
    private ?string $accessToken = null;

    public function __construct(private ReservationService $reservationService)
    {
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? config('services.paypal.live_url')
            : config('services.paypal.sandbox_url');
    }

    // ─── PayPal ───────────────────────────────────────────────────────────────

    public function createPaypalOrder(Reservation $reservation): array
    {
        $token = $this->getPaypalAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent'         => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => (string) $reservation->id,
                        'description'  => "Reservation #{$reservation->id} - {$reservation->vehicle->brand} {$reservation->vehicle->model}",
                        'amount'       => [
                            'currency_code' => 'USD',
                            'value'         => number_format($reservation->total_price, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => config('app.url') . "/api/payments/paypal/success",
                    'cancel_url' => config('app.url') . "/api/payments/paypal/cancel",
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to create PayPal order: ' . $response->body(), 500);
        }

        $order = $response->json();

        Payment::create([
            'reservation_id'  => $reservation->id,
            'user_id'         => $reservation->user_id,
            'amount'          => $reservation->total_price,
            'method'          => 'paypal',
            'status'          => 'pending',
            'paypal_order_id' => $order['id'],
            'gateway_response' => $order,
        ]);

        $approvalUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;

        return [
            'order_id'    => $order['id'],
            'approve_url' => $approvalUrl,
        ];
    }

    public function capturePaypalPayment(string $orderId, string $payerId): array
    {
        $token = $this->getPaypalAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        if (!$response->successful()) {
            throw new \Exception('PayPal capture failed: ' . $response->body(), 500);
        }

        $capture = $response->json();

        return DB::transaction(function () use ($orderId, $payerId, $capture) {
            $payment = Payment::where('paypal_order_id', $orderId)->firstOrFail();

            $transactionId = $capture['purchase_units'][0]['payments']['captures'][0]['id'] ?? Str::uuid();

            $payment->update([
                'status'           => 'completed',
                'transaction_id'   => $transactionId,
                'paypal_payer_id'  => $payerId,
                'gateway_response' => $capture,
                'paid_at'          => now(),
            ]);

            $this->reservationService->markAsPaid($payment->reservation);

            ActivityLog::log('payment_completed', $payment->user_id, Payment::class, $payment->id);

            return ['payment' => $payment->fresh('reservation'), 'capture' => $capture];
        });
    }

    public function handlePaypalCancel(string $orderId): void
    {
        Payment::where('paypal_order_id', $orderId)
            ->update(['status' => 'failed']);
    }

    // ─── Mock Payment ─────────────────────────────────────────────────────────

    public function processMockPayment(Reservation $reservation): Payment
    {
        return DB::transaction(function () use ($reservation) {
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'user_id'        => $reservation->user_id,
                'amount'         => $reservation->total_price,
                'method'         => 'mock',
                'status'         => 'completed',
                'transaction_id' => 'MOCK-' . strtoupper(Str::random(16)),
                'paid_at'        => now(),
                'gateway_response' => ['mock' => true, 'auto_approved' => true],
            ]);

            $this->reservationService->markAsPaid($reservation);

            ActivityLog::log('mock_payment_completed', $reservation->user_id, Payment::class, $payment->id);

            return $payment->fresh('reservation');
        });
    }

    // ─── PayPal helpers ───────────────────────────────────────────────────────

    private function getPaypalAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.client_secret')
        )->asForm()->post("{$this->baseUrl}/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Unable to authenticate with PayPal', 500);
        }

        $this->accessToken = $response->json('access_token');
        return $this->accessToken;
    }
}
