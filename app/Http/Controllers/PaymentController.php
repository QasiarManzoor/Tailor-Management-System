<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

        $order->payments()->create($validated);

        $order->advance_amount = (float) $order->advance_amount + (float) $validated['amount'];
        $order->refreshBalance();

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Payment added successfully.');
    }
}
