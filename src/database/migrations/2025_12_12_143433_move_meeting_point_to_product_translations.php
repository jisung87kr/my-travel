<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add columns to product_translations
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('meeting_point')->nullable()->after('notes');
            $table->text('meeting_point_detail')->nullable()->after('meeting_point');
        });

        // 2. Migrate existing data from products to product_translations (ko locale)
        $products = DB::table('products')
            ->whereNotNull('meeting_point')
            ->orWhereNotNull('meeting_point_detail')
            ->get();

        foreach ($products as $product) {
            DB::table('product_translations')
                ->where('product_id', $product->id)
                ->where('locale', 'ko')
                ->update([
                    'meeting_point' => $product->meeting_point,
                    'meeting_point_detail' => $product->meeting_point_detail,
                ]);
        }

        // 3. Remove columns from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meeting_point', 'meeting_point_detail']);
        });
    }

    public function down(): void
    {
        // 1. Add columns back to products
        Schema::table('products', function (Blueprint $table) {
            $table->string('meeting_point')->nullable()->after('booking_type');
            $table->text('meeting_point_detail')->nullable()->after('meeting_point');
        });

        // 2. Migrate data back from product_translations (ko locale) to products
        $translations = DB::table('product_translations')
            ->where('locale', 'ko')
            ->whereNotNull('meeting_point')
            ->orWhereNotNull('meeting_point_detail')
            ->get();

        foreach ($translations as $translation) {
            DB::table('products')
                ->where('id', $translation->product_id)
                ->update([
                    'meeting_point' => $translation->meeting_point,
                    'meeting_point_detail' => $translation->meeting_point_detail,
                ]);
        }

        // 3. Remove columns from product_translations
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn(['meeting_point', 'meeting_point_detail']);
        });
    }
};
