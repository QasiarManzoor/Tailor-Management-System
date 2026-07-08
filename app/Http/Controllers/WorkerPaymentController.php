<?php

namespace App\Http\Controllers;

use App\Models\CashbookEntry;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkerPaymentController extends Controller
{
    public function store(Request $request, Worker $worker): RedirectResponse
    {
        $orderExists = Rule::exists('orders', 'id')->where(fn ($query) => $query->where('worker_id', $worker->id));

        $validated = $request->validate([
            'order_id' => ['nullable', $orderExists],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'amount' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', Rule::in(Payment::METHODS)],
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($worker, $validated) {
            $payment = $worker->wagePayments()->create($validated);
            $order = isset($validated['order_id'])
                ? Order::query()->find($validated['order_id'])
                : null;

            CashbookEntry::create([
                'shop_id' => $payment->shop_id,
                'entry_date' => $payment->payment_date,
                'type' => 'expense',
                'category' => 'worker_payment',
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'note' => trim('Worker payment: '.$worker->name.($order ? ' / '.$order->order_no : '').($payment->note ? ' - '.$payment->note : '')),
            ]);
        });

        return back()->with('success', 'Worker payment recorded.');
    }
}
