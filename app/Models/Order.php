<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'pending' => 'Placed',
        'confirmed' => 'Accepted',
        'ready' => 'Ready for Pickup',
        'picked_up' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'user_id',
        'farmer_id',
        'pickup_slot_id',
        'total_amount',
        'status',
        'order_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'order_date' => 'datetime',
        ];
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function cutoffAt(): ?Carbon
    {
        if (! $this->pickupSlot) {
            return null;
        }

        $cutoffHours = $this->farmer?->cutoff_hours ?? 2;

        return Carbon::parse($this->pickupSlot->date->toDateString().' '.$this->pickupSlot->start_time)
            ->subHours($cutoffHours);
    }

    public function isCancellable(): bool
    {
        if (! in_array($this->status, ['pending', 'confirmed'], true)) {
            return false;
        }

        $cutoffAt = $this->cutoffAt();

        return ! $cutoffAt || now()->lessThan($cutoffAt);
    }

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

    
    public function pickupSlot(): BelongsTo
    {
        return $this->belongsTo(PickupSlot::class, 'pickup_slot_id');
    }
    public function items(): HasMany
{
    return $this->hasMany(OrderItem::class, 'order_id');
}
}
