<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id', 'order_id', 'cost', 'name', 'date_from', 'date_to', 'renewed'
    ];

    protected $table = 'order_service';

    /**
     * Get the order that owns the order service.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
