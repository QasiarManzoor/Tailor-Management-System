<div class="row g-3">
    <div class="col-12">
        <div class="form-panel">
            <div class="form-panel-header">
                <h2 class="form-panel-title">Shop Assignment</h2>
                <p class="form-panel-copy">Assign the account to an existing shop or create a new shop inline. New shops must include the full receipt header details.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="shop_id" class="form-label">Existing Shop</label>
                    <select class="form-select @error('shop_id') is-invalid @enderror" id="shop_id" name="shop_id">
                        <option value="">Create a new shop</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}" @selected((int) old('shop_id', $user->shop_id) === $shop->id)>{{ $shop->name }}</option>
                        @endforeach
                    </select>
                    @error('shop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_name" class="form-label">New Shop Name</label>
                    <input type="text" class="form-control @error('new_shop_name') is-invalid @enderror" id="new_shop_name" name="new_shop_name" value="{{ old('new_shop_name') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_tagline" class="form-label">Shop Tagline</label>
                    <input type="text" class="form-control @error('new_shop_tagline') is-invalid @enderror" id="new_shop_tagline" name="new_shop_tagline" value="{{ old('new_shop_tagline') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_logo_path" class="form-label">Logo Path</label>
                    <input type="text" class="form-control @error('new_shop_logo_path') is-invalid @enderror" id="new_shop_logo_path" name="new_shop_logo_path" value="{{ old('new_shop_logo_path') }}" placeholder="Example: images/shaq-logo.png">
                    @error('new_shop_logo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_phone_primary" class="form-label">Shop Primary Phone</label>
                    <input type="text" class="form-control @error('new_shop_phone_primary') is-invalid @enderror" id="new_shop_phone_primary" name="new_shop_phone_primary" value="{{ old('new_shop_phone_primary') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_phone_primary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_phone_secondary" class="form-label">Shop Secondary Phone</label>
                    <input type="text" class="form-control @error('new_shop_phone_secondary') is-invalid @enderror" id="new_shop_phone_secondary" name="new_shop_phone_secondary" value="{{ old('new_shop_phone_secondary') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_phone_secondary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_address_line_1" class="form-label">Shop Address Line 1</label>
                    <input type="text" class="form-control @error('new_shop_address_line_1') is-invalid @enderror" id="new_shop_address_line_1" name="new_shop_address_line_1" value="{{ old('new_shop_address_line_1') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_address_line_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="new_shop_address_line_2" class="form-label">Shop Address Line 2</label>
                    <input type="text" class="form-control @error('new_shop_address_line_2') is-invalid @enderror" id="new_shop_address_line_2" name="new_shop_address_line_2" value="{{ old('new_shop_address_line_2') }}" placeholder="Required when creating a new shop">
                    @error('new_shop_address_line_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="new_shop_receipt_footer_company_name" class="form-label">Receipt Footer Company</label>
                    <input type="text" class="form-control @error('new_shop_receipt_footer_company_name') is-invalid @enderror" id="new_shop_receipt_footer_company_name" name="new_shop_receipt_footer_company_name" value="{{ old('new_shop_receipt_footer_company_name') }}">
                    @error('new_shop_receipt_footer_company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="new_shop_receipt_footer_phone" class="form-label">Receipt Footer Phone</label>
                    <input type="text" class="form-control @error('new_shop_receipt_footer_phone') is-invalid @enderror" id="new_shop_receipt_footer_phone" name="new_shop_receipt_footer_phone" value="{{ old('new_shop_receipt_footer_phone') }}">
                    @error('new_shop_receipt_footer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="new_shop_receipt_footer_email" class="form-label">Receipt Footer Email</label>
                    <input type="email" class="form-control @error('new_shop_receipt_footer_email') is-invalid @enderror" id="new_shop_receipt_footer_email" name="new_shop_receipt_footer_email" value="{{ old('new_shop_receipt_footer_email') }}">
                    @error('new_shop_receipt_footer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="role" class="form-label">Role</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            @foreach (\App\Models\User::ROLES as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">User account is active</label>
        </div>
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">{{ $user->exists ? 'New Password' : 'Password' }}</label>
        <div class="password-field">
            <input type="password" class="form-control password-input @error('password') is-invalid @enderror" id="password" name="password" {{ $user->exists ? '' : 'required' }}>
            <button type="button" class="password-toggle" data-password-toggle="#password" aria-label="Show password" title="Show password">&#128065;</button>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="password-field">
            <input type="password" class="form-control password-input" id="password_confirmation" name="password_confirmation" {{ $user->exists ? '' : 'required' }}>
            <button type="button" class="password-toggle" data-password-toggle="#password_confirmation" aria-label="Show password confirmation" title="Show password">&#128065;</button>
        </div>
    </div>
</div>

@once
    @push('styles')
        <style>
            .password-field {
                position: relative;
            }
            .password-field .password-input {
                padding-right: 3rem;
            }
            .password-toggle {
                position: absolute;
                top: 50%;
                right: .55rem;
                transform: translateY(-50%);
                border: 0;
                background: transparent;
                color: var(--muted-text);
                width: 2rem;
                height: 2rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
            }
            .password-toggle:hover,
            .password-toggle:focus {
                background: var(--surface-subtle);
                color: var(--text-color);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var input = document.querySelector(button.getAttribute('data-password-toggle'));

                        if (!input) {
                            return;
                        }

                        var shouldShow = input.type === 'password';
                        input.type = shouldShow ? 'text' : 'password';
                        button.innerHTML = shouldShow ? '&#128064;' : '&#128065;';
                        button.setAttribute('title', shouldShow ? 'Hide password' : 'Show password');
                        button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                    });
                });
            })();
        </script>
    @endpush
@endonce

