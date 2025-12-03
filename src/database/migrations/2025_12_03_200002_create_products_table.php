<?php

use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default(ProductType::DAY_TOUR->value);
            $table->string('region');
            $table->unsignedInteger('duration')->nullable()->comment('Duration in minutes');
            $table->string('booking_type')->default(BookingType::INSTANT->value);
            $table->string('status')->default(ProductStatus::DRAFT->value);
            $table->decimal('average_rating', 2, 1)->nullable();
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('booking_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'region']);
            $table->index('type');
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
