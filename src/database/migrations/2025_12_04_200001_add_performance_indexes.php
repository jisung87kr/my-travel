<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bookings 테이블 - booking_date 인덱스 (가이드 스케줄용)
        if (!$this->indexExists('bookings', 'bookings_date_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'booking_date')) {
                    $table->index('booking_date', 'bookings_date_index');
                }
            });
        }

        // Reviews 테이블 인덱스
        if (!$this->indexExists('reviews', 'reviews_booking_index')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('booking_id', 'reviews_booking_index');
            });
        }

        // Messages 테이블 인덱스
        if (!$this->indexExists('messages', 'messages_thread_index')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->index(['booking_id', 'created_at'], 'messages_thread_index');
            });
        }

        if (!$this->indexExists('messages', 'messages_unread_index')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->index(['receiver_id', 'read_at'], 'messages_unread_index');
            });
        }

        // Wishlists 테이블 인덱스
        if (!$this->indexExists('wishlists', 'wishlists_user_product_index')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->index(['user_id', 'product_id'], 'wishlists_user_product_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_date_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_booking_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_thread_index');
            $table->dropIndex('messages_unread_index');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex('wishlists_user_product_index');
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = config('database.default');

        if ($connection === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('$table')");
            foreach ($indexes as $index) {
                if ($index->name === $indexName) {
                    return true;
                }
            }
            return false;
        }

        // MySQL
        $indexes = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
