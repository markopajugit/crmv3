<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityRisk extends Model
{
    use HasFactory;

    protected $fillable = [
        'riskable_type',
        'riskable_id',
        'risk_level',
        'assessment',
        'mitigation',
        'assessed_by',
        'assessment_date',
        'review_date',
    ];

    protected $dates = [
        'assessment_date',
        'review_date',
    ];

    public function riskable()
    {
        return $this->morphTo();
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
