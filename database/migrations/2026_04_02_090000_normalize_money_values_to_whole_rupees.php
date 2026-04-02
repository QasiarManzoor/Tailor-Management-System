<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            DB::table('payments')->update([
                'amount' => DB::raw('ROUND(amount, 0)'),
            ]);

            DB::table('orders')->update([
                'total_amount' => DB::raw('ROUND(total_amount, 0)'),
                'advance_amount' => DB::raw('ROUND(advance_amount, 0)'),
            ]);

            DB::table('orders')->update([
                'balance_amount' => DB::raw('GREATEST(total_amount - advance_amount, 0)'),
            ]);
        });
    }

    public function down(): void
    {
        // This data normalization cannot be reversed safely.
    }
};
