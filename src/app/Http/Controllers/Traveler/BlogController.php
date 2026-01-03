<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function index(Request $request): View
    {
        $posts = $this->blogService->search([
            'keyword' => $request->keyword,
            'sort_by' => $request->sort_by ?? 'published_at',
            'sort_order' => 'desc',
            'per_page' => 12,
        ]);

        $categories = $this->blogService->getActiveCategories();
        $popularPosts = $this->blogService->getPopularPosts(5);
        $popularTags = $this->blogService->getPopularTags(15);

        return view('blog.index', compact('posts', 'categories', 'popularPosts', 'popularTags'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with(['author', 'category', 'series', 'tags'])
            ->firstOrFail();

        $this->blogService->recordView($post, auth()->user(), request()->ip());

        $relatedPosts = $this->blogService->getRelatedPosts($post, 4);
        $categories = $this->blogService->getActiveCategories();

        $previousPost = $post->getPreviousInSeries();
        $nextPost = $post->getNextInSeries();

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'previousPost', 'nextPost'));
    }

    public function category(string $slug): View
    {
        $category = BlogCategory::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $posts = $this->blogService->search([
            'category' => $category->id,
            'per_page' => 12,
        ]);

        $categories = $this->blogService->getActiveCategories();
        $popularTags = $this->blogService->getPopularTags(15);

        return view('blog.category', compact('category', 'posts', 'categories', 'popularTags'));
    }

    public function series(string $slug): View
    {
        $series = BlogSeries::where('slug', $slug)
            ->active()
            ->with(['posts' => fn ($q) => $q->published()->orderBy('series_order')])
            ->firstOrFail();

        $categories = $this->blogService->getActiveCategories();

        return view('blog.series', compact('series', 'categories'));
    }

    public function tag(string $slug): View
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = $this->blogService->search([
            'tag' => $tag->id,
            'per_page' => 12,
        ]);

        $categories = $this->blogService->getActiveCategories();
        $popularTags = $this->blogService->getPopularTags(15);

        return view('blog.tag', compact('tag', 'posts', 'categories', 'popularTags'));
    }
}
