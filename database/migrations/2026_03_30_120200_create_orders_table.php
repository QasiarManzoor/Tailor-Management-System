<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('measurement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_type');
            $table->text('fabric_details')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('advance_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2)->default(0);
            $table->date('booking_date');
            $table->date('trial_date')->nullable();
            $table->date('delivery_date');
            $table->date('delivered_date')->nullable();
            $table->string('status')->default('booked')->index();
            $table->string('priority')->default('normal')->index();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
