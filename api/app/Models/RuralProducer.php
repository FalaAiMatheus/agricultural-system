<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuralProducer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'document',
        'telephone',
        'email',
        'address',
        'registration_date'
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
