<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\Payment;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'order_no' => trim((string) $request->string('order_no')),
            'customer_id' => $request->integer('customer_id') ?: null,
            'status' => $request->string('status')->toString(),
            'delivery_date' => $request->string('delivery_date')->toString(),
        ];

        $orders = Order::with(['customer', 'measurement'])
            ->when($filters['order_no'] !== '', function ($query) use ($filters) {
                $search = $filters['order_no'];

                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('order_no', 'like', '%'.$search.'%')
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('customer_no', 'like', '%'.$search.'%')
                                ->orWhere('name', 'like', '%'.$search.'%')
                                ->orWhere('phone', 'like', '%'.$search.'%')
                                ->orWhere('alternate_phone', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($filters['customer_id'], fn ($query) => $query->where('customer_id', $filters['customer_id']))
            ->when($filters['status'] !== '', fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['delivery_date'] !== '', fn ($query) => $query->whereDate('delivery_date', $filters['delivery_date']))
            ->orderByRaw("case when delivery_date < curdate() and status not in ('delivered', 'cancelled') then 0 else 1 end")
            ->orderBy('delivery_date')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
            'customers' => Customer::orderBy('name')->get(),
            'statuses' => Order::STATUSES,
            'filters' => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        $customers = Customer::with('measurements')->orderBy('name')->get();

        return view('orders.create', [
            'order' => new Order([
                'customer_id' => $request->integer('customer_id') ?: null,
                'booking_date' => now()->toDateString(),
                'delivery_date' => now()->addDays(7)->toDateString(),
                'status' => 'booked',
                'priority' => 'normal',
                'quantity' => 1,
                'advance_amount' => 0,
            ]),
            'customers' => $customers,
            'measurementMap' => $this->measurementMap($customers),
            'statuses' => Order::STATUSES,
            'priorities' => Order::PRIORITIES,
        ]);
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());

        ActivityLogger::log('order.created', 'Order booked successfully.', [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'customer_id' => $order->customer_id,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order booked successfully.');
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'measurement', 'payments']);

        return view('orders.show', [
            'order' => $order,
            'paymentMethods' => Payment::METHODS,
        ]);
    }

    public function receipt(Order $order): View
    {
        $order->load(['customer', 'measurement', 'payments']);

        ActivityLogger::log('slip.printed', 'Printable receipt opened.', [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'type' => 'receipt',
        ]);

        return view('orders.receipt', [
            'order' => $order,
        ]);
    }

    public function invoice(Order $order): View
    {
        $order->load(['customer', 'measurement', 'payments']);

        ActivityLogger::log('slip.printed', 'Printable invoice opened.', [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'type' => 'invoice',
        ]);

        return view('orders.invoice', [
            'order' => $order,
        ]);
    }

    public function edit(Order $order): View
    {
        $customers = Customer::with('measurements')->orderBy('name')->get();

        return view('orders.edit', [
            'order' => $order,
            'customers' => $customers,
            'measurementMap' => $this->measurementMap($customers),
            'statuses' => Order::STATUSES,
            'priorities' => Order::PRIORITIES,
        ]);
    }

    public function update(OrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        ActivityLogger::log('order.updated', 'Order updated successfully.', [
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'status' => $order->status,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    protected function measurementMap(Collection $customers): array
    {
        return $customers
            ->mapWithKeys(fn (Customer $customer) => [
                $customer->id => $customer->measurements->map(fn (Measurement $measurement) => [
                    'id' => $measurement->id,
                    'title' => $measurement->title,
                    'summary' => trim(collect([
                        $measurement->chest ? 'Chest '.$measurement->chest : null,
                        $measurement->waist ? 'Waist '.$measurement->waist : null,
                        $measurement->shalwar_length ? 'Shalwar '.$measurement->shalwar_length : null,
                    ])->filter()->join(' | ')),
                ])->values()->all(),
            ])
            ->all();
    }
}
