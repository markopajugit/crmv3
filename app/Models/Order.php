<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'number', 'description','company_id', 'person_id', 'notes', 'responsible_user_id', 'status', 'payment_status', 'awaiting_status', 'notification_sent', 'paid_date'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_date'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function responsible_user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for the company.
     */
    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the services for the order.
     */
    /*public function services(){
        return $this->hasMany(Service::class);
    }*/

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('id')->withPivot('name')->withPivot('cost')->withPivot('date_from')->withPivot('date_to')->withPivot('renewed')->orderBy('service_category_id', 'asc')->orderBy('service_id', 'asc');
    }

    public function orderServices()
    {
        return $this->hasMany(OrderService::class);
    }

    public function getNotes()
    {
        return $this->hasMany(Note::class)->orderByDesc('updated_at');
    }

    public function getOrderContacts()
    {
        return $this->hasMany(OrderContact::class);
    }

    public function files(){
        return $this->hasMany(File::class)->orderBy('created_at', 'desc');
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
