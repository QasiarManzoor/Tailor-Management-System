<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserSafetyService;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('role', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('superadmin.users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('superadmin.users.create', [
            'user' => new User(['role' => 'owner', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['super_admin', 'owner'])],
            'password' => ['required', 'confirmed', Password::min(8)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogger::log('user.created', 'Super admin created a user account.', [
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'target_role' => $user->role,
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user, UserSafetyService $userSafetyService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'owner'])],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (! $userSafetyService->canChangeRole($user, $validated['role'])) {
            return back()->withInput()->withErrors(['role' => 'At least one super admin must remain in the system.']);
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        ActivityLogger::log('user.updated', 'Super admin updated a user account.', [
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'target_role' => $user->role,
            'password_changed' => ! empty($validated['password']),
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user, UserSafetyService $userSafetyService): RedirectResponse
    {
        if (! $userSafetyService->canDeactivate($user, auth()->id())) {
            $message = $user->id === auth()->id() && $user->is_active
                ? 'You cannot deactivate your own active session account.'
                : 'The last active super admin cannot be deactivated.';

            return back()->withErrors(['status' => $message]);
        }

        $user->update(['is_active' => ! $user->is_active]);

        ActivityLogger::log('user.status.updated', 'Super admin changed a user status.', [
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'is_active' => $user->is_active,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function destroy(User $user, UserSafetyService $userSafetyService): RedirectResponse
    {
        if (! $userSafetyService->canDelete($user, auth()->id())) {
            $message = $user->id === auth()->id()
                ? 'You cannot delete your own account while signed in.'
                : 'The last super admin cannot be deleted.';

            return back()->withErrors(['delete' => $message]);
        }

        ActivityLogger::log('user.deleted', 'Super admin deleted a user account.', [
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'target_role' => $user->role,
        ]);

        $user->delete();

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
