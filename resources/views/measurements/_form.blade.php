<div class="slip-sheet overflow-hidden">
    <div class="slip-banner p-4 p-lg-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
            <div>
                <div class="h4 mb-2">{{ __('tailor.measurements.title') }}</div>
                <div class="text-muted">Slip-inspired bilingual measurement form for day-to-day tailoring use.</div>
                <div class="mt-3 small">
                    <span class="fw-semibold">Urdu preview:</span>
                    <span class="urdu-text ur-label ms-2" dir="rtl" lang="ur">لمبائی، چھاتی، خصوصی ہدایات</span>
                </div>
            </div>
            <div class="text-lg-end">
                <div class="metric-label">@include('partials.bilingual-text', ['key' => 'tailor.common.date'])</div>
                <div class="slip-kpi-value">{{ $measurementDate }}</div>
            </div>
        </div>
    </div>

    <div class="p-4 p-lg-5">
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="slip-section p-4 h-100">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.customer_information'])</div>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            @include('partials.bilingual-text', ['key' => 'tailor.common.customer', 'for' => 'customer_id', 'tag' => 'label', 'class' => 'form-label'])
                            <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select customer</option>
                                @foreach ($customers as $customerOption)
                                    <option value="{{ $customerOption->id }}" data-name="{{ $customerOption->name }}" data-phone="{{ $customerOption->phone }}" @selected((int) old('customer_id', $measurement->customer_id) === $customerOption->id)>{{ $customerOption->name }} - {{ $customerOption->phone }}</option>
                                @endforeach
                            </select>
                            @include('partials.field-error', ['field' => 'customer_id'])
                        </div>
                        <div class="col-md-6">
                            @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.title', 'for' => 'title', 'tag' => 'label', 'class' => 'form-label'])
                            <input type="text" id="title" name="title" value="{{ old('title', $measurement->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="{{ __('tailor.measurements.help.title') }} / {{ \Illuminate\Support\Facades\Lang::get('tailor.measurements.help.title', [], 'ur') }}" required>
                            @include('partials.field-error', ['field' => 'title'])
                        </div>
                        <div class="col-md-6">
                            <div class="slip-kpi h-100">
                                <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.common.name'])</div>
                                <div id="customer_name_preview" class="slip-kpi-value">{{ optional($customers->firstWhere('id', old('customer_id', $measurement->customer_id)))->name ?: 'Select customer' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="slip-kpi h-100">
                                <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.common.phone_number'])</div>
                                <div id="customer_phone_preview" class="slip-kpi-value">{{ optional($customers->firstWhere('id', old('customer_id', $measurement->customer_id)))->phone ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="slip-section p-4 h-100">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.slip_information'])</div>
                    <div class="slip-kpi mb-3">
                        <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.measurement_date'])</div>
                        <div class="slip-kpi-value">{{ $measurementDate }}</div>
                    </div>
                    <div class="slip-kpi mb-3">
                        <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.common.return_date'])</div>
                        <div class="text-muted">Use the linked order form if you want to track return or delivery dates.</div>
                    </div>
                    <div class="small text-muted">Database fields remain in English internally; only the measurement slip UI is bilingual for easier shop use.</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="slip-section p-4 h-100">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.upper_body'])</div>
                    <div class="row g-3">
                        @foreach (['kameez_length', 'chest', 'waist', 'hip', 'shoulder', 'sleeve', 'collar', 'arm_hole'] as $field)
                            <div class="col-sm-6">
                                @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field, 'for' => $field, 'tag' => 'label', 'class' => 'form-label'])
                                <input type="number" step="0.01" min="0" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" class="form-control @error($field) is-invalid @enderror">
                                @include('partials.field-error', ['field' => $field])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="slip-section p-4 h-100">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.lower_body'])</div>
                    <div class="row g-3">
                        @foreach (['shalwar_length', 'thigh', 'knee', 'bottom_width', 'cuff'] as $field)
                            <div class="col-sm-6">
                                @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field, 'for' => $field, 'tag' => 'label', 'class' => 'form-label'])
                                <input type="number" step="0.01" min="0" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" class="form-control @error($field) is-invalid @enderror">
                                @include('partials.field-error', ['field' => $field])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="slip-section p-4">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.style_details'])</div>
                    <div class="row g-3">
                        @foreach (['front_style', 'collar_style', 'pocket_style', 'trouser_style'] as $field)
                            <div class="col-md-6 col-xl-3">
                                @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field, 'for' => $field, 'tag' => 'label', 'class' => 'form-label'])
                                <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" class="form-control @error($field) is-invalid @enderror">
                                @include('partials.field-error', ['field' => $field])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="slip-section p-4">
                    <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.special_notes'])</div>
                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.special_notes', 'for' => 'special_notes', 'tag' => 'label', 'class' => 'form-label'])
                    <textarea id="special_notes" name="special_notes" rows="6" class="form-control slip-notes @error('special_notes') is-invalid @enderror" placeholder="{{ __('tailor.measurements.help.special_notes') }} / {{ \Illuminate\Support\Facades\Lang::get('tailor.measurements.help.special_notes', [], 'ur') }}">{{ old('special_notes', $measurement->special_notes) }}</textarea>
                    @include('partials.field-error', ['field' => 'special_notes'])
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-dark">Save Measurement</button>
            @if ($measurement->exists)
                <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-outline-secondary">View Slip</a>
            @endif
            <a href="{{ route('measurements.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const customerSelect = document.getElementById('customer_id');
    const customerNamePreview = document.getElementById('customer_name_preview');
    const customerPhonePreview = document.getElementById('customer_phone_preview');

    function syncCustomerSlipInfo() {
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        customerNamePreview.textContent = selectedOption?.dataset?.name || 'Select customer';
        customerPhonePreview.textContent = selectedOption?.dataset?.phone || '-';
    }

    customerSelect.addEventListener('change', syncCustomerSlipInfo);
    syncCustomerSlipInfo();
</script>
@endpush
