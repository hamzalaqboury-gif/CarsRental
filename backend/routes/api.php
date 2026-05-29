<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// ─── Public Auth routes ───────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ─── Public vehicle browsing ──────────────────────────────────────────────────
Route::get('vehicles',      [VehicleController::class, 'index']);
Route::get('vehicles/{id}', [VehicleController::class, 'show']);

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware('jwt.auth')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'me']);
        Route::put('profile',  [AuthController::class, 'updateProfile']);
    });

    // File uploads
    Route::post('upload/driver-license', [UploadController::class, 'uploadDriverLicense']);
    Route::post('upload/avatar',         [UploadController::class, 'uploadAvatar']);

    // Reservations (role-aware controller)
    Route::get('reservations',                        [ReservationController::class, 'index']);
    Route::post('reservations',                       [ReservationController::class, 'store']);
    Route::get('reservations/{id}',                   [ReservationController::class, 'show']);
    Route::post('reservations/check-availability',    [ReservationController::class, 'checkAvailability']);
    Route::post('reservations/{id}/cancel',           [ReservationController::class, 'cancel']);

    // Payments
    Route::prefix('payments')->group(function () {
        Route::post('paypal/create',  [PaymentController::class, 'createPaypalOrder']);
        Route::get('paypal/success',  [PaymentController::class, 'paypalSuccess']);
        Route::get('paypal/cancel',   [PaymentController::class, 'paypalCancel']);
        Route::post('mock',           [PaymentController::class, 'mockPay']);
    });

    // Client dashboard
    Route::middleware('role:client,manager,admin,super-admin')
        ->get('dashboard/client', [ClientDashboard::class, 'index']);

    // ─── Admin / Manager routes ───────────────────────────────────────────────
    Route::middleware('role:admin,manager,super-admin')->group(function () {
        // Vehicle management
        Route::post('vehicles',        [VehicleController::class, 'store']);
        Route::put('vehicles/{id}',    [VehicleController::class, 'update']);
        Route::delete('vehicles/{id}', [VehicleController::class, 'destroy']);

        // Reservation management
        Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm']);

        // Dashboards
        Route::get('dashboard/manager',    [AdminDashboard::class, 'manager']);
    });

    // ─── Super-Admin / Admin routes ───────────────────────────────────────────
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::apiResource('admin/users', UserController::class);
        Route::post('admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::get('dashboard/admin',                 [AdminDashboard::class, 'superAdmin']);
    });
});
