<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $products = $vendor ? $vendor->products()
            ->with('translations')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->getTranslation('ko')?->name ?? '상품',
            ]) : collect();

        return view('vendor.schedules.index', compact('products'));
    }
}
