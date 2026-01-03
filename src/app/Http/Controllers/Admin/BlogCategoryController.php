<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])
            ->ordered()
            ->get();

        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.blog.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        BlogCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', '카테고리가 생성되었습니다.');
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? $category->slug,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', '카테고리가 수정되었습니다.');
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        if ($category->posts()->count() > 0) {
            return redirect()->back()->with('error', '포스트가 있는 카테고리는 삭제할 수 없습니다.');
        }

        $category->delete();

        return redirect()->route('admin.blog.categories.index')
            ->with('success', '카테고리가 삭제되었습니다.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:blog_categories,id',
        ]);

        foreach ($validated['order'] as $index => $categoryId) {
            BlogCategory::where('id', $categoryId)->update(['sort_order' => $index]);
        }

        return redirect()->back()->with('success', '순서가 변경되었습니다.');
    }
}
