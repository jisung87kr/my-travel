<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'date',
        'start_time',
        'total_count',
        'available_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'total_count' => 'integer',
            'available_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    public function isAvailable(int $quantity = 1): bool
    {
        return $this->is_active && $this->available_count >= $quantity;
    }

    public function decreaseStock(int $quantity): bool
    {
        if (!$this->isAvailable($quantity)) {
            return false;
        }

        $this->decrement('available_count', $quantity);

        return true;
    }

    public function increaseStock(int $quantity): void
    {
        $newCount = min($this->available_count + $quantity, $this->total_count);
        $this->update(['available_count' => $newCount]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->active()->where('available_count', '>', 0);
    }

    public function scopeFuture($query)
    {
        return $query->where('date', '>=', today());
    }
}
