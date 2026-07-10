<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->timestamps();
        });

        DB::table('orders')
            ->select(['id', 'status', 'created_at', 'updated_at'])
            ->orderBy('id')
            ->chunk(100, function ($orders) {
                $rows = $orders->map(function ($order) {
                    return [
                        'order_id' => $order->id,
                        'changed_by' => null,
                        'from_status' => null,
                        'to_status' => $order->status,
                        'created_at' => $order->created_at ?: now(),
                        'updated_at' => $order->updated_at ?: now(),
                    ];
                })->all();

                DB::table('order_status_histories')->insert($rows);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
