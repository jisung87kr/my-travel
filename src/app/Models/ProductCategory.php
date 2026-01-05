<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    public const LEVEL_MAIN = 1;
    public const LEVEL_SUB = 2;
    public const LEVEL_DETAIL = 3;

    protected $fillable = [
        'parent_id',
        'slug',
        'level',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (ProductCategory $category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->slug ?? 'category');
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductCategoryTranslation::class, 'category_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    // Translation helper
    public function getTranslation(?string $locale = null): ?ProductCategoryTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', Language::default()->value)
            ?? $this->translations->first();
    }

    public function getName(?string $locale = null): string
    {
        return $this->getTranslation($locale)?->name ?? $this->slug;
    }

    public function getDescription(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)?->description;
    }

    // Hierarchy helpers
    public function getAncestors(): Collection
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    public function getDescendants(): Collection
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendants());
        }

        return $descendants;
    }

    public function getFullPath(?string $locale = null): string
    {
        return $this->getAncestors()
            ->push($this)
            ->map(fn ($c) => $c->getName($locale))
            ->implode(' > ');
    }

    public function isMain(): bool
    {
        return $this->level === self::LEVEL_MAIN;
    }

    public function isSub(): bool
    {
        return $this->level === self::LEVEL_SUB;
    }

    public function isDetail(): bool
    {
        return $this->level === self::LEVEL_DETAIL;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    public function scopeMain($query)
    {
        return $query->byLevel(self::LEVEL_MAIN);
    }

    public function scopeSub($query)
    {
        return $query->byLevel(self::LEVEL_SUB);
    }

    public function scopeDetail($query)
    {
        return $query->byLevel(self::LEVEL_DETAIL);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildrenOf($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeWithProductCount($query)
    {
        return $query->withCount(['products' => fn ($q) => $q->active()]);
    }
}
