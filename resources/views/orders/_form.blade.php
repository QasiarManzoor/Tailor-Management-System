<div class="row g-4">
    <div class="col-12">
        <div class="form-panel">
            <div class="form-panel-header">
                <h2 class="form-panel-title">Customer And Order Context</h2>
                <p class="form-panel-copy">Choose the customer, attach a saved measurement if available, and set the basic job details.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    @include('partials.bilingual-text', ['key' => 'tailor.common.customer', 'for' => 'customer_id', 'tag' => 'label', 'class' => 'form-label'])
                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select customer</option>
                        @foreach ($customers as $customerOption)
                            <option value="{{ $customerOption->id }}" @selected((int) old('customer_id', $order->customer_id) === $customerOption->id)>{{ $customerOption->customer_no ?: 'No #' }} · {{ $customerOption->name }} · {{ $customerOption->phone }}</option>
                        @endforeach
                    </select>
                    @include('partials.field-error', ['field' => 'customer_id'])
                </div>
                <div class="col-md-4">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.saved_measurement', 'for' => 'measurement_id', 'tag' => 'label', 'class' => 'form-label'])
                    <select name="measurement_id" id="measurement_id" class="form-select @error('measurement_id') is-invalid @enderror">
                        <option value="">No saved measurement</option>
                    </select>
                    <div class="form-text">Pick an existing slip so the order connects to saved customer measurements.</div>
                    @include('partials.field-error', ['field' => 'measurement_id'])
                </div>
                <div class="col-md-4">
                    <label class="form-label">Order Number</label>
                    <input type="text" class="form-control" value="{{ $order->order_no ?: 'Auto-generated on save' }}" disabled>
                </div>
                <input type="hidden" id="order_type" name="order_type" value="{{ old('order_type', $order->order_type ?: 'Tailoring Order') }}">
                <div class="col-md-4">
                    <label class="form-label" for="quantity">Quantity</label>
                    <input type="number" min="1" step="1" inputmode="numeric" data-integer-input id="quantity" name="quantity" value="{{ old('quantity', $order->quantity ?: 1) }}" class="form-control @error('quantity') is-invalid @enderror" required>
                    @include('partials.field-error', ['field' => 'quantity'])
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    @include('partials.field-error', ['field' => 'status'])
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $order->priority) === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                    @include('partials.field-error', ['field' => 'priority'])
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-panel h-100">
            <div class="form-panel-header">
                <h2 class="form-panel-title">Payment Snapshot</h2>
                <p class="form-panel-copy">Capture the booking value and the amount received at the counter.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.total_amount', 'for' => 'total_amount', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="number" step="1" min="0" inputmode="numeric" data-integer-input id="total_amount" name="total_amount" value="{{ old('total_amount', $order->total_amount) }}" class="form-control @error('total_amount') is-invalid @enderror" required>
                    @include('partials.field-error', ['field' => 'total_amount'])
                </div>
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.advance_amount', 'for' => 'advance_amount', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="number" step="1" min="0" inputmode="numeric" data-integer-input id="advance_amount" name="advance_amount" value="{{ old('advance_amount', $order->advance_amount) }}" class="form-control @error('advance_amount') is-invalid @enderror" required>
                    <div class="form-text">Extra receipts can be added later from the order details page.</div>
                    @include('partials.field-error', ['field' => 'advance_amount'])
                </div>
                <div class="col-12">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.balance', 'for' => 'balance_preview', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="text" id="balance_preview" class="form-control" value="Rs. 0" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-panel h-100">
            <div class="form-panel-header">
                <h2 class="form-panel-title">Timeline</h2>
                <p class="form-panel-copy">Keep the work path clear from booking to delivery.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.booking_date', 'for' => 'booking_date', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="date" id="booking_date" name="booking_date" min="{{ now()->toDateString() }}" value="{{ old('booking_date', optional($order->booking_date)->format('Y-m-d') ?: $order->booking_date) }}" class="form-control @error('booking_date') is-invalid @enderror" required>
                    @include('partials.field-error', ['field' => 'booking_date'])
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="trial_date">Trial Date</label>
                    <input type="date" id="trial_date" name="trial_date" value="{{ old('trial_date', optional($order->trial_date)->format('Y-m-d') ?: $order->trial_date) }}" class="form-control @error('trial_date') is-invalid @enderror">
                    @include('partials.field-error', ['field' => 'trial_date'])
                </div>
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.orders.delivery_date', 'for' => 'delivery_date', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', optional($order->delivery_date)->format('Y-m-d') ?: $order->delivery_date) }}" class="form-control @error('delivery_date') is-invalid @enderror" required>
                    @include('partials.field-error', ['field' => 'delivery_date'])
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="delivered_date">Delivered Date</label>
                    <input type="date" id="delivered_date" name="delivered_date" value="{{ old('delivered_date', optional($order->delivered_date)->format('Y-m-d') ?: $order->delivered_date) }}" class="form-control @error('delivered_date') is-invalid @enderror">
                    @include('partials.field-error', ['field' => 'delivered_date'])
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-panel">
            <div class="form-panel-header">
                <h2 class="form-panel-title">Fabric And Notes</h2>
                <p class="form-panel-copy">Save the workshop context, cloth details, and any special stitching instructions.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-5">
                    <label class="form-label" for="fabric_details">Fabric Details</label>
                    <textarea id="fabric_details" name="fabric_details" rows="5" class="form-control @error('fabric_details') is-invalid @enderror" placeholder="Fabric type, color, supplied by customer or shop, lining notes">{{ old('fabric_details', $order->fabric_details) }}</textarea>
                    @include('partials.field-error', ['field' => 'fabric_details'])
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="special_instructions">Special Instructions</label>
                    <textarea id="special_instructions" name="special_instructions" rows="5" class="form-control @error('special_instructions') is-invalid @enderror" placeholder="Neck design, cuff notes, urgency, delivery reminders">{{ old('special_instructions', $order->special_instructions) }}</textarea>
                    @include('partials.field-error', ['field' => 'special_instructions'])
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button class="btn btn-dark">Save Order</button>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

@push('scripts')
<script>
    const measurementMap = @json($measurementMap);
    const selectedMeasurement = '{{ old('measurement_id', $order->measurement_id) }}';
    const customerField = document.getElementById('customer_id');
    const measurementField = document.getElementById('measurement_id');
    const totalField = document.getElementById('total_amount');
    const advanceField = document.getElementById('advance_amount');
    const balanceField = document.getElementById('balance_preview');
    const bookingDateField = document.getElementById('booking_date');
    const trialDateField = document.getElementById('trial_date');
    const deliveryDateField = document.getElementById('delivery_date');
    const deliveredDateField = document.getElementById('delivered_date');
    const today = '{{ now()->toDateString() }}';

    function syncMeasurements() {
        const customerId = customerField.value;
        const options = measurementMap[customerId] || [];
        measurementField.innerHTML = '<option value="">No saved measurement</option>';

        options.forEach((measurement) => {
            const option = document.createElement('option');
            option.value = measurement.id;
            option.textContent = measurement.summary ? `${measurement.title} (${measurement.summary})` : measurement.title;
            if (String(measurement.id) === String(selectedMeasurement) || String(measurement.id) === String(measurementField.dataset.selected)) {
                option.selected = true;
            }
            measurementField.appendChild(option);
        });
    }

    function syncBalance() {
        const total = parseInt(totalField.value || 0, 10);
        const advance = parseInt(advanceField.value || 0, 10);
        const balance = Math.max(0, total - advance);
        balanceField.value = `Rs. ${balance}`;
    }

    function syncDateMinimums() {
        const baseDate = bookingDateField.value || today;
        bookingDateField.min = today;
        trialDateField.min = baseDate;
        deliveryDateField.min = baseDate;
        deliveredDateField.min = baseDate;
    }

    measurementField.dataset.selected = selectedMeasurement;
    customerField.addEventListener('change', function () {
        measurementField.dataset.selected = '';
        syncMeasurements();
    });
    totalField.addEventListener('input', syncBalance);
    advanceField.addEventListener('input', syncBalance);
    bookingDateField.addEventListener('change', syncDateMinimums);
    syncMeasurements();
    syncBalance();
    syncDateMinimums();
</script>
@endpush
