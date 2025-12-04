<?php

namespace Tests\Feature\Bookings;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private User $vendorUser;
    private Vendor $vendor;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);

        $this->product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::ACTIVE,
        ]);
    }

    public function test_vendor_can_list_schedules(): void
    {
        foreach (range(1, 5) as $i) {
            ProductSchedule::factory()->create([
                'product_id' => $this->product->id,
                'date' => today()->addDays($i),
            ]);
        }

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/vendor/products/{$this->product->id}/schedules");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_vendor_can_create_schedule(): void
    {
        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$this->product->id}/schedules", [
                'date' => today()->addDay()->toDateString(),
                'total_count' => 20,
                'start_time' => '09:00',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $schedule = ProductSchedule::where('product_id', $this->product->id)
            ->whereDate('date', today()->addDay())
            ->first();
        $this->assertNotNull($schedule);
        $this->assertEquals(20, $schedule->total_count);
    }

    public function test_vendor_can_update_schedules(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->putJson("/vendor/products/{$this->product->id}/schedules", [
                'schedules' => [
                    [
                        'id' => $schedule->id,
                        'total_count' => 30,
                    ],
                ],
            ]);

        $response->assertStatus(200);
        $this->assertEquals(30, $schedule->fresh()->total_count);
    }

    public function test_vendor_can_bulk_create_schedules(): void
    {
        $dates = [
            today()->addDays(1)->toDateString(),
            today()->addDays(2)->toDateString(),
            today()->addDays(3)->toDateString(),
        ];

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$this->product->id}/schedules/bulk", [
                'dates' => $dates,
                'total_count' => 15,
                'start_time' => '10:00',
            ]);

        $response->assertStatus(201);

        foreach ($dates as $date) {
            $schedule = ProductSchedule::where('product_id', $this->product->id)
                ->whereDate('date', $date)
                ->first();
            $this->assertNotNull($schedule);
            $this->assertEquals(15, $schedule->total_count);
        }
    }

    public function test_vendor_can_close_date(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$this->product->id}/schedules/close", [
                'date' => today()->addDay()->toDateString(),
            ]);

        $response->assertStatus(200);

        $schedule = ProductSchedule::where('product_id', $this->product->id)
            ->whereDate('date', today()->addDay())
            ->first();
        $this->assertNotNull($schedule);
        $this->assertFalse($schedule->is_active);
    }

    public function test_vendor_can_open_date(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$this->product->id}/schedules/open", [
                'date' => today()->addDay()->toDateString(),
            ]);

        $response->assertStatus(200);

        $schedule = ProductSchedule::where('product_id', $this->product->id)
            ->whereDate('date', today()->addDay())
            ->first();
        $this->assertNotNull($schedule);
        $this->assertTrue($schedule->is_active);
    }

    public function test_vendor_cannot_manage_other_vendor_product_schedules(): void
    {
        $otherVendor = Vendor::factory()->create();
        $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$otherProduct->id}/schedules", [
                'date' => today()->addDay()->toDateString(),
                'total_count' => 20,
            ]);

        $response->assertStatus(403);
    }

    public function test_cannot_create_schedule_for_past_date(): void
    {
        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/products/{$this->product->id}/schedules", [
                'date' => today()->subDay()->toDateString(),
                'total_count' => 20,
            ]);

        $response->assertStatus(422);
    }
}
