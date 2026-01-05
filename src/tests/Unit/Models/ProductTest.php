<?php

namespace Tests\Unit\Models;

use App\Enums\BookingType;
use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Region;
use App\Models\ProductSchedule;
use App\Models\ProductTranslation;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RegionSeeder::class);
    }

    public function test_product_can_be_created(): void
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    public function test_product_type_is_cast_to_enum(): void
    {
        $product = Product::factory()->dayTour()->create();

        $this->assertInstanceOf(ProductType::class, $product->type);
        $this->assertEquals(ProductType::DAY_TOUR, $product->type);
    }

    public function test_product_has_region_relationship(): void
    {
        $jejuRegion = Region::where('code', 'jeju')->first();
        $product = Product::factory()->create([
            'region_id' => $jejuRegion->id,
        ]);

        $this->assertInstanceOf(Region::class, $product->regionRelation);
        $this->assertEquals($jejuRegion->id, $product->region_id);
    }

    public function test_product_belongs_to_vendor(): void
    {
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertInstanceOf(Vendor::class, $product->vendor);
        $this->assertEquals($vendor->id, $product->vendor->id);
    }

    public function test_product_has_translations(): void
    {
        $product = Product::factory()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => 'Korean Name',
        ]);
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::EN,
            'name' => 'English Name',
        ]);

        $this->assertCount(2, $product->translations);
    }

    public function test_product_get_translation_returns_correct_locale(): void
    {
        $product = Product::factory()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => 'Korean Name',
        ]);
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::EN,
            'name' => 'English Name',
        ]);

        $product->load('translations');

        $this->assertEquals('Korean Name', $product->getTranslation('ko')->name);
        $this->assertEquals('English Name', $product->getTranslation('en')->name);
    }

    public function test_product_get_translation_falls_back_to_default(): void
    {
        $product = Product::factory()->create();
        ProductTranslation::factory()->create([
            'product_id' => $product->id,
            'locale' => Language::KO,
            'name' => 'Korean Name',
        ]);

        $product->load('translations');

        // Request Japanese, but fall back to Korean (default)
        $translation = $product->getTranslation('ja');
        $this->assertEquals('Korean Name', $translation->name);
    }

    public function test_product_is_active_check(): void
    {
        $activeProduct = Product::factory()->active()->create();
        $draftProduct = Product::factory()->create();

        $this->assertTrue($activeProduct->isActive());
        $this->assertFalse($draftProduct->isActive());
    }

    public function test_product_is_instant_booking_check(): void
    {
        $instantProduct = Product::factory()->instant()->create();
        $requestProduct = Product::factory()->request()->create();

        $this->assertTrue($instantProduct->isInstantBooking());
        $this->assertFalse($requestProduct->isInstantBooking());
    }

    public function test_product_scope_active(): void
    {
        Product::factory()->active()->count(3)->create();
        Product::factory()->count(2)->create(); // draft

        $activeProducts = Product::active()->get();

        $this->assertCount(3, $activeProducts);
    }

    public function test_product_scope_by_region(): void
    {
        $jejuRegion = Region::where('code', 'jeju')->first();
        $seoulRegion = Region::where('code', 'seoul')->first();

        Product::factory()->create(['region_id' => $jejuRegion->id]);
        Product::factory()->create(['region_id' => $jejuRegion->id]);
        Product::factory()->create(['region_id' => $seoulRegion->id]);

        $jejuProducts = Product::byRegion($jejuRegion->id)->get();

        $this->assertCount(2, $jejuProducts);
    }

    public function test_product_scope_by_type(): void
    {
        Product::factory()->dayTour()->count(2)->create();
        Product::factory()->create(['type' => ProductType::ACTIVITY]);

        $dayTours = Product::byType(ProductType::DAY_TOUR)->get();

        $this->assertCount(2, $dayTours);
    }

    public function test_product_soft_deletes(): void
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $productId]);
    }
}
