<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_can_be_created(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(User::class, $wishlist->user);
        $this->assertEquals($user->id, $wishlist->user->id);
    }

    public function test_wishlist_belongs_to_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(Product::class, $wishlist->product);
        $this->assertEquals($product->id, $wishlist->product->id);
    }

    public function test_wishlist_toggle_adds_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $result = Wishlist::toggle($user->id, $product->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_toggle_removes_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $result = Wishlist::toggle($user->id, $product->id);

        $this->assertFalse($result);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_is_wishlisted_returns_true_when_exists(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertTrue(Wishlist::isWishlisted($user->id, $product->id));
    }

    public function test_is_wishlisted_returns_false_when_not_exists(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->assertFalse(Wishlist::isWishlisted($user->id, $product->id));
    }

    public function test_user_product_combination_is_unique(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
