<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $placeholder = asset('Assets/Website_Asset/images/product-placeholder.svg');

        if (!$this->image) {
            return $placeholder;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = ltrim(str_replace('\\', '/', $this->image), '/');

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        if (is_file(public_path($path))) {
            return asset($path);
        }

        $index = static::imageIndex();

        $match = $index['exact'][strtolower(basename($path))]
            ?? $index['stem'][static::imageStem($path)]
            ?? null;

        return $match
            ? asset('Assets/Website_Asset/images/' . $match)
            : $placeholder;
    }

    protected static function imageStem(string $name): string
    {
        $stem = preg_replace('/[^a-z0-9]/', '', strtolower(pathinfo($name, PATHINFO_FILENAME)));

        return Str::singular($stem);
    }

    protected static function imageIndex(): array
    {
        static $index = null;

        if ($index !== null) {
            return $index;
        }

        $index = ['exact' => [], 'stem' => []];
        $root = public_path('Assets/Website_Asset/images');

        if (!is_dir($root)) {
            return $index;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if (!$file->isFile() || !preg_match('/\.(jpe?g|png|webp|gif|avif)$/i', $file->getFilename())) {
                continue;
            }

            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));

            $index['exact'][strtolower($file->getFilename())] ??= $relative;
            $index['stem'][static::imageStem($file->getFilename())] ??= $relative;
        }

        return $index;
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