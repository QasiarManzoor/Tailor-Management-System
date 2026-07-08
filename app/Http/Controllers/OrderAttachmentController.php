<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderAttachmentController extends Controller
{
    public function store(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(OrderAttachment::TYPES)],
            'photo' => ['required', 'image', 'max:4096'],
        ]);

        $file = $request->file('photo');
        $path = $file->store('order-attachments/'.$order->id, 'public');

        $order->attachments()->create([
            'uploaded_by' => $request->user()?->id,
            'type' => $validated['type'],
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'Order photo attached.');
    }

    public function destroy(Order $order, OrderAttachment $attachment): RedirectResponse
    {
        abort_unless($attachment->order_id === $order->id, 404);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Order photo removed.');
    }
}
