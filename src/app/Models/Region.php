<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    public const LEVEL_COUNTRY = 1;
    public const LEVEL_PROVINCE = 2;
    public const LEVEL_DISTRICT = 3;

    protected $fillable = [
        'parent_id',
        'code',
        'slug',
        'level',
        'latitude',
        'longitude',
        'popularity',
        'is_active',
        'is_featured',
        'sort_order',
        'timezone',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'popularity' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Region $region) {
            if (empty($region->slug)) {
                $region->slug = static::generateUniqueSlug($region->code);
            }
        });
    }

    public static function generateUniqueSlug(string $code): string
    {
        $slug = Str::slug($code);
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
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')->orderBy('sort_order');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(RegionTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RegionImage::class)->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Translation helper
    public function getTranslation(?string $locale = null): ?RegionTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', Language::default()->value)
            ?? $this->translations->first();
    }

    public function getName(?string $locale = null): string
    {
        return $this->getTranslation($locale)?->name ?? $this->code;
    }

    public function getShortName(?string $locale = null): string
    {
        $translation = $this->getTranslation($locale);

        return $translation?->short_name ?? $translation?->name ?? $this->code;
    }

    // Image helpers
    public function getPrimaryImage(): ?RegionImage
    {
        return $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();
    }

    public function getHeroImage(): ?RegionImage
    {
        return $this->images->firstWhere('type', 'hero')
            ?? $this->getPrimaryImage();
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

    public function isCountry(): bool
    {
        return $this->level === self::LEVEL_COUNTRY;
    }

    public function isProvince(): bool
    {
        return $this->level === self::LEVEL_PROVINCE;
    }

    public function isDistrict(): bool
    {
        return $this->level === self::LEVEL_DISTRICT;
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

    public function scopeCountries($query)
    {
        return $query->byLevel(self::LEVEL_COUNTRY);
    }

    public function scopeProvinces($query)
    {
        return $query->byLevel(self::LEVEL_PROVINCE);
    }

    public function scopeDistricts($query)
    {
        return $query->byLevel(self::LEVEL_DISTRICT);
    }

    public function scopePopular($query, int $limit = 10)
    {
        return $query->orderByDesc('popularity')->limit($limit);
    }

    public function scopeWithProductCount($query)
    {
        return $query->withCount(['products' => fn ($q) => $q->active()]);
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

    // Utility
    public function incrementPopularity(): void
    {
        $this->increment('popularity');
    }

    public function getFullPath(?string $locale = null): string
    {
        return $this->getAncestors()
            ->push($this)
            ->map(fn ($r) => $r->getName($locale))
            ->implode(' > ');
    }
}
