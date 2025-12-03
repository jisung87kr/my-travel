<?php

namespace Tests\Feature\Products;

use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_can_list_active_products(): void
    {
        Product::factory()->active()->count(3)->create();
        Product::factory()->count(2)->create(); // draft

        $response = $this->getJson('/products');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_products_by_region(): void
    {
        Product::factory()->active()->count(2)->create(['region' => Region::SEOUL]);
        Product::factory()->active()->count(3)->create(['region' => Region::JEJU]);

        $response = $this->getJson('/products?region=seoul');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_filter_products_by_type(): void
    {
        Product::factory()->active()->dayTour()->count(2)->create();
        Product::factory()->active()->create(['type' => ProductType::ACTIVITY]);

        $response = $this->getJson('/products?type=day_tour');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_search_products_by_keyword(): void
    {
        $product = Product::factory()->active()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => '서울 시티투어',
            'description' => '서울 관광',
        ]);

        $otherProduct = Product::factory()->active()->create();
        ProductTranslation::factory()->create([
            'product_id' => $otherProduct->id,
            'locale' => Language::KO,
            'name' => '부산 투어',
            'description' => '부산 관광',
        ]);

        $response = $this->getJson('/products?keyword=서울');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_view_single_active_product(): void
    {
        $product = Product::factory()->active()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => 'Test Product',
        ]);
        ProductPrice::factory()->create([
            'product_id' => $product->id,
            'type' => 'adult',
            'price' => 50000,
        ]);

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_cannot_view_inactive_product(): void
    {
        $product = Product::factory()->create(['status' => ProductStatus::DRAFT]);

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_products_include_translations_and_prices(): void
    {
        $product = Product::factory()->active()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
        ]);
        ProductPrice::factory()->create([
            'product_id' => $product->id,
        ]);

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'type',
                    'region',
                    'translations',
                    'prices',
                ],
            ]);
    }

    public function test_pagination_works(): void
    {
        Product::factory()->active()->count(25)->create();

        $response = $this->getJson('/products?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 25)
            ->assertJsonPath('meta.per_page', 10);
    }

    public function test_max_per_page_is_limited(): void
    {
        Product::factory()->active()->count(150)->create();

        $response = $this->getJson('/products?per_page=200');

        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 100);
    }
}
