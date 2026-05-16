<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
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
                ->where(function ($innerQuery) use ($query) {
                    $innerQuery->where('customer_no', 'like', '%'.$query.'%')
                        ->orWhere('name', 'like', '%'.$query.'%')
                        ->orWhere('phone', 'like', '%'.$query.'%')
                        ->orWhere('alternate_phone', 'like', '%'.$query.'%');
                })
                ->orderBy('name')
                ->take(8)
                ->get();

            $orders = Order::query()
                ->with('customer')
                ->where(function ($innerQuery) use ($query) {
                    $innerQuery->where('order_no', 'like', '%'.$query.'%')
                        ->orWhere('order_type', 'like', '%'.$query.'%')
                        ->orWhereHas('customer', function ($customerQuery) use ($query) {
                            $customerQuery->where('customer_no', 'like', '%'.$query.'%')
                                ->orWhere('name', 'like', '%'.$query.'%')
                                ->orWhere('phone', 'like', '%'.$query.'%')
                                ->orWhere('alternate_phone', 'like', '%'.$query.'%');
                        });
                })
                ->latest()
                ->take(8)
                ->get();

            $measurements = Measurement::query()
                ->with('customer')
                ->where(function ($innerQuery) use ($query) {
                    $innerQuery->where('title', 'like', '%'.$query.'%')
                        ->orWhereHas('customer', function ($customerQuery) use ($query) {
                            $customerQuery->where('customer_no', 'like', '%'.$query.'%')
                                ->orWhere('name', 'like', '%'.$query.'%')
                                ->orWhere('phone', 'like', '%'.$query.'%')
                                ->orWhere('alternate_phone', 'like', '%'.$query.'%');
                        });
                })
                ->latest()
                ->take(8)
                ->get();
        }

        return view('search.index', compact('query', 'customers', 'orders', 'measurements'));
    }
}
