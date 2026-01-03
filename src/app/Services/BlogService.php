<?php

namespace App\Services;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlogService
{
    public function __construct(
        private ImageService $imageService
    ) {}

    public function create(array $data, User $author): BlogPost
    {
        return DB::transaction(function () use ($data, $author) {
            $post = BlogPost::create([
                'user_id' => $author->id,
                'category_id' => $data['category_id'] ?? null,
                'series_id' => $data['series_id'] ?? null,
                'series_order' => $data['series_order'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'] ?? null,
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'],
                'thumbnail_path' => $this->handleThumbnail($data['thumbnail'] ?? null),
                'status' => BlogPostStatus::DRAFT,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            if (!empty($data['tags'])) {
                $this->syncTags($post, $data['tags']);
            }

            if ($data['status'] === 'published') {
                $this->publish($post);
            }

            $this->updateSeriesPostCount($post->series_id);

            return $post->load(['author', 'category', 'series', 'tags']);
        });
    }

    public function update(BlogPost $post, array $data): BlogPost
    {
        return DB::transaction(function () use ($post, $data) {
            $oldSeriesId = $post->series_id;

            $updateData = [
                'category_id' => $data['category_id'] ?? $post->category_id,
                'series_id' => $data['series_id'] ?? $post->series_id,
                'series_order' => $data['series_order'] ?? $post->series_order,
                'title' => $data['title'] ?? $post->title,
                'excerpt' => $data['excerpt'] ?? $post->excerpt,
                'content' => $data['content'] ?? $post->content,
                'meta_title' => $data['meta_title'] ?? $post->meta_title,
                'meta_description' => $data['meta_description'] ?? $post->meta_description,
            ];

            if (isset($data['slug']) && $data['slug'] !== $post->slug) {
                $updateData['slug'] = $data['slug'];
            }

            if (isset($data['thumbnail'])) {
                if ($post->thumbnail_path) {
                    $this->imageService->delete($post->thumbnail_path);
                }
                $updateData['thumbnail_path'] = $this->handleThumbnail($data['thumbnail']);
            }

            if (isset($data['remove_thumbnail']) && $data['remove_thumbnail']) {
                if ($post->thumbnail_path) {
                    $this->imageService->delete($post->thumbnail_path);
                }
                $updateData['thumbnail_path'] = null;
            }

            $post->update($updateData);

            if (isset($data['tags'])) {
                $this->syncTags($post, $data['tags']);
            }

            if (isset($data['status'])) {
                if ($data['status'] === 'published' && !$post->isPublished()) {
                    $this->publish($post);
                } elseif ($data['status'] === 'draft' && $post->isPublished()) {
                    $this->unpublish($post);
                } elseif ($data['status'] === 'archived') {
                    $this->archive($post);
                }
            }

            if ($oldSeriesId !== $post->series_id) {
                $this->updateSeriesPostCount($oldSeriesId);
                $this->updateSeriesPostCount($post->series_id);
            }

            return $post->fresh(['author', 'category', 'series', 'tags']);
        });
    }

    public function delete(BlogPost $post): bool
    {
        return DB::transaction(function () use ($post) {
            $seriesId = $post->series_id;

            if ($post->thumbnail_path) {
                $this->imageService->delete($post->thumbnail_path);
            }

            $post->tags()->detach();
            $result = $post->delete();

            $this->updateSeriesPostCount($seriesId);
            $this->updateTagPostCounts($post->tags->pluck('id')->toArray());

            return $result;
        });
    }

    public function publish(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => BlogPostStatus::PUBLISHED,
            'published_at' => $post->published_at ?? now(),
        ]);

        $this->updateSeriesPostCount($post->series_id);
        $this->updateTagPostCounts($post->tags->pluck('id')->toArray());

        return $post;
    }

    public function unpublish(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => BlogPostStatus::DRAFT,
        ]);

        $this->updateSeriesPostCount($post->series_id);
        $this->updateTagPostCounts($post->tags->pluck('id')->toArray());

        return $post;
    }

    public function archive(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => BlogPostStatus::ARCHIVED,
        ]);

        $this->updateSeriesPostCount($post->series_id);
        $this->updateTagPostCounts($post->tags->pluck('id')->toArray());

        return $post;
    }

    public function syncTags(BlogPost $post, array $tagNames): void
    {
        $tagIds = collect($tagNames)
            ->filter()
            ->map(function ($name) {
                return BlogTag::findOrCreateByName(trim($name))->id;
            })
            ->toArray();

        $oldTagIds = $post->tags->pluck('id')->toArray();
        $post->tags()->sync($tagIds);

        $this->updateTagPostCounts(array_unique(array_merge($oldTagIds, $tagIds)));
    }

    public function recordView(BlogPost $post, ?User $user, string $ip): void
    {
        $sessionKey = 'blog_viewed_' . $post->id;

        if (session()->has($sessionKey)) {
            return;
        }

        session()->put($sessionKey, true);
        $post->increment('view_count');
    }

    public function toggleLike(BlogPost $post, User $user): array
    {
        $like = BlogLike::where('blog_post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $post->decrement('like_count');
            $liked = false;
        } else {
            BlogLike::create([
                'blog_post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $post->increment('like_count');
            $liked = true;
        }

        return [
            'liked' => $liked,
            'like_count' => $post->fresh()->like_count,
        ];
    }

    public function createComment(BlogPost $post, User $user, array $data): BlogComment
    {
        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
            'is_approved' => true,
            'is_visible' => true,
        ]);

        $post->increment('comment_count');

        return $comment->load('user');
    }

    public function deleteComment(BlogComment $comment): bool
    {
        $post = $comment->post;

        $repliesCount = $comment->replies()->count();
        $comment->replies()->delete();
        $result = $comment->delete();

        $post->decrement('comment_count', 1 + $repliesCount);

        return $result;
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = BlogPost::query()
            ->with(['author', 'category', 'tags'])
            ->published();

        if (!empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (!empty($filters['series'])) {
            $query->bySeries($filters['series']);
        }

        if (!empty($filters['tag'])) {
            $query->byTag($filters['tag']);
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'published_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($filters['per_page'] ?? 12, 50);

        return $query->paginate($perPage);
    }

    public function getRelatedPosts(BlogPost $post, int $limit = 4): Collection
    {
        $tagIds = $post->tags->pluck('id')->toArray();

        return BlogPost::query()
            ->with(['author', 'category'])
            ->published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post, $tagIds) {
                if ($post->category_id) {
                    $query->where('category_id', $post->category_id);
                }
                if (!empty($tagIds)) {
                    $query->orWhereHas('tags', fn ($q) => $q->whereIn('blog_tags.id', $tagIds));
                }
            })
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getPopularPosts(int $limit = 5): Collection
    {
        return BlogPost::query()
            ->with(['author', 'category'])
            ->published()
            ->popular()
            ->limit($limit)
            ->get();
    }

    public function getRecentPosts(int $limit = 5): Collection
    {
        return BlogPost::query()
            ->with(['author', 'category'])
            ->published()
            ->recent()
            ->limit($limit)
            ->get();
    }

    public function uploadEditorImage(UploadedFile $file): string
    {
        $path = $this->imageService->store($file, 'blog/content');

        return $this->imageService->getUrl($path);
    }

    private function handleThumbnail(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        return $this->imageService->store($file, 'blog/thumbnails');
    }

    private function updateSeriesPostCount(?int $seriesId): void
    {
        if (!$seriesId) {
            return;
        }

        $series = BlogSeries::find($seriesId);
        $series?->updatePostCount();
    }

    private function updateTagPostCounts(array $tagIds): void
    {
        foreach ($tagIds as $tagId) {
            $tag = BlogTag::find($tagId);
            $tag?->updatePostCount();
        }
    }

    public function reorderSeriesPosts(BlogSeries $series, array $order): void
    {
        DB::transaction(function () use ($series, $order) {
            foreach ($order as $index => $postId) {
                BlogPost::where('id', $postId)
                    ->where('series_id', $series->id)
                    ->update(['series_order' => $index + 1]);
            }
        });
    }

    public function getActiveCategories(): Collection
    {
        return BlogCategory::active()
            ->ordered()
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->get();
    }

    public function getActiveSeries(): Collection
    {
        return BlogSeries::active()
            ->where('post_count', '>', 0)
            ->orderByDesc('updated_at')
            ->get();
    }

    public function getPopularTags(int $limit = 20): Collection
    {
        return BlogTag::where('post_count', '>', 0)
            ->popular($limit)
            ->get();
    }
}
