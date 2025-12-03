<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'preferred_language',
        'provider',
        'provider_id',
        'no_show_count',
        'is_blocked',
        'blocked_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_at' => 'datetime',
            'is_blocked' => 'boolean',
            'is_active' => 'boolean',
            'preferred_language' => Language::class,
        ];
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
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

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function canBook(): bool
    {
        return !$this->isBlocked() && $this->is_active;
    }

    public function incrementNoShowCount(): void
    {
        $this->increment('no_show_count');

        if ($this->no_show_count >= 3) {
            $this->update([
                'is_blocked' => true,
                'blocked_at' => now(),
            ]);
        }
    }
}
