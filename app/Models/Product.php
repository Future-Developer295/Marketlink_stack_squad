<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'unit',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
}
