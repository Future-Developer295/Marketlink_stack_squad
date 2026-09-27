<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

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
