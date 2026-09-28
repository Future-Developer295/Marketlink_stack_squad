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
        'approval_status',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    protected static function booted(): void
    {
        static::updated(function (Product $product): void {
            $wasAvailable = $product->getOriginal('is_active') && $product->getOriginal('stock_quantity') > 0;

            if ($wasAvailable || ! $product->is_active || $product->stock_quantity < 1) {
                return;
            }

            $followers = Favorite::where('product_id', $product->id)
                ->orWhere('farmer_id', $product->farmer_id)
                ->distinct()
                ->pluck('user_id');

            foreach ($followers as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'restock',
                    'title' => 'A favorite is back in stock',
                    'message' => $product->name . ' is available again. Explore your saved favorites to take a look.',
                    'is_read' => false,
                ]);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_active', true)
            ->where('approval_status', 'approved');
    }

    public function imageUrl(): string
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL) && in_array(parse_url($this->image, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return $this->image;
        }

        if ($this->image && is_file(public_path('product_images/' . basename($this->image)))) {
            return asset('product_images/' . basename($this->image));
        }

        return asset('Assets/Website_Asset/images/product-placeholder.svg');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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