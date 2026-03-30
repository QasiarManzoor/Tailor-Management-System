<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
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
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Shop Dashboard');
        $response->assertSee('Pending Balance Widget');
    }

    public function test_core_pages_load_without_errors(): void
    {
        $customer = Customer::firstOrFail();
        $measurement = Measurement::firstOrFail();
        $order = Order::firstOrFail();

        $pages = [
            route('customers.index'),
            route('customers.create'),
            route('customers.show', $customer),
            route('customers.edit', $customer),
            route('measurements.index'),
            route('measurements.create', ['customer_id' => $customer->id]),
            route('measurements.show', $measurement),
            route('measurements.edit', $measurement),
            route('measurements.print', $measurement),
            route('orders.index'),
            route('orders.create', ['customer_id' => $customer->id]),
            route('orders.show', $order),
            route('orders.edit', $order),
            route('orders.receipt', $order),
            route('orders.invoice', $order),
            route('orders.payments.create', $order),
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_measurement_pages_show_bilingual_labels(): void
    {
        $measurement = Measurement::firstOrFail();

        $this->get(route('measurements.create'))
            ->assertOk()
            ->assertSee('Kameez Length')
            ->assertSee('لمبائی')
            ->assertSee('Special Notes')
            ->assertSee('خصوصی ہدایات');

        $this->get(route('measurements.show', $measurement))
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

        $this->get(route('measurements.print', $measurement))
            ->assertOk()
            ->assertSee('Print')
            ->assertSee('Rashid Tailor Shop');

        $this->get(route('orders.receipt', $order))
            ->assertOk()
            ->assertSee('Payment Summary');

        $this->get(route('orders.invoice', $order))
            ->assertOk()
            ->assertSee('Invoice / Delivery Slip');
    }
}
