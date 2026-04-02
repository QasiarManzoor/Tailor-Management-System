@props(['order'])

@php
    $statusStyles = [
        'booked' => ['class' => 'status-pill', 'label' => 'Booked'],
        'cutting' => ['class' => 'status-pill', 'label' => 'Cutting'],
        'stitching' => ['class' => 'status-pill', 'label' => 'Stitching'],
        'trial' => ['class' => 'status-pill', 'label' => 'Trial'],
        'ready' => ['class' => 'status-pill', 'label' => 'Ready'],
        'delivered' => ['class' => 'status-pill', 'label' => 'Delivered'],
        'cancelled' => ['class' => 'status-pill', 'label' => 'Cancelled'],
    ];
    $config = $statusStyles[$order->status] ?? ['class' => 'status-pill', 'label' => ucfirst(str_replace('_', ' ', $order->status))];
@endphp

<span class="{{ $config['class'] }} {{ $order->statusBadgeClass() }}">
    <span class="pill-dot"></span>
    {{ $config['label'] }}
</span>
