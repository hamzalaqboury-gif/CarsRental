<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepo,
        private VehicleRepositoryInterface $vehicleRepo
    ) {}

    public function getSuperAdminStats(): array
    {
        $roleDistribution = User::with('roles')
            ->get()
            ->groupBy(fn($u) => $u->getRoleNames()->first() ?? 'none')
            ->map->count();

        $monthlyRevenue = Reservation::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        return [
            'users' => [
                'total'            => User::count(),
                'role_distribution' => $roleDistribution,
                'new_this_month'   => User::whereMonth('created_at', now()->month)->count(),
            ],
            'vehicles' => [
                'total'     => $this->vehicleRepo->countTotal(),
                'available' => $this->vehicleRepo->countAvailable(),
            ],
            'reservations' => array_merge(
                $this->reservationRepo->getStatusCounts(),
                ['total' => Reservation::count()]
            ),
            'revenue'         => $this->reservationRepo->getRevenueStats(),
            'monthly_revenue' => $monthlyRevenue,
            'recent_activity' => ActivityLog::with('user')
                ->latest()
                ->limit(20)
                ->get(),
        ];
    }

    public function getManagerStats(): array
    {
        $recentReservations = Reservation::with(['user', 'vehicle'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->limit(10)
            ->get();

        return [
            'fleet' => [
                'total'     => $this->vehicleRepo->countTotal(),
                'available' => $this->vehicleRepo->countAvailable(),
            ],
            'reservations' => [
                'status_counts'      => $this->reservationRepo->getStatusCounts(),
                'recent'             => $recentReservations,
                'today_checkouts'    => Reservation::whereDate('start_date', today())
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->count(),
                'today_returns'      => Reservation::whereDate('end_date', today())
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->count(),
            ],
            'revenue' => $this->reservationRepo->getRevenueStats(),
        ];
    }

    public function getClientStats(int $userId): array
    {
        $reservations = Reservation::where('user_id', $userId)->get();

        return [
            'reservations' => [
                'total'   => $reservations->count(),
                'active'  => $reservations->whereIn('status', ['pending', 'confirmed'])->count(),
                'paid'    => $reservations->where('status', 'paid')->count(),
                'cancelled' => $reservations->where('status', 'cancelled')->count(),
            ],
            'total_spent' => $reservations->where('payment_status', 'paid')->sum('total_price'),
            'recent_reservations' => Reservation::with(['vehicle'])
                ->where('user_id', $userId)
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }
}
