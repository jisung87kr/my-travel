<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogSeries;
use App\Services\BlogService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogSeriesController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService,
        private readonly ImageService $imageService
    ) {}

    public function index(): View
    {
        $series = BlogSeries::withCount(['posts', 'posts as published_posts_count' => fn ($q) => $q->published()])
            ->latest()
            ->get();

        return view('admin.blog.series.index', compact('series'));
    }

    public function create(): View
    {
        return view('admin.blog.series.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_series,slug',
            'description' => 'nullable|string|max:1000',
            'thumbnail' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->imageService->store($request->file('thumbnail'), 'blog/series');
        }

        BlogSeries::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.blog.series.index')
            ->with('success', '시리즈가 생성되었습니다.');
    }

    public function show(BlogSeries $series): View
    {
        $series->load(['posts' => fn ($q) => $q->orderBy('series_order')->with(['author', 'category'])]);

        return view('admin.blog.series.show', compact('series'));
    }

    public function edit(BlogSeries $series): View
    {
        return view('admin.blog.series.edit', compact('series'));
    }

    public function update(Request $request, BlogSeries $series): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_series,slug,' . $series->id,
            'description' => 'nullable|string|max:1000',
            'thumbnail' => 'nullable|image|max:5120',
            'remove_thumbnail' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $series->slug,
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($series->thumbnail_path) {
                $this->imageService->delete($series->thumbnail_path);
            }
            $updateData['thumbnail_path'] = $this->imageService->store($request->file('thumbnail'), 'blog/series');
        } elseif ($request->boolean('remove_thumbnail') && $series->thumbnail_path) {
            $this->imageService->delete($series->thumbnail_path);
            $updateData['thumbnail_path'] = null;
        }

        $series->update($updateData);

        return redirect()->route('admin.blog.series.index')
            ->with('success', '시리즈가 수정되었습니다.');
    }

    public function destroy(BlogSeries $series): RedirectResponse
    {
        if ($series->posts()->count() > 0) {
            return redirect()->back()->with('error', '포스트가 있는 시리즈는 삭제할 수 없습니다.');
        }

        if ($series->thumbnail_path) {
            $this->imageService->delete($series->thumbnail_path);
        }

        $series->delete();

        return redirect()->route('admin.blog.series.index')
            ->with('success', '시리즈가 삭제되었습니다.');
    }

    public function reorderPosts(Request $request, BlogSeries $series): RedirectResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:blog_posts,id',
        ]);

        $this->blogService->reorderSeriesPosts($series, $validated['order']);

        return redirect()->back()->with('success', '포스트 순서가 변경되었습니다.');
    }
}
