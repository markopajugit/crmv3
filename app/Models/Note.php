<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id', 'order_id', 'company_id', 'content', 'user_id'
    ];

    protected $table = 'notes';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function responsible_user($id)
    {
        return User::find($id);
    }
}
