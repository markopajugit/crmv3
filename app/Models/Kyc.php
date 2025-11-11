<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kycable_type',
        'kycable_id', 
        'comments',
        'risk',
        'documents',
        'responsible_user_id',
        'start_date',
        'end_date'
    ];



    /**
     * Get the owning kycable model (Company or Person).
     */
    public function kycable()
    {
        return $this->morphTo();
    }

    /**
     * Get the responsible user for this KYC record.
     */
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
} 