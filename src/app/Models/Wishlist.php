<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function toggle(int $userId, int $productId): bool
    {
        $wishlist = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return false;
        }

        self::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return true;
    }

    public static function isWishlisted(int $userId, int $productId): bool
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }
}
