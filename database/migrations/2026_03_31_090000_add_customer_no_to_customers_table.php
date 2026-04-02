<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_no')->nullable()->unique()->after('id');
        });

        $customers = DB::table('customers')->select('id')->orderBy('id')->get();
        $sequence = 1;

        foreach ($customers as $customer) {
            DB::table('customers')
                ->where('id', $customer->id)
                ->update([
                    'customer_no' => sprintf('2026%05d', $sequence),
                ]);

            $sequence++;
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['customer_no']);
            $table->dropColumn('customer_no');
        });
    }
};
