<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DeliveryCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $monthInput = $request->string('month')->toString();
        $month = preg_match('/^\d{4}-\d{2}$/', $monthInput)
            ? Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth()
            : today()->startOfMonth();

        $start = $month->copy()->startOfWeek();
        $end = $month->copy()->endOfMonth()->endOfWeek();

        $orders = Order::with('customer')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('delivery_date', [$start, $end])
                    ->orWhereBetween('trial_date', [$start, $end]);
            })
            ->orderBy('delivery_date')
            ->get();

        return view('calendar.index', [
            'month' => $month,
            'previousMonth' => $month->copy()->subMonth(),
            'nextMonth' => $month->copy()->addMonth(),
            'weeks' => collect($start->daysUntil($end))->chunk(7),
            'ordersByDeliveryDate' => $orders->whereNotNull('delivery_date')->groupBy(fn ($order) => $order->delivery_date->format('Y-m-d')),
            'ordersByTrialDate' => $orders->whereNotNull('trial_date')->groupBy(fn ($order) => $order->trial_date->format('Y-m-d')),
        ]);
    }
}
