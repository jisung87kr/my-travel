<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('slug', 100)->unique();
            $table->unsignedTinyInteger('level');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('popularity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('timezone', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('level');
            $table->index(['is_active', 'level']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
