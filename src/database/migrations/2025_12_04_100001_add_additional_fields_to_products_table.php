<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedTinyInteger('min_persons')->default(1)->after('duration');
            $table->unsignedTinyInteger('max_persons')->default(10)->after('min_persons');
            $table->string('meeting_point')->nullable()->after('booking_type');
            $table->text('meeting_point_detail')->nullable()->after('meeting_point');
            $table->decimal('latitude', 10, 8)->nullable()->after('meeting_point_detail');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'min_persons',
                'max_persons',
                'meeting_point',
                'meeting_point_detail',
                'latitude',
                'longitude',
            ]);
        });
    }
};
