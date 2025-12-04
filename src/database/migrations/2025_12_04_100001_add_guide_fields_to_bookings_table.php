<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('guide_id')->nullable()->after('schedule_id')->constrained('users')->nullOnDelete();
            $table->timestamp('checked_in_at')->nullable()->after('confirmed_at');
            $table->timestamp('started_at')->nullable()->after('checked_in_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['guide_id']);
            $table->dropColumn(['guide_id', 'checked_in_at', 'started_at', 'completed_at']);
        });
    }
};
