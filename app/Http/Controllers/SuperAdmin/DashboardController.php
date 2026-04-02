<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdminDashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(SuperAdminDashboardService $dashboardService): View
    {
        return view('superadmin.dashboard', $dashboardService->data());
    }
}
