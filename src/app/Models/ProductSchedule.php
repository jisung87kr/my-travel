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

    protected $appends = ['is_past', 'starts_at', 'starts_at_human'];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
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

    /**
     * 날짜와 시간을 합친 시작 일시 (Carbon)
     */
    public function getStartsAtCarbon(): ?\Carbon\Carbon
    {
        if (!$this->date || !$this->start_time) {
            return null;
        }

        return $this->date->copy()->setTimeFrom($this->start_time);
    }

    /**
     * 날짜와 시간을 합친 시작 일시 (JSON용 문자열)
     */
    public function getStartsAtAttribute(): ?string
    {
        return $this->getStartsAtCarbon()?->format('Y-m-d H:i');
    }

    public function getStartsAtHumanAttribute(): ?string
    {
        $carbon = $this->getStartsAtCarbon();
        if (!$carbon) {
            return null;
        }

        $days = ['일', '월', '화', '수', '목', '금', '토'];
        $dayOfWeek = $days[$carbon->dayOfWeek];

        return $carbon->format('Y-m-d') . "({$dayOfWeek}) " . $carbon->format('H:i');
    }

    /**
     * 시작 시간이 지났는지 확인
     */
    public function getIsPastAttribute(): bool
    {
        $startsAt = $this->getStartsAtCarbon();

        if (!$startsAt) {
            return $this->date?->lt(today()) ?? false;
        }

        return $startsAt->lte(now());
    }
}
