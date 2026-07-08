<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_the_dashboard_page_loads(): void
    {
        $response = $this->actingAs($this->owner())->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Shop Dashboard');
        $response->assertSee('Pending Balance');
    }

    public function test_core_pages_load_without_errors(): void
    {
        $customer = Customer::firstOrFail();
        $measurement = Measurement::firstOrFail();
        $order = Order::firstOrFail();

        $pages = [
            route('dashboard'),
            route('reports.index'),
            route('calendar.index'),
            route('inventory.index'),
            route('workers.index'),
            route('cashbook.index'),
            route('customers.index'),
            route('customers.create'),
            route('customers.show', $customer),
            route('customers.ledger', $customer),
            route('customers.edit', $customer),
            route('measurements.index'),
            route('measurements.create', ['customer_id' => $customer->id]),
            route('measurements.show', $measurement),
            route('measurements.edit', $measurement),
            route('measurements.print', $measurement),
            route('orders.index'),
            route('orders.kanban'),
            route('orders.create', ['customer_id' => $customer->id]),
            route('orders.show', $order),
            route('orders.edit', $order),
            route('orders.receipt', $order),
            route('orders.invoice', $order),
            route('orders.payments.create', $order),
        ];

        foreach ($pages as $page) {
            $this->actingAs($this->owner())->get($page)->assertOk();
        }

        $this->actingAs(User::where('email', 'admin@shaqtechnologies.com')->firstOrFail())
            ->get(route('superadmin.backups.index'))
            ->assertOk();
    }

    public function test_measurement_pages_show_bilingual_labels(): void
    {
        $measurement = Measurement::firstOrFail();

        $this->actingAs($this->owner())->get(route('measurements.create'))
            ->assertOk()
            ->assertSee('Kameez Length')
            ->assertSee('لمبائی')
            ->assertSee('Special Notes')
            ->assertSee('خصوصی ہدایات');

        $this->actingAs($this->owner())->get(route('measurements.show', $measurement))
            ->assertOk()
            ->assertSee('Chest')
            ->assertSee('چھاتی')
            ->assertSee('Customer Information')
            ->assertSee('کسٹمر معلومات');
    }

    public function test_print_pages_load_print_controls(): void
    {
        $measurement = Measurement::firstOrFail();
        $order = Order::firstOrFail();

        $this->actingAs($this->owner())->get(route('measurements.print', $measurement))
            ->assertOk()
            ->assertSee('Print')
            ->assertSee('XYZ Tailor Shop');

        $this->actingAs($this->owner())->get(route('orders.receipt', $order))
            ->assertOk()
            ->assertSee('Payment Summary');

        $this->actingAs($this->owner())->get(route('orders.invoice', $order))
            ->assertOk()
            ->assertSee('Invoice / Delivery Slip');
    }

    private function owner(): User
    {
        return User::where('email', 'owner@shop.com')->firstOrFail();
    }
}
