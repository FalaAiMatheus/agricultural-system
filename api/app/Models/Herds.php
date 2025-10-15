<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Herds extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'property_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'species',
        'quantity',
        'purpose',
        'update_date'
    ];

    public function properties(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
