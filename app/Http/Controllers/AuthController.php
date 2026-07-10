<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 300;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request);

        try {
            if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
                $seconds = RateLimiter::availableIn($throttleKey);

                throw ValidationException::withMessages([
                    'email' => 'Too many login attempts. Please try again in '.ceil($seconds / 60).' minute(s).',
                ]);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            // Allow login to continue when production cache/rate-limiter storage is unavailable.
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            try {
                RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);
            } catch (Throwable $exception) {
                // Allow failed credential responses even if rate-limit storage is unavailable.
            }

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        try {
            $request->session()->regenerate();
        } catch (Throwable $exception) {
            // Session regeneration can fail if the runtime session backend is misconfigured.
        }

        try {
            RateLimiter::clear($throttleKey);
        } catch (Throwable $exception) {
            // Ignore rate-limiter cleanup errors in constrained deployments.
        }

        $user = $request->user();

        $hasIsActiveColumn = Schema::hasTable('users') && Schema::hasColumn('users', 'is_active');

        if ($hasIsActiveColumn && (! $user || ! $user->is_active)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please contact the super admin.',
            ]);
        }

        ActivityLogger::log('user.login', 'User signed in successfully.', [
            'email' => $user->email,
            'role' => $user->role,
        ], $user);

        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('superadmin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('email')->toString()).'|'.$request->ip());
    }
}

