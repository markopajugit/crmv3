<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityRisk extends Model
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
        'risk_level',
        'user_id',
    ];

    /**
     * Get the person that owns the risk.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the company that owns the risk.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created the risk.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
