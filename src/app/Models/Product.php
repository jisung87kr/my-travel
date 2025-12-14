<?php

namespace App\Models;

use App\Enums\BookingType;
use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'slug',
        'type',
        'region',
        'duration',
        'min_persons',
        'max_persons',
        'booking_type',
        'latitude',
        'longitude',
        'status',
        'average_rating',
        'review_count',
        'booking_count',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'region' => Region::class,
            'booking_type' => BookingType::class,
            'status' => ProductStatus::class,
            'average_rating' => 'decimal:1',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->slug_source ?? 'product');
            }
            unset($product->slug_source);
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title) ?: 'product-' . time();
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ProductSchedule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getTranslation(?string $locale = null): ?ProductTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', Language::default()->value)
            ?? $this->translations->first();
    }

    public function getPrimaryImage(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();
    }

    public function getAdultPrice(): ?ProductPrice
    {
        return $this->prices->firstWhere('type', 'adult');
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::ACTIVE;
    }

    public function isInstantBooking(): bool
    {
        return $this->booking_type === BookingType::INSTANT;
    }

    public function updateRating(): void
    {
        $this->update([
            'average_rating' => $this->reviews()->avg('rating'),
            'review_count' => $this->reviews()->count(),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', ProductStatus::ACTIVE);
    }

    public function scopeByRegion($query, string|Region $region)
    {
        $region = $region instanceof Region ? $region->value : $region;

        return $query->where('region', $region);
    }

    public function scopeByType($query, string|ProductType $type)
    {
        $type = $type instanceof ProductType ? $type->value : $type;

        return $query->where('type', $type);
    }
}
