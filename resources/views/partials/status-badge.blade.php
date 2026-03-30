@props(['order'])
<span class="badge rounded-pill {{ $order->statusBadgeClass() }} text-capitalize px-3 py-2">{{ str_replace('_', ' ', $order->status) }}</span>
