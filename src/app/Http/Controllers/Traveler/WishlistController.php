<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();

            return response()->json([
                'success' => true,
                'added' => false,
                'message' => 'Removed from wishlist',
            ]);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'added' => true,
            'message' => 'Added to wishlist',
        ]);
    }
}
