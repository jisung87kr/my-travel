<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\UpdateScheduleRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService
    ) {}

    // Web View Method
    public function indexView(Request $request): View
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $products = $vendor ? $vendor->products()
            ->with('translations')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->getTranslation('ko')?->title ?? '상품',
            ]) : collect();

        return view('vendor.schedules.index', compact('products'));
    }

    public function index(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('view', $product);

        $startDate = $request->get('start_date', today()->toDateString());
        $endDate = $request->get('end_date', today()->addMonths(3)->toDateString());

        $schedules = $this->inventoryService->getSchedulesForProduct(
            $product->id,
            $startDate,
            $endDate
        );

        // Get bookings for calendar display
        $bookings = Booking::with(['user', 'schedule'])
            ->where('product_id', $product->id)
            ->whereHas('schedule', fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->get();

        return ApiResponse::success([
            'schedules' => $schedules,
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'total_count' => 'required|integer|min:1|max:1000',
            'start_time' => 'nullable|date_format:H:i',
        ]);

        $schedule = $this->inventoryService->setDailyCapacity(
            $product->id,
            $request->date,
            $request->total_count,
            $request->start_time
        );

        return ApiResponse::created($schedule, '일정이 등록되었습니다.');
    }

    public function update(UpdateScheduleRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $schedules = collect();

        foreach ($request->schedules as $scheduleData) {
            if (isset($scheduleData['id'])) {
                $schedule = ProductSchedule::find($scheduleData['id']);
                if ($schedule && $schedule->product_id === $product->id) {
                    $this->inventoryService->updateCapacity($schedule, $scheduleData['total_count']);
                    if (isset($scheduleData['is_active'])) {
                        $schedule->update(['is_active' => $scheduleData['is_active']]);
                    }
                    $schedules->push($schedule->fresh());
                }
            } else {
                $schedule = $this->inventoryService->setDailyCapacity(
                    $product->id,
                    $scheduleData['date'],
                    $scheduleData['total_count'],
                    $scheduleData['start_time'] ?? null
                );
                $schedules->push($schedule);
            }
        }

        return ApiResponse::success($schedules, '일정이 업데이트되었습니다.');
    }

    public function bulkCreate(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date|after_or_equal:today',
            'total_count' => 'required|integer|min:1|max:1000',
            'start_time' => 'nullable|date_format:H:i',
        ]);

        $schedules = $this->inventoryService->bulkCreateSchedules(
            $product->id,
            $request->dates,
            $request->total_count,
            $request->start_time
        );

        return ApiResponse::created($schedules, '일정이 일괄 등록되었습니다.');
    }

    public function close(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'date' => 'required|date',
        ]);

        $this->inventoryService->closeDate($product->id, $request->date);

        return ApiResponse::success(null, '해당 날짜가 마감되었습니다.');
    }

    public function open(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'date' => 'required|date',
        ]);

        $this->inventoryService->openDate($product->id, $request->date);

        return ApiResponse::success(null, '해당 날짜가 오픈되었습니다.');
    }
}
