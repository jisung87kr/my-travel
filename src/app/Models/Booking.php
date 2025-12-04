<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'schedule_id',
        'guide_id',
        'booking_code',
        'status',
        'adult_count',
        'child_count',
        'infant_count',
        'total_price',
        'special_request',
        'contact_name',
        'contact_phone',
        'contact_email',
        'confirmed_at',
        'checked_in_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'cancelled_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'adult_count' => 'integer',
            'child_count' => 'integer',
            'infant_count' => 'integer',
            'total_price' => 'integer',
            'confirmed_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }

    public static function generateBookingCode(): string
    {
        do {
            $code = 'B' . date('ymd') . Str::upper(Str::random(6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ProductSchedule::class, 'schedule_id');
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getTotalPersonsAttribute(): int
    {
        return $this->adult_count + $this->child_count + $this->infant_count;
    }

    public function getQuantityAttribute(): int
    {
        return $this->total_persons;
    }

    public function getTotalAmountAttribute(): int
    {
        return $this->total_price;
    }

    public function getBookingDateAttribute()
    {
        return $this->schedule?->date ?? $this->created_at;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->total_price) . '원';
    }

    public function isPending(): bool
    {
        return $this->status === BookingStatus::PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::CONFIRMED;
    }

    public function isCompleted(): bool
    {
        return $this->status === BookingStatus::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === BookingStatus::CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            BookingStatus::PENDING,
            BookingStatus::CONFIRMED,
        ]);
    }

    public function canTransitionTo(BookingStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    public function confirm(): void
    {
        $this->update([
            'status' => BookingStatus::CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    public function cancel(User $cancelledBy, ?string $reason = null): void
    {
        $this->update([
            'status' => BookingStatus::CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy->id,
            'cancellation_reason' => $reason,
        ]);

        $this->schedule->increaseStock($this->total_persons);
    }

    public function markAsNoShow(): void
    {
        $this->update([
            'status' => BookingStatus::NO_SHOW,
        ]);

        $this->user->incrementNoShowCount();
    }

    public function complete(): void
    {
        $this->update([
            'status' => BookingStatus::COMPLETED,
        ]);

        $this->product->increment('booking_count');
    }

    public function scopeByStatus($query, BookingStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->byStatus(BookingStatus::PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->byStatus(BookingStatus::CONFIRMED);
    }

    public function scopeCompleted($query)
    {
        return $query->byStatus(BookingStatus::COMPLETED);
    }
}
