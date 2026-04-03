<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $shops = Shop::query()
            ->withCount(['users', 'customers', 'measurements', 'orders', 'payments'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('code', 'like', '%'.$search.'%')
                        ->orWhere('phone_primary', 'like', '%'.$search.'%')
                        ->orWhere('phone_secondary', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('superadmin.shops.index', compact('shops', 'search'));
    }

    public function toggleStatus(Shop $shop): RedirectResponse
    {
        $shop->update(['is_active' => ! $shop->is_active]);

        ActivityLogger::log('shop.status.updated', 'Super admin changed a shop status.', [
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'is_active' => $shop->is_active,
        ]);

        if (! $shop->is_active && session('superadmin_shop_context_id') === $shop->id) {
            session()->forget('superadmin_shop_context_id');
        }

        return back()->with('success', $shop->is_active ? 'Shop activated successfully.' : 'Shop archived successfully.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        if ($shop->users()->exists()) {
            return back()->withErrors([
                'delete' => 'This shop cannot be deleted while users are still assigned to it. Remove or reassign those users first.',
            ]);
        }

        $shopName = $shop->name;
        $shopId = $shop->id;

        DB::transaction(function () use ($shop): void {
            $shop->activityLogs()->delete();
            $shop->payments()->delete();
            $shop->orders()->delete();
            $shop->measurements()->delete();
            $shop->customers()->delete();
            $shop->delete();
        });

        if (session('superadmin_shop_context_id') === $shopId) {
            session()->forget('superadmin_shop_context_id');
        }

        ActivityLogger::log('shop.deleted', 'Super admin deleted a shop with all related business data.', [
            'deleted_shop_id' => $shopId,
            'deleted_shop_name' => $shopName,
        ]);

        return back()->with('success', 'Shop and all related business data deleted successfully.');
    }
}
