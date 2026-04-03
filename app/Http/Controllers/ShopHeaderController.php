<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Support\ActivityLogger;
use App\Support\CurrentShop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShopHeaderController extends Controller
{
    public function edit(Request $request): View|RedirectResponse
    {
        $shop = $this->resolveShop($request);

        if (! $shop) {
            return redirect()->route('dashboard')->with('error', 'No shop is linked to this account yet.');
        }

        return view('shop-header.edit', [
            'shop' => $shop,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $shop = $this->resolveShop($request);

        if (! $shop) {
            return redirect()->route('dashboard')->with('error', 'No shop is linked to this account yet.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'phone_primary' => ['required', 'string', 'max:255'],
            'phone_secondary' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['required', 'string', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:255'],
        ]);

        $shop->update($validated);

        ActivityLogger::log('shop.header.updated', 'Slip header updated for a shop.', [
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'logo_path' => $shop->logo_path,
        ]);

        return redirect()->route('shop-header.edit')->with('success', 'Slip header updated successfully.');
    }

    protected function resolveShop(Request $request): ?Shop
    {
        $managedShop = CurrentShop::contextShop();

        if ($managedShop) {
            return $managedShop;
        }

        return $request->user()?->shop;
    }
}
