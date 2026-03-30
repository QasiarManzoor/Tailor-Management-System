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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->decimal('kameez_length', 8, 2)->nullable();
            $table->decimal('chest', 8, 2)->nullable();
            $table->decimal('waist', 8, 2)->nullable();
            $table->decimal('hip', 8, 2)->nullable();
            $table->decimal('shoulder', 8, 2)->nullable();
            $table->decimal('sleeve', 8, 2)->nullable();
            $table->decimal('collar', 8, 2)->nullable();
            $table->decimal('arm_hole', 8, 2)->nullable();
            $table->decimal('shalwar_length', 8, 2)->nullable();
            $table->decimal('thigh', 8, 2)->nullable();
            $table->decimal('knee', 8, 2)->nullable();
            $table->decimal('bottom_width', 8, 2)->nullable();
            $table->decimal('cuff', 8, 2)->nullable();
            $table->string('front_style')->nullable();
            $table->string('collar_style')->nullable();
            $table->string('pocket_style')->nullable();
            $table->string('trouser_style')->nullable();
            $table->text('special_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
