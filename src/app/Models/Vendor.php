<?php

namespace App\Models;

use App\Enums\VendorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'business_number',
        'contact_phone',
        'contact_email',
        'description',
        'status',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => VendorStatus::class,
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Product::class);
    }

    public function messages(): HasManyThrough
    {
        return $this->hasManyThrough(
            Message::class,
            Booking::class,
            'product_id',
            'booking_id'
        )->whereHas('booking.product', fn ($q) => $q->where('vendor_id', $this->id));
    }

    public function isApproved(): bool
    {
        return $this->status === VendorStatus::APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === VendorStatus::PENDING;
    }

    public function approve(): void
    {
        $this->update([
            'status' => VendorStatus::APPROVED,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => VendorStatus::REJECTED,
            'rejection_reason' => $reason,
        ]);
    }
}
