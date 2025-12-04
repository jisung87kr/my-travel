<?php

namespace App\Services;

use App\Models\ProductSchedule;
use Illuminate\Support\Collection;

class InventoryService
{
    public function checkAvailability(int $productId, string $date, int $persons): ?ProductSchedule
    {
        return ProductSchedule::where('product_id', $productId)
            ->whereDate('date', $date)
            ->where('is_active', true)
            ->where('available_count', '>=', $persons)
            ->lockForUpdate()
            ->first();
    }

    public function getSchedule(int $productId, string $date): ?ProductSchedule
    {
        return ProductSchedule::where('product_id', $productId)
            ->whereDate('date', $date)
            ->first();
    }

    public function decreaseStock(ProductSchedule $schedule, int $count): bool
    {
        return $schedule->decreaseStock($count);
    }

    public function increaseStock(ProductSchedule $schedule, int $count): void
    {
        $schedule->increaseStock($count);
    }

    public function setDailyCapacity(int $productId, string $date, int $totalCount, ?string $startTime = null): ProductSchedule
    {
        $schedule = ProductSchedule::where('product_id', $productId)
            ->whereDate('date', $date)
            ->first();

        if ($schedule) {
            $schedule->update([
                'total_count' => $totalCount,
                'available_count' => $totalCount,
                'start_time' => $startTime,
                'is_active' => true,
            ]);
            return $schedule;
        }

        return ProductSchedule::create([
            'product_id' => $productId,
            'date' => $date,
            'total_count' => $totalCount,
            'available_count' => $totalCount,
            'start_time' => $startTime,
            'is_active' => true,
        ]);
    }

    public function updateCapacity(ProductSchedule $schedule, int $totalCount): ProductSchedule
    {
        $bookedCount = $schedule->total_count - $schedule->available_count;
        $newAvailableCount = max(0, $totalCount - $bookedCount);

        $schedule->update([
            'total_count' => $totalCount,
            'available_count' => $newAvailableCount,
        ]);

        return $schedule;
    }

    public function closeDate(int $productId, string $date): bool
    {
        return ProductSchedule::where('product_id', $productId)
            ->whereDate('date', $date)
            ->update(['is_active' => false]) > 0;
    }

    public function openDate(int $productId, string $date): bool
    {
        return ProductSchedule::where('product_id', $productId)
            ->whereDate('date', $date)
            ->update(['is_active' => true]) > 0;
    }

    public function getSchedulesForProduct(int $productId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = ProductSchedule::where('product_id', $productId);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        return $query->orderBy('date')->get();
    }

    public function bulkCreateSchedules(int $productId, array $dates, int $totalCount, ?string $startTime = null): Collection
    {
        $schedules = collect();

        foreach ($dates as $date) {
            $schedule = $this->setDailyCapacity($productId, $date, $totalCount, $startTime);
            $schedules->push($schedule);
        }

        return $schedules;
    }
}
