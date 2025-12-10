<?php

namespace App\Http\Controllers\Api\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

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

            return Response::success(['added' => false], '위시리스트에서 삭제되었습니다.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return Response::success(['added' => true], '위시리스트에 추가되었습니다.');
    }
}
