<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\Vendor;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventoryService $service;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->service = new InventoryService();

        $vendor = Vendor::factory()->create();
        $this->product = Product::factory()->active()->create([
            'vendor_id' => $vendor->id,
        ]);
    }

    public function test_check_availability_returns_schedule(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 10,
            'is_active' => true,
        ]);

        $result = $this->service->checkAvailability(
            $this->product->id,
            today()->addDay()->toDateString(),
            5
        );

        $this->assertNotNull($result);
        $this->assertEquals($schedule->id, $result->id);
    }

    public function test_check_availability_returns_null_when_insufficient(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 3,
            'is_active' => true,
        ]);

        $result = $this->service->checkAvailability(
            $this->product->id,
            today()->addDay()->toDateString(),
            5
        );

        $this->assertNull($result);
    }

    public function test_check_availability_returns_null_for_inactive(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 10,
            'is_active' => false,
        ]);

        $result = $this->service->checkAvailability(
            $this->product->id,
            today()->addDay()->toDateString(),
            5
        );

        $this->assertNull($result);
    }

    public function test_set_daily_capacity_creates_new_schedule(): void
    {
        $schedule = $this->service->setDailyCapacity(
            $this->product->id,
            today()->addDay()->toDateString(),
            20,
            '09:00'
        );

        $this->assertDatabaseHas('product_schedules', [
            'id' => $schedule->id,
            'product_id' => $this->product->id,
            'total_count' => 20,
            'available_count' => 20,
            'is_active' => true,
        ]);
    }

    public function test_set_daily_capacity_updates_existing(): void
    {
        $existing = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 5,
        ]);

        $schedule = $this->service->setDailyCapacity(
            $this->product->id,
            today()->addDay()->toDateString(),
            30
        );

        $this->assertEquals($existing->id, $schedule->id);
        $this->assertEquals(30, $schedule->total_count);
        $this->assertEquals(30, $schedule->available_count);
    }

    public function test_update_capacity_adjusts_available(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 5, // 5 booked
        ]);

        $updated = $this->service->updateCapacity($schedule, 15);

        $this->assertEquals(15, $updated->total_count);
        $this->assertEquals(10, $updated->available_count); // 15 - 5 booked
    }

    public function test_update_capacity_caps_available_at_zero(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 2, // 8 booked
        ]);

        $updated = $this->service->updateCapacity($schedule, 5);

        $this->assertEquals(5, $updated->total_count);
        $this->assertEquals(0, $updated->available_count); // can't be negative
    }

    public function test_close_date(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'is_active' => true,
        ]);

        $result = $this->service->closeDate(
            $this->product->id,
            today()->addDay()->toDateString()
        );

        $this->assertTrue($result);

        $schedule = ProductSchedule::where('product_id', $this->product->id)
            ->whereDate('date', today()->addDay())
            ->first();
        $this->assertNotNull($schedule);
        $this->assertFalse($schedule->is_active);
    }

    public function test_open_date(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'is_active' => false,
        ]);

        $result = $this->service->openDate(
            $this->product->id,
            today()->addDay()->toDateString()
        );

        $this->assertTrue($result);

        $schedule = ProductSchedule::where('product_id', $this->product->id)
            ->whereDate('date', today()->addDay())
            ->first();
        $this->assertNotNull($schedule);
        $this->assertTrue($schedule->is_active);
    }

    public function test_get_schedules_for_product(): void
    {
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDays(1),
        ]);
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDays(5),
        ]);
        ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDays(10),
        ]);

        $schedules = $this->service->getSchedulesForProduct(
            $this->product->id,
            today()->addDays(1)->toDateString(),
            today()->addDays(7)->toDateString()
        );

        $this->assertCount(2, $schedules);
    }

    public function test_bulk_create_schedules(): void
    {
        $dates = [
            today()->addDays(1)->toDateString(),
            today()->addDays(2)->toDateString(),
            today()->addDays(3)->toDateString(),
        ];

        $schedules = $this->service->bulkCreateSchedules(
            $this->product->id,
            $dates,
            20,
            '10:00'
        );

        $this->assertCount(3, $schedules);

        foreach ($dates as $date) {
            $schedule = ProductSchedule::where('product_id', $this->product->id)
                ->whereDate('date', $date)
                ->first();
            $this->assertNotNull($schedule);
            $this->assertEquals(20, $schedule->total_count);
        }
    }
}
