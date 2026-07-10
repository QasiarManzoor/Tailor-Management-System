<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use App\Support\FastSearch;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->string('q'));

        $customers = collect();
        $orders = collect();
        $measurements = collect();

        if ($query !== '') {
            $customers = Customer::query()
                ->withCount(['measurements', 'orders'])
                ->where(fn ($customerQuery) => FastSearch::customers($customerQuery, $query))
                ->orderBy('name')
                ->take(8)
                ->get();

            $orders = Order::query()
                ->with('customer')
                ->where(fn ($orderQuery) => FastSearch::orders($orderQuery, $query))
                ->latest()
                ->take(8)
                ->get();

            $measurements = Measurement::query()
                ->with('customer')
                ->where(fn ($measurementQuery) => FastSearch::measurements($measurementQuery, $query))
                ->latest()
                ->take(8)
                ->get();
        }

        return view('search.index', compact('query', 'customers', 'orders', 'measurements'));
    }
}
