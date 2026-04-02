<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->default('MASTER RASHID');
            $table->string('shop_tagline')->default('Digital Order Slip');
            $table->string('shop_phone_primary')->nullable();
            $table->string('shop_phone_secondary')->nullable();
            $table->string('shop_address_line_1')->nullable();
            $table->string('shop_address_line_2')->nullable();
            $table->string('receipt_footer_company_name')->nullable();
            $table->string('receipt_footer_phone')->nullable();
            $table->string('receipt_footer_email')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
