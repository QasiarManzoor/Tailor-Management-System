<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Order;
use Illuminate\Database\Seeder;

class TailorShopSeeder extends Seeder
{
    public function run(): void
    {
        $customers = collect([
            [
                'name' => 'Muhammad Rashid',
                'phone' => '03001234567',
                'alternate_phone' => '03111234567',
                'address' => 'Model Town, Lahore',
                'gender' => 'male',
                'notes' => 'Prefers classic collar and loose fitting.',
            ],
            [
                'name' => 'Ayesha Khalid',
                'phone' => '03019876543',
                'alternate_phone' => null,
                'address' => 'Johar Town, Lahore',
                'gender' => 'female',
                'notes' => 'Usually books 3-piece suits before Eid.',
            ],
            [
                'name' => 'Usman Tariq',
                'phone' => '03214567890',
                'alternate_phone' => null,
                'address' => 'Gulberg, Lahore',
                'gender' => 'male',
                'notes' => 'Needs urgent delivery for office uniforms.',
            ],
        ])->map(fn (array $attributes) => Customer::create($attributes));

        $measurementOne = Measurement::create([
            'customer_id' => $customers[0]->id,
            'title' => 'Summer Suit 2026',
            'kameez_length' => 41,
            'chest' => 40,
            'waist' => 37,
            'hip' => 40,
            'shoulder' => 18,
            'sleeve' => 24,
            'collar' => 15.5,
            'arm_hole' => 18,
            'shalwar_length' => 39,
            'thigh' => 24,
            'knee' => 17,
            'bottom_width' => 14,
            'cuff' => 9,
            'front_style' => 'Plain front',
            'collar_style' => 'Ban collar',
            'pocket_style' => 'Side pocket',
            'trouser_style' => 'Classic shalwar',
            'special_notes' => 'Add soft collar support.',
        ]);

        $measurementTwo = Measurement::create([
            'customer_id' => $customers[1]->id,
            'title' => 'Party Wear',
            'kameez_length' => 44,
            'chest' => 38,
            'waist' => 34,
            'hip' => 40,
            'shoulder' => 15,
            'sleeve' => 22,
            'arm_hole' => 17,
            'shalwar_length' => 38,
            'thigh' => 23,
            'knee' => 16,
            'bottom_width' => 12,
            'cuff' => 8,
            'front_style' => 'Open front',
            'collar_style' => 'Round neck',
            'pocket_style' => 'Hidden',
            'trouser_style' => 'Straight trouser',
            'special_notes' => 'Light fitting with matching cuffs.',
        ]);

        $orderOne = Order::create([
            'customer_id' => $customers[0]->id,
            'measurement_id' => $measurementOne->id,
            'order_type' => '2 x Shalwar Kameez',
            'fabric_details' => 'Wash & wear navy blue, customer fabric',
            'quantity' => 2,
            'total_amount' => 6500,
            'advance_amount' => 2500,
            'booking_date' => now()->subDays(3)->toDateString(),
            'delivery_date' => now()->addDays(2)->toDateString(),
            'status' => 'stitching',
            'priority' => 'normal',
            'special_instructions' => 'Keep side pockets deep and collar medium stiff.',
        ]);

        $orderOne->payments()->create([
            'amount' => 1000,
            'payment_method' => 'cash',
            'payment_date' => now()->subDay()->toDateString(),
            'note' => 'Extra advance received on follow-up visit.',
        ]);
        $orderOne->advance_amount = 3500;
        $orderOne->refreshBalance();

        Order::create([
            'customer_id' => $customers[1]->id,
            'measurement_id' => $measurementTwo->id,
            'order_type' => '1 x Fancy Suit',
            'fabric_details' => 'Premium lawn with embroidery',
            'quantity' => 1,
            'total_amount' => 4800,
            'advance_amount' => 4800,
            'booking_date' => now()->subDays(1)->toDateString(),
            'delivery_date' => now()->toDateString(),
            'status' => 'ready',
            'priority' => 'urgent',
            'special_instructions' => 'Prepare for same-day pickup.',
        ]);

        Order::create([
            'customer_id' => $customers[2]->id,
            'measurement_id' => null,
            'order_type' => '3 x Office Uniform',
            'fabric_details' => 'Shop fabric, charcoal grey',
            'quantity' => 3,
            'total_amount' => 9000,
            'advance_amount' => 3000,
            'booking_date' => now()->subDays(5)->toDateString(),
            'delivery_date' => now()->subDay()->toDateString(),
            'status' => 'cutting',
            'priority' => 'urgent',
            'special_instructions' => 'Need one fitting before final stitching.',
        ]);
    }
}
