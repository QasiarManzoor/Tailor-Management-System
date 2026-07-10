<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index(): View
    {
        $workers = Worker::with(['orders.customer', 'wagePayments'])
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->withSum('wagePayments', 'amount')
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('workers.index', [
            'workers' => $workers,
            'paymentMethods' => Payment::METHODS,
            'workerPaymentRoutes' => $workers->mapWithKeys(fn (Worker $worker) => [
                $worker->id => route('workers.payments.store', $worker),
            ]),
            'workerOrderOptions' => $workers->mapWithKeys(fn (Worker $worker) => [
                $worker->id => $worker->orders->map(fn ($order) => [
                    'id' => $order->id,
                    'label' => $order->order_no.' - '.$order->customer?->name,
                ])->values(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Worker::create($this->validated($request));

        return back()->with('success', 'Worker saved.');
    }

    public function update(Request $request, Worker $worker): RedirectResponse
    {
        $worker->update($this->validated($request));

        return back()->with('success', 'Worker updated.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => false];
    }
}
