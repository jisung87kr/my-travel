<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BlogPostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function index(Request $request): View
    {
        $query = BlogPost::with(['author', 'category', 'series', 'tags']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('series')) {
            $query->where('series_id', $request->series);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(20);
        $categories = BlogCategory::active()->ordered()->get();
        $series = BlogSeries::active()->get();
        $statuses = BlogPostStatus::cases();

        return view('admin.blog.posts.index', compact('posts', 'categories', 'series', 'statuses'));
    }

    public function create(): View
    {
        $categories = BlogCategory::active()->ordered()->get();
        $series = BlogSeries::active()->get();

        return view('admin.blog.posts.create', compact('categories', 'series'));
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $post = $this->blogService->create(
            $request->validated(),
            $request->user()
        );

        return redirect()->route('admin.blog.posts.show', $post)
            ->with('success', '포스트가 저장되었습니다.');
    }

    public function show(BlogPost $post): View
    {
        $post->load(['author', 'category', 'series', 'tags', 'comments.user']);

        return view('admin.blog.posts.show', compact('post'));
    }

    public function edit(BlogPost $post): View
    {
        $post->load(['category', 'series', 'tags']);

        $categories = BlogCategory::active()->ordered()->get();
        $series = BlogSeries::active()->get();

        return view('admin.blog.posts.edit', compact('post', 'categories', 'series'));
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $this->blogService->update($post, $request->validated());

        return redirect()->route('admin.blog.posts.show', $post)
            ->with('success', '포스트가 수정되었습니다.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $this->blogService->delete($post);

        return redirect()->route('admin.blog.posts.index')
            ->with('success', '포스트가 삭제되었습니다.');
    }

    public function publish(BlogPost $post): RedirectResponse
    {
        $this->blogService->publish($post);

        return redirect()->back()->with('success', '포스트가 발행되었습니다.');
    }

    public function unpublish(BlogPost $post): RedirectResponse
    {
        $this->blogService->unpublish($post);

        return redirect()->back()->with('success', '포스트가 비공개로 전환되었습니다.');
    }

    public function archive(BlogPost $post): RedirectResponse
    {
        $this->blogService->archive($post);

        return redirect()->back()->with('success', '포스트가 보관되었습니다.');
    }
}
