<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addShopColumn('users', after: 'id');
        $this->addShopColumn('customers');
        $this->addShopColumn('measurements');
        $this->addShopColumn('orders');
        $this->addShopColumn('payments');
        $this->addShopColumn('activity_logs');

        $defaultShopId = DB::table('shops')
            ->whereIn('code', ['default-tailor-shop', 'master-rashid'])
            ->orderByRaw("case when code = 'default-tailor-shop' then 0 else 1 end")
            ->value('id');

        if (! $defaultShopId) {
            return;
        }

        DB::table('users')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);
        DB::table('customers')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);

        DB::table('measurements')->select(['id', 'customer_id'])->orderBy('id')->chunkById(100, function ($rows) use ($defaultShopId): void {
            foreach ($rows as $row) {
                $shopId = DB::table('customers')->where('id', $row->customer_id)->value('shop_id') ?: $defaultShopId;
                DB::table('measurements')->where('id', $row->id)->update(['shop_id' => $shopId]);
            }
        });

        DB::table('orders')->select(['id', 'customer_id'])->orderBy('id')->chunkById(100, function ($rows) use ($defaultShopId): void {
            foreach ($rows as $row) {
                $shopId = DB::table('customers')->where('id', $row->customer_id)->value('shop_id') ?: $defaultShopId;
                DB::table('orders')->where('id', $row->id)->update(['shop_id' => $shopId]);
            }
        });

        DB::table('payments')->select(['id', 'order_id'])->orderBy('id')->chunkById(100, function ($rows) use ($defaultShopId): void {
            foreach ($rows as $row) {
                $shopId = DB::table('orders')->where('id', $row->order_id)->value('shop_id') ?: $defaultShopId;
                DB::table('payments')->where('id', $row->id)->update(['shop_id' => $shopId]);
            }
        });

        DB::table('activity_logs')->select(['id', 'user_id'])->orderBy('id')->chunkById(100, function ($rows) use ($defaultShopId): void {
            foreach ($rows as $row) {
                $shopId = $row->user_id
                    ? DB::table('users')->where('id', $row->user_id)->value('shop_id')
                    : $defaultShopId;

                DB::table('activity_logs')->where('id', $row->id)->update(['shop_id' => $shopId ?: $defaultShopId]);
            }
        });

        $this->addSecondaryIndexes();
    }

    public function down(): void
    {
        $this->dropIndexIfExists('users', 'users_shop_id_role_index');
        $this->dropIndexIfExists('customers', 'customers_shop_id_name_index');
        $this->dropIndexIfExists('customers', 'customers_shop_id_phone_index');
        $this->dropIndexIfExists('measurements', 'measurements_shop_id_customer_id_index');
        $this->dropIndexIfExists('orders', 'orders_shop_id_customer_id_index');
        $this->dropIndexIfExists('orders', 'orders_shop_id_status_delivery_date_index');
        $this->dropIndexIfExists('payments', 'payments_shop_id_order_id_index');
        $this->dropIndexIfExists('activity_logs', 'activity_logs_shop_id_action_index');

        $this->dropShopColumn('activity_logs');
        $this->dropShopColumn('payments');
        $this->dropShopColumn('orders');
        $this->dropShopColumn('measurements');
        $this->dropShopColumn('customers');
        $this->dropShopColumn('users');
    }

    protected function addShopColumn(string $table, ?string $after = null): void
    {
        if (Schema::hasColumn($table, 'shop_id')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($after) {
            $column = $blueprint->foreignId('shop_id')->nullable()->constrained()->nullOnDelete();

            if ($after) {
                $column->after($after);
            }
        });
    }

    protected function dropShopColumn(string $table): void
    {
        if (! Schema::hasColumn($table, 'shop_id')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->dropConstrainedForeignId('shop_id');
        });
    }

    protected function addSecondaryIndexes(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['shop_id', 'role']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index(['shop_id', 'name']);
            $table->index(['shop_id', 'phone']);
        });

        Schema::table('measurements', function (Blueprint $table) {
            $table->index(['shop_id', 'customer_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['shop_id', 'customer_id']);
            $table->index(['shop_id', 'status', 'delivery_date']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['shop_id', 'order_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['shop_id', 'action']);
        });
    }

    protected function dropIndexIfExists(string $table, string $index): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($index) {
                $blueprint->dropIndex($index);
            });
        } catch (Throwable) {
            // Ignore missing indexes during rollback safety.
        }
    }
};
