<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private DashboardService $dashboardService) {}

    public function superAdmin(): JsonResponse
    {
        return $this->success($this->dashboardService->getSuperAdminStats(), 'Dashboard data retrieved.');
    }

    public function manager(): JsonResponse
    {
        return $this->success($this->dashboardService->getManagerStats(), 'Dashboard data retrieved.');
    }
}
