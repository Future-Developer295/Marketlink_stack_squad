<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'market_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'capacity' => 'integer',
            'is_available' => 'boolean',
        ];
    }

   
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

   
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}
