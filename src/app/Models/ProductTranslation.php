<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'locale',
        'name',
        'description',
        'includes',
        'excludes',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'locale' => Language::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
