<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (! Schema::hasColumn('shops', 'receipt_footer_company_name')) {
                $table->string('receipt_footer_company_name')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('shops', 'receipt_footer_phone')) {
                $table->string('receipt_footer_phone')->nullable()->after('receipt_footer_company_name');
            }

            if (! Schema::hasColumn('shops', 'receipt_footer_email')) {
                $table->string('receipt_footer_email')->nullable()->after('receipt_footer_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'receipt_footer_email')) {
                $table->dropColumn('receipt_footer_email');
            }

            if (Schema::hasColumn('shops', 'receipt_footer_phone')) {
                $table->dropColumn('receipt_footer_phone');
            }

            if (Schema::hasColumn('shops', 'receipt_footer_company_name')) {
                $table->dropColumn('receipt_footer_company_name');
            }
        });
    }
};
