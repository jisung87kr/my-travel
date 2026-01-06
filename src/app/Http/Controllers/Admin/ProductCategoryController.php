<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductCategory::with(['translations', 'parent.translations'])
            ->withCount(['products' => fn ($q) => $q->active(), 'children']);

        // Filter by level
        if ($request->filled('level')) {
            $query->byLevel((int) $request->level);
        }

        // Filter by parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        } else {
            // Default: show main categories
            if (!$request->filled('level')) {
                $query->main();
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhereHas('translations', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        $categories = $query->ordered()->paginate(20)->withQueryString();

        // For parent filter dropdown
        $mainCategories = ProductCategory::main()->active()->ordered()->with('translations')->get();

        return view('admin.product-categories.index', compact('categories', 'mainCategories'));
    }

    public function create(): View
    {
        $parents = ProductCategory::where('level', '<', ProductCategory::LEVEL_DETAIL)
            ->active()
            ->ordered()
            ->with('translations')
            ->get()
            ->groupBy('level');

        return view('admin.product-categories.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:product_categories,slug',
            'parent_id' => 'nullable|exists:product_categories,id',
            'level' => 'required|integer|in:1,2,3',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            // Icon
            'icon_type' => 'nullable|in:svg,image',
            'icon_svg' => 'nullable|string|max:10000',
            'icon_image' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            // Translations
            'translations.ko.name' => 'required|string|max:100',
            'translations.ko.description' => 'nullable|string|max:1000',
            'translations.en.name' => 'nullable|string|max:100',
            'translations.en.description' => 'nullable|string|max:1000',
        ]);

        // Handle icon
        $iconData = $this->processIcon($request, $validated);

        $category = ProductCategory::create([
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'level' => $validated['level'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
            'icon_type' => $iconData['type'],
            'icon' => $iconData['icon'],
        ]);

        // Create translations
        foreach (['ko', 'en'] as $locale) {
            if (!empty($validated['translations'][$locale]['name'])) {
                ProductCategoryTranslation::create([
                    'category_id' => $category->id,
                    'locale' => $locale,
                    'name' => $validated['translations'][$locale]['name'],
                    'description' => $validated['translations'][$locale]['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.product-categories.index')
            ->with('success', '카테고리가 생성되었습니다.');
    }

    public function show(ProductCategory $productCategory): View
    {
        $productCategory->load([
            'translations',
            'parent.translations',
            'children.translations',
        ]);
        $productCategory->loadCount(['products' => fn ($q) => $q->active()]);

        return view('admin.product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory): View
    {
        $productCategory->load('translations');

        $parents = ProductCategory::where('level', '<', ProductCategory::LEVEL_DETAIL)
            ->where('id', '!=', $productCategory->id)
            ->active()
            ->ordered()
            ->with('translations')
            ->get()
            ->groupBy('level');

        return view('admin.product-categories.edit', compact('productCategory', 'parents'));
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:product_categories,slug,' . $productCategory->id,
            'parent_id' => 'nullable|exists:product_categories,id',
            'level' => 'required|integer|in:1,2,3',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            // Icon
            'icon_type' => 'nullable|in:svg,image',
            'icon_svg' => 'nullable|string|max:10000',
            'icon_image' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'remove_icon' => 'nullable|boolean',
            // Translations
            'translations.ko.name' => 'required|string|max:100',
            'translations.ko.description' => 'nullable|string|max:1000',
            'translations.en.name' => 'nullable|string|max:100',
            'translations.en.description' => 'nullable|string|max:1000',
        ]);

        // Handle icon removal
        $iconData = ['type' => $productCategory->icon_type, 'icon' => $productCategory->icon];

        if ($request->boolean('remove_icon')) {
            // Delete old image if exists
            if ($productCategory->icon_type === 'image' && $productCategory->icon) {
                Storage::disk('public')->delete($productCategory->icon);
            }
            $iconData = ['type' => null, 'icon' => null];
        } else {
            // Check for new icon
            $newIconData = $this->processIcon($request, $validated);
            if ($newIconData['type']) {
                // Delete old image if replacing with new one
                if ($productCategory->icon_type === 'image' && $productCategory->icon) {
                    Storage::disk('public')->delete($productCategory->icon);
                }
                $iconData = $newIconData;
            }
        }

        $productCategory->update([
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'level' => $validated['level'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
            'icon_type' => $iconData['type'],
            'icon' => $iconData['icon'],
        ]);

        // Update translations
        foreach (['ko', 'en'] as $locale) {
            $translation = $productCategory->translations()->where('locale', $locale)->first();

            if (!empty($validated['translations'][$locale]['name'])) {
                if ($translation) {
                    $translation->update([
                        'name' => $validated['translations'][$locale]['name'],
                        'description' => $validated['translations'][$locale]['description'] ?? null,
                    ]);
                } else {
                    ProductCategoryTranslation::create([
                        'category_id' => $productCategory->id,
                        'locale' => $locale,
                        'name' => $validated['translations'][$locale]['name'],
                        'description' => $validated['translations'][$locale]['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.product-categories.index')
            ->with('success', '카테고리가 수정되었습니다.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->count() > 0) {
            return redirect()->back()->with('error', '상품이 연결된 카테고리는 삭제할 수 없습니다.');
        }

        if ($productCategory->children()->count() > 0) {
            return redirect()->back()->with('error', '하위 카테고리가 있는 카테고리는 삭제할 수 없습니다.');
        }

        $productCategory->translations()->delete();
        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', '카테고리가 삭제되었습니다.');
    }

    public function toggleActive(ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update(['is_active' => !$productCategory->is_active]);

        $status = $productCategory->is_active ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "카테고리가 {$status}되었습니다.");
    }

    public function toggleFeatured(ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update(['is_featured' => !$productCategory->is_featured]);

        $status = $productCategory->is_featured ? '추천 설정' : '추천 해제';

        return redirect()->back()->with('success', "카테고리가 {$status}되었습니다.");
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:product_categories,id',
        ]);

        foreach ($validated['order'] as $index => $categoryId) {
            ProductCategory::where('id', $categoryId)->update(['sort_order' => $index]);
        }

        return redirect()->back()->with('success', '순서가 변경되었습니다.');
    }

    /**
     * Process icon from request (SVG or image upload)
     */
    private function processIcon(Request $request, array $validated): array
    {
        $iconType = $validated['icon_type'] ?? 'svg';

        // SVG type
        if ($iconType === 'svg' && !empty($validated['icon_svg'])) {
            $svg = trim($validated['icon_svg']);
            // Basic SVG validation
            if (preg_match('/<svg[\s\S]*<\/svg>/i', $svg)) {
                return ['type' => 'svg', 'icon' => $svg];
            }
        }

        // Image type
        if ($iconType === 'image' && $request->hasFile('icon_image')) {
            $file = $request->file('icon_image');
            $path = $file->store('product-categories/icons', 'public');
            return ['type' => 'image', 'icon' => $path];
        }

        return ['type' => null, 'icon' => null];
    }
}
