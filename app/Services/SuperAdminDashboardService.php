<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class SuperAdminDashboardService
{
    public function data(): array
    {
        return [
            'stats' => [
                'totalUsers' => User::count(),
                'totalOwners' => User::where('role', 'owner')->count(),
                'totalCustomers' => Customer::count(),
                'totalOrders' => Order::count(),
                'deliveredOrders' => Order::where('status', 'delivered')->count(),
                'pendingOrders' => Order::whereNotIn('status', ['delivered', 'cancelled'])->count(),
                'pendingBalances' => Order::where('balance_amount', '>', 0)->sum('balance_amount'),
                'paymentsReceived' => Payment::sum('amount'),
            ],
            'latestUsers' => User::latest()->take(6)->get(),
            'latestOrders' => Order::with('customer')->latest()->take(6)->get(),
            'latestActivityLogs' => ActivityLog::with('user')->latest()->take(10)->get(),
        ];
    }
}
