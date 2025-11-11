<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cost', 'type', 'service_category_id', 'reaccuring_frequency'
    ];

    public function service_category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
