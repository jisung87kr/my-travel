<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\RegionTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Region::with(['translations', 'parent.translations'])
            ->withCount(['products' => fn ($q) => $q->active(), 'children']);

        // Filter by level
        if ($request->filled('level')) {
            $query->byLevel((int) $request->level);
        }

        // Filter by parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        } else {
            // Default: show provinces (level 2)
            if (!$request->filled('level')) {
                $query->provinces();
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('translations', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        $regions = $query->ordered()->paginate(20)->withQueryString();

        // For parent filter dropdown
        $provinces = Region::provinces()->active()->ordered()->with('translations')->get();

        return view('admin.regions.index', compact('regions', 'provinces'));
    }

    public function create(): View
    {
        $parents = Region::where('level', '<', Region::LEVEL_DISTRICT)
            ->active()
            ->ordered()
            ->with('translations')
            ->get()
            ->groupBy('level');

        return view('admin.regions.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:regions,code',
            'parent_id' => 'nullable|exists:regions,id',
            'level' => 'required|integer|in:1,2,3',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'timezone' => 'nullable|string|max:50',
            // Translations
            'translations.ko.name' => 'required|string|max:100',
            'translations.ko.short_name' => 'nullable|string|max:50',
            'translations.ko.description' => 'nullable|string|max:1000',
            'translations.en.name' => 'nullable|string|max:100',
            'translations.en.short_name' => 'nullable|string|max:50',
            'translations.en.description' => 'nullable|string|max:1000',
        ]);

        $region = Region::create([
            'code' => $validated['code'],
            'parent_id' => $validated['parent_id'] ?? null,
            'level' => $validated['level'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
            'timezone' => $validated['timezone'] ?? 'Asia/Seoul',
        ]);

        // Create translations
        foreach (['ko', 'en'] as $locale) {
            if (!empty($validated['translations'][$locale]['name'])) {
                RegionTranslation::create([
                    'region_id' => $region->id,
                    'locale' => $locale,
                    'name' => $validated['translations'][$locale]['name'],
                    'short_name' => $validated['translations'][$locale]['short_name'] ?? null,
                    'description' => $validated['translations'][$locale]['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.regions.index')
            ->with('success', '지역이 생성되었습니다.');
    }

    public function show(Region $region): View
    {
        $region->load([
            'translations',
            'images',
            'parent.translations',
            'children.translations',
        ]);
        $region->loadCount(['products' => fn ($q) => $q->active()]);

        return view('admin.regions.show', compact('region'));
    }

    public function edit(Region $region): View
    {
        $region->load('translations');

        $parents = Region::where('level', '<', Region::LEVEL_DISTRICT)
            ->where('id', '!=', $region->id)
            ->active()
            ->ordered()
            ->with('translations')
            ->get()
            ->groupBy('level');

        return view('admin.regions.edit', compact('region', 'parents'));
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:regions,code,' . $region->id,
            'parent_id' => 'nullable|exists:regions,id',
            'level' => 'required|integer|in:1,2,3',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'timezone' => 'nullable|string|max:50',
            // Translations
            'translations.ko.name' => 'required|string|max:100',
            'translations.ko.short_name' => 'nullable|string|max:50',
            'translations.ko.description' => 'nullable|string|max:1000',
            'translations.en.name' => 'nullable|string|max:100',
            'translations.en.short_name' => 'nullable|string|max:50',
            'translations.en.description' => 'nullable|string|max:1000',
        ]);

        $region->update([
            'code' => $validated['code'],
            'parent_id' => $validated['parent_id'] ?? null,
            'level' => $validated['level'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
            'timezone' => $validated['timezone'] ?? 'Asia/Seoul',
        ]);

        // Update translations
        foreach (['ko', 'en'] as $locale) {
            $translation = $region->translations()->where('locale', $locale)->first();

            if (!empty($validated['translations'][$locale]['name'])) {
                if ($translation) {
                    $translation->update([
                        'name' => $validated['translations'][$locale]['name'],
                        'short_name' => $validated['translations'][$locale]['short_name'] ?? null,
                        'description' => $validated['translations'][$locale]['description'] ?? null,
                    ]);
                } else {
                    RegionTranslation::create([
                        'region_id' => $region->id,
                        'locale' => $locale,
                        'name' => $validated['translations'][$locale]['name'],
                        'short_name' => $validated['translations'][$locale]['short_name'] ?? null,
                        'description' => $validated['translations'][$locale]['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.regions.index')
            ->with('success', '지역이 수정되었습니다.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        if ($region->products()->count() > 0) {
            return redirect()->back()->with('error', '상품이 연결된 지역은 삭제할 수 없습니다.');
        }

        if ($region->children()->count() > 0) {
            return redirect()->back()->with('error', '하위 지역이 있는 지역은 삭제할 수 없습니다.');
        }

        $region->translations()->delete();
        $region->images()->delete();
        $region->delete();

        return redirect()->route('admin.regions.index')
            ->with('success', '지역이 삭제되었습니다.');
    }

    public function toggleActive(Region $region): RedirectResponse
    {
        $region->update(['is_active' => !$region->is_active]);

        $status = $region->is_active ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "지역이 {$status}되었습니다.");
    }

    public function toggleFeatured(Region $region): RedirectResponse
    {
        $region->update(['is_featured' => !$region->is_featured]);

        $status = $region->is_featured ? '추천 설정' : '추천 해제';

        return redirect()->back()->with('success', "지역이 {$status}되었습니다.");
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:regions,id',
        ]);

        foreach ($validated['order'] as $index => $regionId) {
            Region::where('id', $regionId)->update(['sort_order' => $index]);
        }

        return redirect()->back()->with('success', '순서가 변경되었습니다.');
    }
}
