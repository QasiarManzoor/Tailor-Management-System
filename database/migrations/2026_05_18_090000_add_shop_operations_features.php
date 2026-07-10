<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->default('tailor');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('worker_id')->nullable()->after('measurement_id')->constrained('workers')->nullOnDelete();
            $table->string('work_category')->default('new_stitch')->after('order_type')->index();
            $table->string('trial_status')->default('not_required')->after('trial_date')->index();
            $table->text('alteration_notes')->nullable()->after('special_instructions');
        });

        Schema::create('order_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->boolean('is_done')->default(false)->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('design_reference')->index();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('cashbook_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('entry_date')->index();
            $table->string('type')->index();
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        $now = now();
        $defaultWorkers = DB::table('shops')->select('id')->get()->map(fn ($shop) => [
            'shop_id' => $shop->id,
            'name' => 'Unassigned Tailor',
            'role' => 'tailor',
            'phone' => null,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        if ($defaultWorkers) {
            DB::table('workers')->insert($defaultWorkers);
        }

        DB::table('orders')->orderBy('id')->chunk(100, function ($orders) use ($now) {
            $rows = [];

            foreach ($orders as $order) {
                foreach (['Fabric received', 'Cutting done', 'Stitching done', 'Buttons attached', 'Ironing done', 'Final checking done', 'Packed', 'Delivered'] as $label) {
                    $rows[] = [
                        'order_id' => $order->id,
                        'label' => $label,
                        'is_done' => false,
                        'completed_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if ($rows) {
                DB::table('order_checklist_items')->insert($rows);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashbook_entries');
        Schema::dropIfExists('order_attachments');
        Schema::dropIfExists('order_checklist_items');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('worker_id');
            $table->dropColumn(['work_category', 'trial_status', 'alteration_notes']);
        });

        Schema::dropIfExists('workers');
    }
};
