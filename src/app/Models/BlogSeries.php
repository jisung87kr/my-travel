<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogSeries extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_series';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'post_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'post_count' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (BlogSeries $series) {
            if (empty($series->slug)) {
                $series->slug = static::generateUniqueSlug($series->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title) ?: 'series-' . time();
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'series_id')->orderBy('series_order');
    }

    public function publishedPosts(): HasMany
    {
        return $this->posts()->published();
    }

    public function updatePostCount(): void
    {
        $this->update([
            'post_count' => $this->posts()->published()->count(),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }
}
