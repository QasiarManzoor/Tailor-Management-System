<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Services\UserSafetyService;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $users = User::query()
            ->with('shop')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('role', 'like', '%'.$search.'%')
                        ->orWhereHas('shop', fn ($shopQuery) => $shopQuery->where('name', 'like', '%'.$search.'%'));
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
            'user' => new User([
                'role' => 'owner',
                'is_active' => true,
                'shop_id' => Shop::query()->value('id'),
            ]),
            'shops' => Shop::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $shopId = $this->resolveShopId($validated);

        $user = User::create([
            'shop_id' => $shopId,
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
            'shop_id' => $user->shop_id,
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('superadmin.users.edit', [
            'user' => $user,
            'shops' => Shop::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user, UserSafetyService $userSafetyService): RedirectResponse
    {
        $validated = $request->validate($this->rules($user));

        if (! $userSafetyService->canChangeRole($user, $validated['role'])) {
            return back()->withInput()->withErrors(['role' => 'At least one super admin must remain in the system.']);
        }

        $payload = [
            'shop_id' => $this->resolveShopId($validated),
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
            'shop_id' => $user->shop_id,
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
            'shop_id' => $user->shop_id,
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
            'shop_id' => $user->shop_id,
        ]);

        $user->delete();

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    protected function rules(?User $user = null): array
    {
        return [
            'shop_id' => ['nullable', 'exists:shops,id', 'required_without:new_shop_name'],
            'new_shop_name' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_tagline' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_phone_primary' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_phone_secondary' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_address_line_1' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_address_line_2' => ['nullable', 'string', 'max:255', 'required_without:shop_id'],
            'new_shop_logo_path' => ['nullable', 'string', 'max:255'],
            'new_shop_receipt_footer_company_name' => ['nullable', 'string', 'max:255'],
            'new_shop_receipt_footer_phone' => ['nullable', 'string', 'max:255'],
            'new_shop_receipt_footer_email' => ['nullable', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function resolveShopId(array $validated): int
    {
        if (! empty($validated['shop_id'])) {
            return (int) $validated['shop_id'];
        }

        $baseCode = Str::slug($validated['new_shop_name']);
        $code = $baseCode !== '' ? $baseCode : 'shop';
        $suffix = 1;
        $candidate = $code;

        while (Shop::query()->where('code', $candidate)->exists()) {
            $candidate = $code.'-'.$suffix;
            $suffix++;
        }

        return Shop::query()->create([
            'name' => $validated['new_shop_name'],
            'code' => $candidate,
            'tagline' => $validated['new_shop_tagline'] ?: null,
            'phone_primary' => $validated['new_shop_phone_primary'] ?: null,
            'phone_secondary' => $validated['new_shop_phone_secondary'] ?: null,
            'address_line_1' => $validated['new_shop_address_line_1'] ?: null,
            'address_line_2' => $validated['new_shop_address_line_2'] ?: null,
            'logo_path' => $validated['new_shop_logo_path'] ?: null,
            'receipt_footer_company_name' => $validated['new_shop_receipt_footer_company_name'] ?: null,
            'receipt_footer_phone' => $validated['new_shop_receipt_footer_phone'] ?: null,
            'receipt_footer_email' => $validated['new_shop_receipt_footer_email'] ?: null,
            'is_active' => true,
        ])->id;
    }
}

