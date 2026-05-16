@php
    $receiptUrl = $order->whatsappReceiptUrl();
    $deliveryUrl = $order->whatsappDeliveryReminderUrl();
    $paymentUrl = $order->whatsappPaymentReminderUrl();
@endphp

<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">WhatsApp Actions</div>
        <div class="d-grid gap-2">
            @if ($receiptUrl)
                <a href="{{ $receiptUrl }}" class="btn btn-outline-dark" target="_blank" rel="noopener">Send Receipt Message</a>
            @endif
            @if ($deliveryUrl)
                <a href="{{ $deliveryUrl }}" class="btn btn-outline-dark" target="_blank" rel="noopener">Send Delivery Reminder</a>
            @endif
            @if ($paymentUrl)
                <a href="{{ $paymentUrl }}" class="btn btn-outline-secondary" target="_blank" rel="noopener">Send Payment Reminder</a>
            @endif
            @unless ($receiptUrl || $deliveryUrl || $paymentUrl)
                <p class="text-muted mb-0">Add a customer phone number to enable WhatsApp messages.</p>
            @endunless
        </div>
    </div>
</div>
