<?php

namespace Tests\Feature\Products;

use App\Enums\BookingType;
use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VendorProductTest extends TestCase
{
    use RefreshDatabase;

    private User $vendorUser;
    private Vendor $vendor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);
    }

    public function test_vendor_can_list_own_products(): void
    {
        Product::factory()->count(3)->create(['vendor_id' => $this->vendor->id]);
        Product::factory()->count(2)->create(); // other vendor

        $response = $this->actingAs($this->vendorUser)
            ->getJson('/vendor/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_vendor_can_create_product(): void
    {
        $data = [
            'type' => ProductType::DAY_TOUR->value,
            'region' => Region::SEOUL->value,
            'duration' => 480,
            'min_persons' => 2,
            'max_persons' => 10,
            'booking_type' => BookingType::INSTANT->value,
            'translations' => [
                [
                    'locale' => Language::KO->value,
                    'name' => '서울 시티투어',
                    'description' => '서울의 주요 명소를 둘러봅니다.',
                ],
            ],
            'prices' => [
                [
                    'type' => 'adult',
                    'label' => '성인',
                    'price' => 50000,
                ],
            ],
        ];

        $response = $this->actingAs($this->vendorUser)
            ->postJson('/vendor/products', $data);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', ProductStatus::DRAFT->value);

        $this->assertDatabaseHas('products', [
            'vendor_id' => $this->vendor->id,
            'type' => ProductType::DAY_TOUR->value,
        ]);
    }

    public function test_vendor_can_view_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/vendor/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_vendor_cannot_view_other_vendor_product(): void
    {
        $otherVendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/vendor/products/{$product->id}");

        $response->assertStatus(403);
    }

    public function test_vendor_can_update_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->putJson("/vendor/products/{$product->id}", [
                'region' => Region::JEJU->value,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'region' => Region::JEJU->value,
        ]);
    }

    public function test_vendor_cannot_update_other_vendor_product(): void
    {
        $otherVendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->putJson("/vendor/products/{$product->id}", [
                'region' => Region::JEJU->value,
            ]);

        $response->assertStatus(403);
    }

    public function test_vendor_can_delete_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->deleteJson("/vendor/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_vendor_cannot_delete_other_vendor_product(): void
    {
        $otherVendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->deleteJson("/vendor/products/{$product->id}");

        $response->assertStatus(403);
    }

    public function test_vendor_can_upload_images(): void
    {
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library is not available');
        }

        Storage::fake('public');

        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$product->id}/images", [
                'images' => [
                    UploadedFile::fake()->image('test1.jpg'),
                    UploadedFile::fake()->image('test2.jpg'),
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data');
    }

    public function test_vendor_can_reorder_images(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
        $image1 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 1,
        ]);
        $image2 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->putJson("/vendor/products/{$product->id}/images/reorder", [
                'order' => [$image2->id, $image1->id],
            ]);

        $response->assertStatus(200);

        $this->assertEquals(1, $image2->fresh()->sort_order);
        $this->assertEquals(2, $image1->fresh()->sort_order);
    }

    public function test_vendor_can_delete_image(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
        $image = ProductImage::factory()->create([
            'product_id' => $product->id,
            'path' => 'products/1/test.jpg',
        ]);
        Storage::disk('public')->put($image->path, 'test');

        $response = $this->actingAs($this->vendorUser)
            ->deleteJson("/vendor/products/{$product->id}/images/{$image->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    }

    public function test_vendor_can_submit_product_for_review(): void
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$product->id}/submit");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', ProductStatus::PENDING->value);
    }

    public function test_vendor_can_deactivate_active_product(): void
    {
        $product = Product::factory()->active()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$product->id}/deactivate");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', ProductStatus::INACTIVE->value);
    }

    public function test_unauthenticated_user_cannot_access_vendor_products(): void
    {
        $response = $this->getJson('/vendor/products');

        $response->assertStatus(401);
    }

    public function test_non_vendor_user_cannot_access_vendor_products(): void
    {
        $traveler = User::factory()->create();
        $traveler->assignRole('traveler');

        $response = $this->actingAs($traveler)
            ->getJson('/vendor/products');

        $response->assertStatus(403);
    }

    public function test_validation_error_on_invalid_product_data(): void
    {
        $response = $this->actingAs($this->vendorUser)
            ->postJson('/vendor/products', [
                'type' => 'invalid_type',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'region', 'min_persons', 'max_persons', 'booking_type', 'translations', 'prices']);
    }
}
