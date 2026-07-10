<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">Workshop Details</div>
        <div class="row g-3">
            <div class="col-md-6">
                <span class="metric-label d-block mb-1">Work Category</span>
                {{ ucwords(str_replace('_', ' ', $order->work_category ?: 'new_stitch')) }}
            </div>
            <div class="col-md-6">
                <span class="metric-label d-block mb-1">Assigned Worker</span>
                {{ $order->worker?->name ?: 'Unassigned' }}
            </div>
            <div class="col-md-6">
                <span class="metric-label d-block mb-1">Trial Status</span>
                {{ ucwords(str_replace('_', ' ', $order->trial_status ?: 'not_required')) }}
            </div>
            <div class="col-md-6">
                <span class="metric-label d-block mb-1">Trial Date</span>
                {{ $order->trial_date?->format('d M Y') ?: 'Not set' }}
            </div>
            @if ($order->alteration_notes)
                <div class="col-12">
                    <span class="metric-label d-block mb-1">Alteration / Repair Notes</span>
                    {{ $order->alteration_notes }}
                </div>
            @endif
        </div>
    </div>
</div>
