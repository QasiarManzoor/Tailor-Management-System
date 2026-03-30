<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeasurementRequest;
use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $measurements = Measurement::with('customer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('title', 'like', '%'.$search.'%')
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', '%'.$search.'%')
                                ->orWhere('phone', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('measurements.index', [
            'measurements' => $measurements,
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        return view('measurements.create', [
            'measurement' => new Measurement([
                'customer_id' => $request->integer('customer_id') ?: null,
            ]),
            'customers' => Customer::orderBy('name')->get(),
            'measurementDate' => now()->toDateString(),
        ]);
    }

    public function store(MeasurementRequest $request): RedirectResponse
    {
        $measurement = Measurement::create($request->validated());

        return redirect()
            ->route('measurements.show', $measurement)
            ->with('success', 'Measurement saved successfully.');
    }

    public function show(Measurement $measurement): View
    {
        $measurement->load('customer');

        return view('measurements.show', [
            'measurement' => $measurement,
        ]);
    }

    public function print(Measurement $measurement): View
    {
        $measurement->load('customer');

        return view('measurements.print', [
            'measurement' => $measurement,
        ]);
    }

    public function edit(Measurement $measurement): View
    {
        return view('measurements.edit', [
            'measurement' => $measurement,
            'customers' => Customer::orderBy('name')->get(),
            'measurementDate' => optional($measurement->updated_at)->format('Y-m-d') ?: now()->toDateString(),
        ]);
    }

    public function update(MeasurementRequest $request, Measurement $measurement): RedirectResponse
    {
        $measurement->update($request->validated());

        return redirect()
            ->route('measurements.show', $measurement)
            ->with('success', 'Measurement updated successfully.');
    }

    public function destroy(Measurement $measurement): RedirectResponse
    {
        $customerId = $measurement->customer_id;
        $measurement->delete();

        return redirect()
            ->route('customers.show', $customerId)
            ->with('success', 'Measurement deleted successfully.');
    }
}
