<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        return view('inventory.index', [
            'search' => $search,
            'items' => InventoryItem::with('movements')
                ->when($search !== '', fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%')))
                ->orderByDesc('is_active')
                ->orderBy('name')
                ->get(),
            'categories' => InventoryItem::CATEGORIES,
            'units' => InventoryItem::UNITS,
            'movementTypes' => InventoryMovement::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(InventoryItem::CATEGORIES)],
            'unit' => ['required', Rule::in(InventoryItem::UNITS)],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'cost_price' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => false];

        InventoryItem::create($validated);

        return back()->with('success', 'Inventory item saved.');
    }

    public function movement(Request $request, InventoryItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(InventoryMovement::TYPES)],
            'quantity' => ['required', 'integer', 'min:1'],
            'movement_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($item, $validated) {
            $item->movements()->create($validated);

            $quantity = (int) $validated['quantity'];
            $nextStock = match ($validated['type']) {
                'in' => $item->stock_quantity + $quantity,
                'out' => max(0, $item->stock_quantity - $quantity),
                default => $quantity,
            };

            $item->update(['stock_quantity' => $nextStock]);
        });

        return back()->with('success', 'Inventory movement recorded.');
    }
}
