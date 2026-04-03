<?php

namespace App\Http\Controllers;

use App\Services\BusinessDashboardService;
use App\Support\CurrentShop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(BusinessDashboardService $dashboardService): View|RedirectResponse
    {
        $user = request()->user();

        if ($user?->isSuperAdmin() && ! CurrentShop::isScopedForSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('dashboard.index', $dashboardService->data());
    }
}
