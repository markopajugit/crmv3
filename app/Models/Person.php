<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address_street', 'address_city', 'address_zip', 'address_dropdown', 'id_code', 'id_code_est', 'email', 'phone', 'tax_residency', 'notes', 'date_of_birth', 'country', 'address_note', 'email_note', 'phone_note', 'birthplace_country', 'birthplace_city', 'citizenship', 'pep'
    ];

    protected $table = 'persons';

    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('relation');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getNotes()
    {
        return $this->hasMany(Note::class)->orderByDesc('updated_at');
    }

    public function files(){
        return $this->hasMany(File::class)->orderBy('created_at', 'desc');
    }

    public function getContacts(){
        return $this->morphMany(EntityContact::class, 'contactable');
    }

    public function getAddresses(){
        return $this->morphMany(EntityAddress::class, 'addressable');
    }

    public function getCurrentRisk(){
        return $this->morphOne(EntityRisk::class, 'riskable')->latest();
    }

    public function getRisksHistory(){
        return $this->morphMany(EntityRisk::class, 'riskable');
    }

    /**
     * Get all KYC records for the person.
     */
    public function kycs()
    {
        return $this->morphMany(Kyc::class, 'kycable');
    }

    /**
     * Get the latest KYC record for the person.
     */
    public function getCurrentKyc()
    {
        return $this->morphOne(Kyc::class, 'kycable')->latest();
    }

    /**
     * Get all tax residencies for the person.
     */
    public function taxResidencies()
    {
        return $this->hasMany(PersonTaxResidency::class);
    }

    /**
     * Get active tax residencies for the person.
     */
    public function getActiveTaxResidencies()
    {
        return $this->taxResidencies()->active();
    }

    /**
     * Get the primary tax residency for the person.
     */
    public function getPrimaryTaxResidency()
    {
        return $this->taxResidencies()->primary()->active()->first();
    }

    /**
     * Get all tax residency countries as a comma-separated string.
     */
    public function getTaxResidencyCountriesAttribute()
    {
        return $this->getActiveTaxResidencies()->pluck('country')->implode(', ');
    }
}
