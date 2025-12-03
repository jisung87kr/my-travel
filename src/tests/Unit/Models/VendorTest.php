<?php

namespace Tests\Unit\Models;

use App\Enums\VendorStatus;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_be_created(): void
    {
        $vendor = Vendor::factory()->create([
            'company_name' => 'Test Company',
        ]);

        $this->assertDatabaseHas('vendors', [
            'company_name' => 'Test Company',
        ]);
    }

    public function test_vendor_status_is_cast_to_enum(): void
    {
        $vendor = Vendor::factory()->create([
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(VendorStatus::class, $vendor->status);
        $this->assertEquals(VendorStatus::PENDING, $vendor->status);
    }

    public function test_vendor_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $vendor->user);
        $this->assertEquals($user->id, $vendor->user->id);
    }

    public function test_vendor_is_pending_by_default(): void
    {
        $vendor = Vendor::factory()->create();

        $this->assertTrue($vendor->isPending());
        $this->assertFalse($vendor->isApproved());
    }

    public function test_vendor_can_be_approved(): void
    {
        $vendor = Vendor::factory()->create();

        $vendor->approve();
        $vendor->refresh();

        $this->assertTrue($vendor->isApproved());
        $this->assertFalse($vendor->isPending());
        $this->assertNotNull($vendor->approved_at);
        $this->assertNull($vendor->rejection_reason);
    }

    public function test_vendor_can_be_rejected(): void
    {
        $vendor = Vendor::factory()->create();
        $reason = 'Invalid business registration';

        $vendor->reject($reason);
        $vendor->refresh();

        $this->assertEquals(VendorStatus::REJECTED, $vendor->status);
        $this->assertEquals($reason, $vendor->rejection_reason);
    }

    public function test_vendor_has_products_relationship(): void
    {
        $vendor = Vendor::factory()->approved()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertTrue($vendor->products->contains($product));
    }

    public function test_vendor_soft_deletes(): void
    {
        $vendor = Vendor::factory()->create();
        $vendorId = $vendor->id;

        $vendor->delete();

        $this->assertSoftDeleted('vendors', ['id' => $vendorId]);
    }
}
