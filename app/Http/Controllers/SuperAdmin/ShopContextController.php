<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;

class ShopContextController extends Controller
{
    public function activate(Shop $shop): RedirectResponse
    {
        session(['superadmin_shop_context_id' => $shop->id]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Now managing data for '.$shop->name.'.');
    }

    public function clear(): RedirectResponse
    {
        $shopName = optional(Shop::query()->find(session('superadmin_shop_context_id')))->name;

        session()->forget('superadmin_shop_context_id');

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', $shopName ? 'Exited '.$shopName.' management view.' : 'Exited shop management view.');
    }
}
