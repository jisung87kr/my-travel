<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('content');
            $table->text('vendor_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'is_visible']);
            $table->index('user_id');
        });

        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
    }
};
