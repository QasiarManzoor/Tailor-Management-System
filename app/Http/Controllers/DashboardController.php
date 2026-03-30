<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = today();
        $pendingBalanceOrders = Order::with('customer')
            ->where('balance_amount', '>', 0)
            ->orderByDesc('balance_amount')
            ->take(5)
            ->get();

        $stats = [
            'totalCustomers' => Customer::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'readyOrders' => Order::where('status', 'ready')->count(),
            'deliveriesToday' => Order::whereDate('delivery_date', $today)->count(),
            'overdueOrders' => Order::whereDate('delivery_date', '<', $today)
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->count(),
            'pendingPayments' => Order::where('balance_amount', '>', 0)->count(),
            'pendingBalanceAmount' => Order::where('balance_amount', '>', 0)->sum('balance_amount'),
        ];

        return view('dashboard.index', [
            'stats' => $stats,
            'latestOrders' => Order::with('customer')->latest()->take(5)->get(),
            'todayDeliveries' => Order::with('customer')
                ->whereDate('delivery_date', $today)
                ->orderByDesc('priority')
                ->orderBy('delivery_date')
                ->get(),
            'pendingBalanceOrders' => $pendingBalanceOrders,
        ]);
    }
}
