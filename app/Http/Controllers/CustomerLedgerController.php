<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;

class CustomerLedgerController extends Controller
{
    public function show(Customer $customer): View
    {
        $customer->load(['orders.payments']);

        $orders = $customer->orders->sortByDesc('booking_date')->values();
        $payments = $orders
            ->flatMap(fn ($order) => $order->payments->map(fn ($payment) => [
                'payment' => $payment,
                'order' => $order,
            ]))
            ->sortByDesc(fn ($row) => $row['payment']->payment_date)
            ->values();

        return view('customers.ledger', [
            'customer' => $customer,
            'orders' => $orders,
            'payments' => $payments,
            'summary' => [
                'ordersCount' => $orders->count(),
                'totalAmount' => (float) $orders->sum('total_amount'),
                'paidAmount' => (float) $orders->sum('advance_amount'),
                'balanceAmount' => (float) $orders->sum('balance_amount'),
            ],
        ]);
    }
}
