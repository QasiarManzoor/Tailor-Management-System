<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('tagline')->nullable();
                $table->string('phone_primary')->nullable();
                $table->string('phone_secondary')->nullable();
                $table->string('address_line_1')->nullable();
                $table->string('address_line_2')->nullable();
                $table->string('logo_path')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        $existingShopId = DB::table('shops')->where('code', 'master-rashid')->value('id');

        if (! $existingShopId) {
            DB::table('shops')->insert([
                'name' => 'MASTER RASHID',
                'code' => 'master-rashid',
                'tagline' => 'Digital Order Slip',
                'phone_primary' => '0313-5271056',
                'phone_secondary' => '057-6108185',
                'address_line_1' => 'Shop # 4, Faizan Plaza, Upside Mezan Bank Basement',
                'address_line_2' => 'Near Soneri Bank Main PWD Islamabad',
                'logo_path' => 'images/shaq-logo.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
