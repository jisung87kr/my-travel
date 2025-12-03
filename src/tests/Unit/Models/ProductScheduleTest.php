<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\ProductSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_can_be_created(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'date' => '2025-01-15',
            'total_count' => 10,
            'available_count' => 10,
        ]);

        $this->assertDatabaseHas('product_schedules', [
            'id' => $schedule->id,
            'total_count' => 10,
        ]);

        $this->assertEquals('2025-01-15', $schedule->date->format('Y-m-d'));
    }

    public function test_schedule_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $schedule = ProductSchedule::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $schedule->product);
        $this->assertEquals($product->id, $schedule->product->id);
    }

    public function test_schedule_is_available_check(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'available_count' => 5,
            'is_active' => true,
        ]);

        $this->assertTrue($schedule->isAvailable(1));
        $this->assertTrue($schedule->isAvailable(5));
        $this->assertFalse($schedule->isAvailable(6));
    }

    public function test_inactive_schedule_is_not_available(): void
    {
        $schedule = ProductSchedule::factory()->inactive()->create([
            'available_count' => 10,
        ]);

        $this->assertFalse($schedule->isAvailable(1));
    }

    public function test_schedule_decrease_stock(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'total_count' => 10,
            'available_count' => 10,
        ]);

        $result = $schedule->decreaseStock(3);

        $this->assertTrue($result);
        $this->assertEquals(7, $schedule->fresh()->available_count);
    }

    public function test_schedule_decrease_stock_fails_when_insufficient(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'total_count' => 10,
            'available_count' => 2,
        ]);

        $result = $schedule->decreaseStock(5);

        $this->assertFalse($result);
        $this->assertEquals(2, $schedule->fresh()->available_count);
    }

    public function test_schedule_increase_stock(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'total_count' => 10,
            'available_count' => 5,
        ]);

        $schedule->increaseStock(3);

        $this->assertEquals(8, $schedule->fresh()->available_count);
    }

    public function test_schedule_increase_stock_caps_at_total(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'total_count' => 10,
            'available_count' => 8,
        ]);

        $schedule->increaseStock(5);

        $this->assertEquals(10, $schedule->fresh()->available_count);
    }

    public function test_schedule_scope_active(): void
    {
        ProductSchedule::factory()->count(3)->create(['is_active' => true]);
        ProductSchedule::factory()->inactive()->count(2)->create();

        $activeSchedules = ProductSchedule::active()->get();

        $this->assertCount(3, $activeSchedules);
    }

    public function test_schedule_scope_available(): void
    {
        ProductSchedule::factory()->create(['available_count' => 5, 'is_active' => true]);
        ProductSchedule::factory()->soldOut()->create(['is_active' => true]);
        ProductSchedule::factory()->create(['available_count' => 3, 'is_active' => false]);

        $availableSchedules = ProductSchedule::available()->get();

        $this->assertCount(1, $availableSchedules);
    }

    public function test_schedule_scope_future(): void
    {
        ProductSchedule::factory()->forDate(now()->addDays(5)->format('Y-m-d'))->create();
        ProductSchedule::factory()->forDate(now()->subDays(5)->format('Y-m-d'))->create();

        $futureSchedules = ProductSchedule::future()->get();

        $this->assertCount(1, $futureSchedules);
    }
}
