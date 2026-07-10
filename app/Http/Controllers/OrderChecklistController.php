<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderChecklistController extends Controller
{
    public function update(Request $request, Order $order): RedirectResponse
    {
        $doneItems = collect($request->input('items', []))->map(fn ($id) => (int) $id)->all();

        $order->checklistItems()->get()->each(function ($item) use ($doneItems) {
            $isDone = in_array($item->id, $doneItems, true);

            $item->update([
                'is_done' => $isDone,
                'completed_at' => $isDone ? ($item->completed_at ?: now()) : null,
            ]);
        });

        return back()->with('success', 'Production checklist updated.');
    }
}
