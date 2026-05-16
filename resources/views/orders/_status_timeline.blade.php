<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">Status Timeline</div>
        @forelse ($order->statusHistory as $history)
            <div class="record-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <p class="record-card-title mb-1">
                            {{ $history->from_status ? ucfirst($history->from_status).' to ' : 'Started as ' }}{{ ucfirst($history->to_status) }}
                        </p>
                        <div class="record-summary">
                            {{ $history->created_at?->format('d M Y, h:i A') }}
                            @if ($history->user)
                                - {{ $history->user->name }}
                            @endif
                        </div>
                    </div>
                    <span class="list-chip">{{ ucfirst($history->to_status) }}</span>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No status changes recorded yet.</p>
        @endforelse
    </div>
</div>
