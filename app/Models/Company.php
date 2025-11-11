<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'number', 'registry_code','registration_country', 'registration_date', 'vat','notes', 'address_street', 'address_city', 'address_zip', 'address_dropdown', 'email', 'phone', 'address_note', 'email_note', 'phone_note', 'deleted', 'kyc_start', 'kyc_end', 'kyc_reason', 'tax_residency', 'activity_code', 'activity_code_description'
    ];

    /**
     * Get the orders for the company.
     */
    public function orders(){
        return $this->hasMany(Order::class);
    }


    public function persons(){
        return $this->belongsToMany(Person::class)->withPivot('relation')->withPivot('selected_email');
    }

    public function companies(){
        return $this->belongsToMany(Company::class)->withPivot('relation');
    }

    /**
     * Get the files for the company.
     */
    public function files(){
        return $this->hasMany(File::class)->orderBy('created_at', 'desc');
    }

    public function getNotes()
    {
        return $this->hasMany(Note::class)->orderByDesc('updated_at');
    }

    public function getContacts(){
        return $this->hasMany(EntityContact::class);
    }

    public function getAddresses(){
        return $this->morphMany(EntityAddress::class, 'addressable');
    }

    public function getCurrentRisk(){
        return $this->hasOne(EntityRisk::class)->latest();
    }

    public function getRisksHistory(){
        return $this->hasMany(EntityRisk::class);
    }

    /**
     * Get all KYC records for the company.
     */
    public function kycs()
    {
        return $this->morphMany(Kyc::class, 'kycable');
    }

    /**
     * Get the latest KYC record for the company.
     */
    public function getCurrentKyc()
    {
        return $this->morphOne(Kyc::class, 'kycable')->latest();
    }
}
