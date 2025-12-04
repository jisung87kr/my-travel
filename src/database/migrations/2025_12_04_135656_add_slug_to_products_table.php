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
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
        });

        // Generate slugs for existing products
        $products = \DB::table('products')->get();
        foreach ($products as $product) {
            $translation = \DB::table('product_translations')
                ->where('product_id', $product->id)
                ->where('locale', 'ko')
                ->first();

            $title = $translation?->title ?? 'product-' . $product->id;
            $slug = \Str::slug($title) ?: 'product-' . $product->id;

            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (\DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            \DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
