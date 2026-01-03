<?php

namespace App\Models;

use App\Enums\BlogPostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'series_id',
        'series_order',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail_path',
        'status',
        'published_at',
        'view_count',
        'like_count',
        'comment_count',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'status' => BlogPostStatus::class,
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'like_count' => 'integer',
            'comment_count' => 'integer',
            'series_order' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title) ?: 'post-' . time();
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(BlogSeries::class, 'series_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function visibleComments(): HasMany
    {
        return $this->comments()
            ->where('is_visible', true)
            ->whereNull('parent_id')
            ->orderByDesc('created_at');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BlogLike::class);
    }

    public function isPublished(): bool
    {
        return $this->status === BlogPostStatus::PUBLISHED
            && $this->published_at
            && $this->published_at->lte(now());
    }

    public function isDraft(): bool
    {
        return $this->status === BlogPostStatus::DRAFT;
    }

    public function isArchived(): bool
    {
        return $this->status === BlogPostStatus::ARCHIVED;
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function updateCounts(): void
    {
        $this->update([
            'like_count' => $this->likes()->count(),
            'comment_count' => $this->comments()->where('is_visible', true)->count(),
        ]);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));

        return max(1, (int) ceil($wordCount / 200));
    }

    public function getPreviousInSeries(): ?self
    {
        if (!$this->series_id || !$this->series_order) {
            return null;
        }

        return static::where('series_id', $this->series_id)
            ->where('series_order', '<', $this->series_order)
            ->published()
            ->orderByDesc('series_order')
            ->first();
    }

    public function getNextInSeries(): ?self
    {
        if (!$this->series_id || !$this->series_order) {
            return null;
        }

        return static::where('series_id', $this->series_id)
            ->where('series_order', '>', $this->series_order)
            ->published()
            ->orderBy('series_order')
            ->first();
    }

    public function scopePublished($query)
    {
        return $query->where('status', BlogPostStatus::PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', BlogPostStatus::DRAFT);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', BlogPostStatus::ARCHIVED);
    }

    public function scopeByCategory($query, int|BlogCategory $category)
    {
        $categoryId = $category instanceof BlogCategory ? $category->id : $category;

        return $query->where('category_id', $categoryId);
    }

    public function scopeBySeries($query, int|BlogSeries $series)
    {
        $seriesId = $series instanceof BlogSeries ? $series->id : $series;

        return $query->where('series_id', $seriesId);
    }

    public function scopeByTag($query, int|BlogTag $tag)
    {
        $tagId = $tag instanceof BlogTag ? $tag->id : $tag;

        return $query->whereHas('tags', fn ($q) => $q->where('blog_tags.id', $tagId));
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('view_count');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('published_at');
    }
}
