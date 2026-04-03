<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(Order $order): View
    {
        $order->load(['customer', 'payments']);

        return view('payments.create', [
            'order' => $order,
            'paymentMethods' => Payment::METHODS,
        ]);
    }

    public function store(PaymentRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        if ((float) $validated['amount'] > (float) $order->balance_amount) {
            return back()
                ->withInput()
                ->withErrors(['amount' => 'Payment amount cannot be greater than the remaining balance.']);
        }

        $payment = DB::transaction(function () use ($order, $validated) {
            $payment = $order->payments()->create([
                ...$validated,
                'shop_id' => $order->shop_id,
            ]);

            $updatedAdvance = (int) $order->advance_amount + (int) $validated['amount'];

            $order->update([
                'advance_amount' => $updatedAdvance,
                'balance_amount' => Order::calculateBalance($order->total_amount, $updatedAdvance),
            ]);

            return $payment;
        });

        ActivityLogger::log('payment.created', 'Payment recorded against an order.', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'amount' => $payment->amount,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Payment added successfully.');
    }
}
