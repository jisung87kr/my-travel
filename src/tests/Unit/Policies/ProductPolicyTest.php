<?php

namespace Tests\Unit\Policies;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ProductPolicy $policy;
    private User $adminUser;
    private User $vendorUser;
    private User $otherVendorUser;
    private User $travelerUser;
    private Vendor $vendor;
    private Vendor $otherVendor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->policy = new ProductPolicy();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);

        $this->otherVendorUser = User::factory()->create();
        $this->otherVendorUser->assignRole('vendor');
        $this->otherVendor = Vendor::factory()->create(['user_id' => $this->otherVendorUser->id]);

        $this->travelerUser = User::factory()->create();
        $this->travelerUser->assignRole('traveler');
    }

    public function test_admin_can_view_any_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->view($this->adminUser, $product));
    }

    public function test_vendor_can_view_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->view($this->vendorUser, $product));
    }

    public function test_vendor_cannot_view_other_vendor_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->otherVendor->id]);

        $this->assertFalse($this->policy->view($this->vendorUser, $product));
    }

    public function test_admin_can_update_any_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->update($this->adminUser, $product));
    }

    public function test_vendor_can_update_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->update($this->vendorUser, $product));
    }

    public function test_vendor_cannot_update_other_vendor_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->otherVendor->id]);

        $this->assertFalse($this->policy->update($this->vendorUser, $product));
    }

    public function test_admin_can_delete_any_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->delete($this->adminUser, $product));
    }

    public function test_vendor_can_delete_own_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->delete($this->vendorUser, $product));
    }

    public function test_vendor_cannot_delete_other_vendor_product(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->otherVendor->id]);

        $this->assertFalse($this->policy->delete($this->vendorUser, $product));
    }

    public function test_only_admin_can_force_delete(): void
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->assertTrue($this->policy->forceDelete($this->adminUser, $product));
        $this->assertFalse($this->policy->forceDelete($this->vendorUser, $product));
    }

    public function test_vendor_can_create_product(): void
    {
        $this->assertTrue($this->policy->create($this->vendorUser));
    }

    public function test_admin_can_create_product(): void
    {
        $this->assertTrue($this->policy->create($this->adminUser));
    }

    public function test_traveler_cannot_create_product(): void
    {
        $this->assertFalse($this->policy->create($this->travelerUser));
    }
}
