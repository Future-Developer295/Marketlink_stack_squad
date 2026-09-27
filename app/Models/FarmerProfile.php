<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmerProfile extends Model
{
    use HasFactory;

    protected $table = 'farmer_profile';

    protected $fillable = [
        'user_id',
        'stall_name',
        'business_name',
        'description',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'operating_days',
        'start_time',
        'end_time',
        'cutoff_hours',
        'approval_status',
        'approved_by',
        'approved_at',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'cutoff_hours' => 'integer',
            'approved_at' => 'datetime',
            'commission_rate' => 'decimal:2',
        ];
    }

    /**
     * The rate actually applied to this farmer's orders: their own
     * override if set, otherwise the platform default from config.
     */
    public function getEffectiveCommissionRateAttribute(): float
    {
        return $this->commission_rate !== null
            ? (float) $this->commission_rate
            : (float) config('marketlink.default_commission_rate', 10.00);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'farmer_id');
    }

    public function pickupSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class, 'farmer_id');
    }

    public function weeklyStockTemplates(): HasMany
    {
        return $this->hasMany(WeeklyStockTemplate::class, 'farmer_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'farmer_id');
    }

    public function marketFarmers(): HasMany
    {
        return $this->hasMany(MarketFarmer::class, 'farmer_id', 'user_id');
    }
}