<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionUnit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'culture_name',
        'property_id',
        'total_area_ha',
        'latitude',
        'longitude',
    ];

    public function properties(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
