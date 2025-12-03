<?php

namespace Tests\Unit\Services;

use App\Enums\BookingType;
use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $service;
    private User $vendorUser;
    private Vendor $vendor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->service = new ProductService();

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);
    }

    public function test_create_product_with_translations_and_prices(): void
    {
        $data = [
            'type' => ProductType::DAY_TOUR->value,
            'region' => Region::SEOUL->value,
            'duration' => 480,
            'min_persons' => 2,
            'max_persons' => 10,
            'booking_type' => BookingType::INSTANT->value,
            'meeting_point' => '서울역',
            'meeting_point_detail' => '1번 출구 앞',
            'translations' => [
                [
                    'locale' => Language::KO->value,
                    'name' => '서울 시티투어',
                    'description' => '서울의 주요 명소를 둘러봅니다.',
                ],
                [
                    'locale' => Language::EN->value,
                    'name' => 'Seoul City Tour',
                    'description' => 'Explore major attractions in Seoul.',
                ],
            ],
            'prices' => [
                [
                    'type' => 'adult',
                    'label' => '성인',
                    'price' => 50000,
                ],
                [
                    'type' => 'child',
                    'label' => '아동',
                    'price' => 30000,
                    'min_age' => 3,
                    'max_age' => 12,
                ],
            ],
        ];

        $product = $this->service->create($data, $this->vendorUser);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'vendor_id' => $this->vendor->id,
            'type' => ProductType::DAY_TOUR->value,
            'region' => Region::SEOUL->value,
            'status' => ProductStatus::DRAFT->value,
        ]);

        $this->assertCount(2, $product->translations);
        $this->assertCount(2, $product->prices);
    }

    public function test_update_product(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $data = [
            'region' => Region::JEJU->value,
            'duration' => 600,
        ];

        $updatedProduct = $this->service->update($product, $data);

        $this->assertEquals(Region::JEJU, $updatedProduct->region);
        $this->assertEquals(600, $updatedProduct->duration);
    }

    public function test_update_product_translations(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => 'Original Name',
        ]);

        $data = [
            'translations' => [
                [
                    'locale' => Language::KO->value,
                    'name' => 'Updated Name',
                    'description' => 'Updated Description',
                ],
            ],
        ];

        $updatedProduct = $this->service->update($product, $data);
        $updatedProduct->load('translations');

        $koTranslation = $updatedProduct->translations->firstWhere('locale', Language::KO);
        $this->assertEquals('Updated Name', $koTranslation->name);
    }

    public function test_update_product_prices_replaces_all(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);
        ProductPrice::factory()->adult()->create([
            'product_id' => $product->id,
            'price' => 50000,
        ]);

        $data = [
            'prices' => [
                [
                    'type' => 'adult',
                    'label' => '성인',
                    'price' => 60000,
                ],
            ],
        ];

        $updatedProduct = $this->service->update($product, $data);
        $updatedProduct->load('prices');

        $this->assertCount(1, $updatedProduct->prices);
        $this->assertEquals(60000, $updatedProduct->prices->first()->price);
    }

    public function test_delete_product(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $result = $this->service->delete($product);

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_upload_images(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');

        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ];

        $images = $this->service->uploadImages($product, $files);

        $this->assertCount(2, $images);
        $this->assertEquals(1, $images[0]->sort_order);
        $this->assertEquals(2, $images[1]->sort_order);
        Storage::disk('public')->assertExists($images[0]->path);
    }

    public function test_reorder_images(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);
        $image1 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 1,
        ]);
        $image2 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 2,
        ]);

        $this->service->reorderImages($product, [$image2->id, $image1->id]);

        $this->assertEquals(1, $image2->fresh()->sort_order);
        $this->assertEquals(2, $image1->fresh()->sort_order);
    }

    public function test_delete_image(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);
        $image = ProductImage::factory()->create([
            'product_id' => $product->id,
            'path' => 'products/1/test.jpg',
        ]);

        Storage::disk('public')->put($image->path, 'test');

        $result = $this->service->deleteImage($image);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($image->path);
    }

    public function test_search_products(): void
    {
        Product::factory()->active()->count(5)->create([
            'region' => Region::SEOUL,
        ]);
        Product::factory()->active()->count(3)->create([
            'region' => Region::JEJU,
        ]);
        Product::factory()->count(2)->create([
            'region' => Region::SEOUL,
        ]); // draft

        $results = $this->service->search(['region' => Region::SEOUL->value]);

        $this->assertEquals(5, $results->total());
    }

    public function test_search_products_by_type(): void
    {
        Product::factory()->active()->dayTour()->count(3)->create();
        Product::factory()->active()->create(['type' => ProductType::ACTIVITY]);

        $results = $this->service->search(['type' => ProductType::DAY_TOUR->value]);

        $this->assertEquals(3, $results->total());
    }

    public function test_search_products_by_keyword(): void
    {
        $product = Product::factory()->active()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => '제주도 투어',
            'description' => '아름다운 제주도',
        ]);

        Product::factory()->active()->count(2)->create();

        $results = $this->service->search(['keyword' => '제주도']);

        $this->assertEquals(1, $results->total());
    }

    public function test_get_vendor_products(): void
    {
        Product::factory()->count(3)->create([
            'vendor_id' => $this->vendor->id,
        ]);
        Product::factory()->count(2)->create(); // other vendor

        $results = $this->service->getVendorProducts($this->vendorUser);

        $this->assertEquals(3, $results->total());
    }

    public function test_get_vendor_products_filter_by_status(): void
    {
        Product::factory()->active()->count(2)->create([
            'vendor_id' => $this->vendor->id,
        ]);
        Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::DRAFT,
        ]);

        $results = $this->service->getVendorProducts($this->vendorUser, [
            'status' => ProductStatus::ACTIVE->value,
        ]);

        $this->assertEquals(2, $results->total());
    }

    public function test_submit_for_review(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::DRAFT,
        ]);

        $updatedProduct = $this->service->submitForReview($product);

        $this->assertEquals(ProductStatus::PENDING, $updatedProduct->status);
    }

    public function test_activate_product(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::PENDING,
        ]);

        $updatedProduct = $this->service->activate($product);

        $this->assertEquals(ProductStatus::ACTIVE, $updatedProduct->status);
    }

    public function test_deactivate_product(): void
    {
        $product = Product::factory()->active()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $updatedProduct = $this->service->deactivate($product);

        $this->assertEquals(ProductStatus::INACTIVE, $updatedProduct->status);
    }
}
