<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private DashboardService $dashboardService) {}

    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getClientStats(auth()->id());
        return $this->success($stats, 'Dashboard data retrieved.');
    }
}
