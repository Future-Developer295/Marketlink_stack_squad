<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'operating_days',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function marketFarmers(): HasMany
    {
        return $this->hasMany(MarketFarmer::class, 'market_id');
    }

    public function pickupSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class, 'market_id');
    }
}
