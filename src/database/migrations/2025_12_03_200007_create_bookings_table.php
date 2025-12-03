<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('product_schedules')->cascadeOnDelete();
            $table->string('booking_code', 20)->unique();
            $table->string('status')->default(BookingStatus::PENDING->value);
            $table->unsignedTinyInteger('adult_count')->default(1);
            $table->unsignedTinyInteger('child_count')->default(0);
            $table->unsignedTinyInteger('infant_count')->default(0);
            $table->unsignedInteger('total_price');
            $table->text('special_request')->nullable();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('contact_email');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['product_id', 'status']);
            $table->index('schedule_id');
            $table->index('booking_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
