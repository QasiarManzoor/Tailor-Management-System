<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_repeated_failed_attempts(): void
    {
        $shop = $this->createShop('Throttle Shop', 'throttle-shop');

        User::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'password' => Hash::make('correct-password'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from(route('login'))
                ->post(route('login.store'), [
                    'email' => 'owner@example.com',
                    'password' => 'wrong-password',
                ])
                ->assertRedirect(route('login'))
                ->assertSessionHasErrors('email');
        }

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'owner@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'Too many login attempts. Please try again in 5 minute(s).',
            ]);
    }

    public function test_super_admin_routes_are_blocked_for_owner_and_available_to_super_admin(): void
    {
        $shop = $this->createShop('Admin Access Shop', 'admin-access-shop');

        $owner = User::factory()->create([
            'shop_id' => $shop->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->actingAs($owner)
            ->get(route('superadmin.dashboard'))
            ->assertForbidden();

        $this->actingAs($superAdmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk();
    }

    public function test_owner_cannot_open_another_shops_order_by_url(): void
    {
        $shopA = $this->createShop('Shop A', 'shop-a');
        $shopB = $this->createShop('Shop B', 'shop-b');

        $ownerA = User::factory()->create([
            'shop_id' => $shopA->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $customerB = Customer::create([
            'shop_id' => $shopB->id,
            'name' => 'Customer B',
            'phone' => '03001234567',
        ]);

        $orderB = Order::create([
            'shop_id' => $shopB->id,
            'customer_id' => $customerB->id,
            'order_type' => 'Tailoring Order',
            'quantity' => 1,
            'total_amount' => 5000,
            'advance_amount' => 1000,
            'booking_date' => now()->toDateString(),
            'delivery_date' => now()->addDays(5)->toDateString(),
            'status' => 'booked',
            'priority' => 'normal',
        ]);

        $this->actingAs($ownerA)
            ->get(route('orders.show', $orderB))
            ->assertNotFound();
    }

    public function test_owner_cannot_open_another_shops_print_routes(): void
    {
        $shopA = $this->createShop('Print Shop A', 'print-shop-a');
        $shopB = $this->createShop('Print Shop B', 'print-shop-b');

        $ownerA = User::factory()->create([
            'shop_id' => $shopA->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $customerB = Customer::create([
            'shop_id' => $shopB->id,
            'name' => 'Print Customer B',
            'phone' => '03001234567',
        ]);

        $measurementB = Measurement::create([
            'shop_id' => $shopB->id,
            'customer_id' => $customerB->id,
            'title' => 'Shop B Measurement',
            'chest' => 40,
        ]);

        $orderB = Order::create([
            'shop_id' => $shopB->id,
            'customer_id' => $customerB->id,
            'measurement_id' => $measurementB->id,
            'order_type' => 'Tailoring Order',
            'quantity' => 1,
            'total_amount' => 5000,
            'advance_amount' => 1000,
            'booking_date' => now()->toDateString(),
            'delivery_date' => now()->addDays(5)->toDateString(),
            'status' => 'booked',
            'priority' => 'normal',
        ]);

        $this->actingAs($ownerA)
            ->get(route('measurements.print', $measurementB))
            ->assertNotFound();

        $this->actingAs($ownerA)
            ->get(route('orders.receipt', $orderB))
            ->assertNotFound();
    }

    public function test_owner_created_customer_is_forced_into_their_own_shop(): void
    {
        $ownerShop = $this->createShop('Owner Shop', 'owner-shop');
        $otherShop = $this->createShop('Other Shop', 'other-shop');

        $owner = User::factory()->create([
            'shop_id' => $ownerShop->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->actingAs($owner)
            ->post(route('customers.store'), [
                'shop_id' => $otherShop->id,
                'name' => 'Forced Shop Customer',
                'phone' => '03001234567',
            ])
            ->assertRedirect();

        $customer = Customer::query()->where('name', 'Forced Shop Customer')->firstOrFail();

        $this->assertSame($ownerShop->id, $customer->shop_id);
    }

    public function test_payment_validation_blocks_past_dates_and_overpayment(): void
    {
        $shop = $this->createShop('Payment Shop', 'payment-shop');
        $owner = User::factory()->create([
            'shop_id' => $shop->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'shop_id' => $shop->id,
            'name' => 'Payment Customer',
            'phone' => '03001234567',
        ]);

        $order = Order::create([
            'shop_id' => $shop->id,
            'customer_id' => $customer->id,
            'order_type' => 'Tailoring Order',
            'quantity' => 1,
            'total_amount' => 5000,
            'advance_amount' => 1000,
            'booking_date' => now()->toDateString(),
            'delivery_date' => now()->addDays(7)->toDateString(),
            'status' => 'booked',
            'priority' => 'normal',
        ]);

        $this->actingAs($owner)
            ->from(route('orders.payments.create', $order))
            ->post(route('orders.payments.store', $order), [
                'amount' => 4500,
                'payment_method' => 'cash',
                'payment_date' => now()->subDay()->toDateString(),
                'note' => 'Invalid payment attempt',
            ])
            ->assertRedirect(route('orders.payments.create', $order))
            ->assertSessionHasErrors(['payment_date']);

        $this->actingAs($owner)
            ->from(route('orders.payments.create', $order))
            ->post(route('orders.payments.store', $order), [
                'amount' => 4500,
                'payment_method' => 'cash',
                'payment_date' => now()->toDateString(),
                'note' => 'Overpayment attempt',
            ])
            ->assertRedirect(route('orders.payments.create', $order))
            ->assertSessionHasErrors([
                'amount' => 'Payment amount cannot be greater than the remaining balance.',
            ]);
    }

    private function createShop(string $name, string $code): Shop
    {
        return Shop::create([
            'name' => $name,
            'code' => $code,
            'tagline' => 'Digital Order Slip',
            'phone_primary' => '03135271056',
            'phone_secondary' => '0576108185',
            'address_line_1' => 'Test Address Line 1',
            'address_line_2' => 'Test Address Line 2',
            'is_active' => true,
        ]);
    }
}
