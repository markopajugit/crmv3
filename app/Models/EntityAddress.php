<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'company_id',
        'street',
        'city',
        'zip',
        'country',
        'note',
    ];

    /**
     * Get the person that owns the address.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the company that owns the address.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
