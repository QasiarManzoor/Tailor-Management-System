<div class="row g-4">
    @if (auth()->user()?->isSuperAdmin())
        <div class="col-md-4">
            <label class="form-label" for="shop_id">Shop</label>
            <select id="shop_id" name="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
                <option value="">Select shop</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}" @selected((int) old('shop_id', $customer->shop_id) === $shop->id)>{{ $shop->name }}</option>
                @endforeach
            </select>
            @include('partials.field-error', ['field' => 'shop_id'])
        </div>
    @endif
    <div class="col-md-4">
        <label class="form-label">Customer Number</label>
        <input type="text" class="form-control" value="{{ $customer->customer_no ?: 'Auto-generated on save' }}" disabled>
    </div>
    <div class="{{ auth()->user()?->isSuperAdmin() ? 'col-md-4' : 'col-md-8' }}">
        <label class="form-label" for="name">Customer Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" class="form-control @error('name') is-invalid @enderror" maxlength="255" required>
        @include('partials.field-error', ['field' => 'name'])
    </div>
    <div class="col-md-3">
        <label class="form-label" for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-control @error('phone') is-invalid @enderror" inputmode="tel" pattern="\+?[0-9]{7,20}" maxlength="20" data-phone-input required>
        @include('partials.field-error', ['field' => 'phone'])
    </div>
    <div class="col-md-3">
        <label class="form-label" for="alternate_phone">Alternate Phone</label>
        <input type="tel" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $customer->alternate_phone) }}" class="form-control @error('alternate_phone') is-invalid @enderror" inputmode="tel" pattern="\+?[0-9]{7,20}" maxlength="20" data-phone-input>
        @include('partials.field-error', ['field' => 'alternate_phone'])
    </div>
    <div class="col-md-2">
        <label class="form-label" for="gender">Gender</label>
        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
            <option value="">Select</option>
            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}" @selected(old('gender', $customer->gender) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @include('partials.field-error', ['field' => 'gender'])
    </div>
    <div class="col-md-4">
        <label class="form-label" for="address">Address</label>
        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $customer->address) }}</textarea>
        @include('partials.field-error', ['field' => 'address'])
    </div>
    <div class="col-12">
        <label class="form-label" for="notes">Notes</label>
        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Any fitting preference, repeat style, or family notes">{{ old('notes', $customer->notes) }}</textarea>
        @include('partials.field-error', ['field' => 'notes'])
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button class="btn btn-dark">Save Customer</button>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
