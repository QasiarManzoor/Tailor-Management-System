<div class="row g-3">
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
        <label for="role" class="form-label">Role</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            @foreach (['owner' => 'Owner', 'super_admin' => 'Super Admin'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">User account is active</label>
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
