<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'municipality',
        'uf',
        'state_registration',
        'total_area',
        'producer_id'
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(RuralProducer::class);
    }

    public function productionUnit(): HasMany
    {
        return $this->hasMany(ProductionUnit::class);
    }

    public function herds(): HasMany
    {
        return $this->hasMany(Herds::class);
    }
}
