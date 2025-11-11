<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'type',
        'street',
        'city',
        'zip',
        'country',
        'notes',
        'is_primary',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
