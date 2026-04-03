<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Shop;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $customers = Customer::query()
            ->withCount(['measurements', 'orders'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('customer_no', 'like', '%'.$search.'%')
                        ->orWhere('name', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%')
                        ->orWhere('alternate_phone', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create(): View
    {
        return view('customers.create', [
            'customer' => new Customer(['shop_id' => auth()->user()?->shop_id]),
            'shops' => auth()->user()?->isSuperAdmin() ? Shop::query()->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if (! auth()->user()?->isSuperAdmin()) {
            $payload['shop_id'] = auth()->user()?->shop_id;
        }

        $customer = Customer::create($payload);

        ActivityLogger::log('customer.created', 'Customer created.', [
            'customer_id' => $customer->id,
            'customer_no' => $customer->customer_no,
            'customer_name' => $customer->name,
            'shop_id' => $customer->shop_id,
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer saved successfully.');
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'measurements',
            'orders.measurement',
            'orders.payments',
        ]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer' => $customer,
            'shops' => auth()->user()?->isSuperAdmin() ? Shop::query()->orderBy('name')->get() : collect(),
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $payload = $request->validated();

        if (! auth()->user()?->isSuperAdmin()) {
            $payload['shop_id'] = $customer->shop_id;
        }

        $customer->update($payload);

        ActivityLogger::log('customer.updated', 'Customer updated.', [
            'customer_id' => $customer->id,
            'customer_no' => $customer->customer_no,
            'customer_name' => $customer->name,
            'shop_id' => $customer->shop_id,
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
