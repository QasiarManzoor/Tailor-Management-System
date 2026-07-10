<?php

namespace App\Http\Controllers;

use App\Models\CashbookEntry;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$startDate, $endDate] = $this->dateRange($request);

        $orders = Order::with('customer')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->get();

        $payments = Payment::with('order.customer')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->latest('payment_date')
            ->latest()
            ->get();

        $entries = CashbookEntry::whereBetween('entry_date', [$startDate, $endDate])->get();

        $paymentIncome = (float) $payments->sum('amount');
        $manualIncome = (float) $entries->where('type', 'income')->sum('amount');
        $expenses = (float) $entries->where('type', 'expense')->sum('amount');
        $grossIncome = $paymentIncome + $manualIncome;

        $topCustomers = $orders
            ->groupBy('customer_id')
            ->map(function ($customerOrders) {
                $firstOrder = $customerOrders->first();

                return [
                    'customer' => $firstOrder?->customer,
                    'orders_count' => $customerOrders->count(),
                    'total_amount' => (float) $customerOrders->sum('total_amount'),
                    'balance_amount' => (float) $customerOrders->sum('balance_amount'),
                ];
            })
            ->sortByDesc('total_amount')
            ->take(5)
            ->values();

        return view('reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $request->string('period', 'month')->toString(),
            'summary' => [
                'paymentIncome' => $paymentIncome,
                'manualIncome' => $manualIncome,
                'grossIncome' => $grossIncome,
                'expenses' => $expenses,
                'netIncome' => $grossIncome - $expenses,
                'ordersCount' => $orders->count(),
                'ordersValue' => (float) $orders->sum('total_amount'),
                'ordersAdvance' => (float) $orders->sum('advance_amount'),
                'ordersBalance' => (float) $orders->sum('balance_amount'),
                'currentPendingBalance' => (float) Order::where('balance_amount', '>', 0)->sum('balance_amount'),
                'overdueOrders' => Order::whereDate('delivery_date', '<', today())
                    ->whereNotIn('status', ['delivered', 'cancelled'])
                    ->count(),
            ],
            'statusBreakdown' => collect(Order::STATUSES)
                ->mapWithKeys(fn ($status) => [$status => $orders->where('status', $status)->count()]),
            'paymentMethods' => collect(Payment::METHODS)
                ->mapWithKeys(fn ($method) => [$method => (float) $payments->where('payment_method', $method)->sum('amount')]),
            'expenseCategories' => collect(CashbookEntry::EXPENSE_CATEGORIES)
                ->mapWithKeys(fn ($category) => [$category => (float) $entries->where('type', 'expense')->where('category', $category)->sum('amount')]),
            'topCustomers' => $topCustomers,
            'recentPayments' => $payments->take(8),
        ]);
    }

    protected function dateRange(Request $request): array
    {
        $period = $request->string('period', 'month')->toString();

        [$startDate, $endDate] = match ($period) {
            'today' => [today(), today()],
            'week' => [today()->startOfWeek(), today()->endOfWeek()],
            'custom' => [
                $request->date('start_date') ?: today()->startOfMonth(),
                $request->date('end_date') ?: today()->endOfMonth(),
            ],
            default => [today()->startOfMonth(), today()->endOfMonth()],
        };

        return $startDate->greaterThan($endDate)
            ? [$endDate, $startDate]
            : [$startDate, $endDate];
    }
}
