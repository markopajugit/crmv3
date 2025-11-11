<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonTaxResidency extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'country',
        'valid_from',
        'valid_to',
        'is_primary',
        'notes'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_primary' => 'boolean'
    ];

    /**
     * Get the person that owns the tax residency.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Scope to get only primary tax residencies
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get active tax residencies (no end date or end date in future)
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('valid_to')
              ->orWhere('valid_to', '>=', now()->toDateString());
        });
    }
} 