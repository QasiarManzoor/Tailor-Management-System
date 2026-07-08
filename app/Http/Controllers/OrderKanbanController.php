<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderKanbanController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['customer', 'worker'])
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderByDesc('priority')
            ->orderBy('delivery_date')
            ->get()
            ->groupBy('status');

        return view('orders.kanban', [
            'ordersByStatus' => $orders,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $order->update(['status' => $validated['status']]);

        ActivityLogger::log('order.status.updated', 'Order status updated from Kanban board.', [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'status' => $order->status,
        ]);

        return back()->with('success', 'Order status updated.');
    }
}
