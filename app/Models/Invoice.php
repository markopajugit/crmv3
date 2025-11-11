<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id', 'order_id', 'issue_date', 'payment_date', 'vat', 'vat_no', 'status', 'vat_comment', 'is_proforma', 'number', 'registry_code', 'payer_name', 'street', 'city', 'zip', 'country'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
