<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionTranslation extends Model
{
    protected $fillable = [
        'region_id',
        'locale',
        'name',
        'short_name',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'locale' => Language::class,
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
