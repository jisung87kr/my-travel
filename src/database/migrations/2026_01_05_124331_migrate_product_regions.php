<?php

use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Map existing region enum values to new region_id
        $regions = Region::provinces()->pluck('id', 'code')->toArray();

        // Use raw DB query to avoid Enum casting issues
        DB::table('products')
            ->whereNotNull('region')
            ->whereNull('region_id')
            ->orderBy('id')
            ->chunk(100, function ($products) use ($regions) {
                foreach ($products as $product) {
                    $oldRegion = $product->region;

                    if (isset($regions[$oldRegion])) {
                        DB::table('products')
                            ->where('id', $product->id)
                            ->update(['region_id' => $regions[$oldRegion]]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Restore region from region_id
        $regions = Region::provinces()->pluck('code', 'id')->toArray();

        DB::table('products')
            ->whereNotNull('region_id')
            ->orderBy('id')
            ->chunk(100, function ($products) use ($regions) {
                foreach ($products as $product) {
                    if (isset($regions[$product->region_id])) {
                        DB::table('products')
                            ->where('id', $product->id)
                            ->update(['region' => $regions[$product->region_id]]);
                    }
                }
            });

        // Clear region_id
        DB::table('products')->update(['region_id' => null]);
    }
};
