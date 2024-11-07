<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'booking';
    protected $fillable = ['id', 'uniqueid', 'customer_id', 'barber_id', 'service_id', 'date', 'time_period_id', 'note', 'status', 'type', 'contact_name', 'contact_phone', 'discount', 'discount_type','selected'];
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'uniqueid');
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class, 'barber_id','uniqueid');
    }
}
