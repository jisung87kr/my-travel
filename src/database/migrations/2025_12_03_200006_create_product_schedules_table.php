<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->unsignedInteger('total_count')->default(0)->comment('Total available slots');
            $table->unsignedInteger('available_count')->default(0)->comment('Currently available slots');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'date']);
            $table->index(['date', 'is_active', 'available_count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_schedules');
    }
};
