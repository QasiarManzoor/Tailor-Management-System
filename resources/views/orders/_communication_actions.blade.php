<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">WhatsApp Actions</div>
        <div class="d-grid gap-2">
            @forelse ($order->whatsappMessageActions() as $action)
                <a href="{{ $action['url'] }}" class="btn btn-{{ $action['variant'] }} text-start" target="_blank" rel="noopener">
                    <span class="d-block">{{ $action['label'] }}</span>
                    <span class="small text-muted">{{ $action['description'] }}</span>
                </a>
            @empty
                <p class="text-muted mb-0">Add a customer phone number to enable WhatsApp messages.</p>
            @endforelse
        </div>
    </div>
</div>
