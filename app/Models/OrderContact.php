<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderContact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'person_id',
        'name',
        'email',
    ];

    /**
     * Get the order that owns the contact.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the person associated with the contact.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
