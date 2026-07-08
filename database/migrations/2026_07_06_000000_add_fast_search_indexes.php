<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->index(['shop_id', 'customer_no'], 'customers_shop_customer_no_index');
            $table->index(['shop_id', 'alternate_phone'], 'customers_shop_alternate_phone_index');
            $table->fullText('name', 'customers_name_fulltext');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['shop_id', 'order_no'], 'orders_shop_order_no_index');
            $table->fullText('order_type', 'orders_order_type_fulltext');
        });

        Schema::table('measurements', function (Blueprint $table) {
            $table->index(['shop_id', 'title'], 'measurements_shop_title_index');
            $table->fullText('title', 'measurements_title_fulltext');
        });
    }

    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->dropFullText('measurements_title_fulltext');
            $table->dropIndex('measurements_shop_title_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropFullText('orders_order_type_fulltext');
            $table->dropIndex('orders_shop_order_no_index');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropFullText('customers_name_fulltext');
            $table->dropIndex('customers_shop_alternate_phone_index');
            $table->dropIndex('customers_shop_customer_no_index');
        });
    }
};
