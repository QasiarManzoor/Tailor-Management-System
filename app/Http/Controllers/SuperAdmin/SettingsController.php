<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('superadmin.settings.edit', [
            'settings' => SystemSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_tagline' => ['nullable', 'string', 'max:255'],
            'shop_phone_primary' => ['nullable', 'string', 'max:255'],
            'shop_phone_secondary' => ['nullable', 'string', 'max:255'],
            'shop_address_line_1' => ['nullable', 'string', 'max:255'],
            'shop_address_line_2' => ['nullable', 'string', 'max:255'],
            'receipt_footer_company_name' => ['nullable', 'string', 'max:255'],
            'receipt_footer_phone' => ['nullable', 'string', 'max:255'],
            'receipt_footer_email' => ['nullable', 'email', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = SystemSetting::current();
        $settings->fill($validated)->save();
        $settings->refreshCache();

        ActivityLogger::log('settings.updated', 'Super admin updated system settings.', [
            'shop_name' => $settings->shop_name,
            'logo_path' => $settings->logo_path,
        ]);

        return redirect()
            ->route('superadmin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}
