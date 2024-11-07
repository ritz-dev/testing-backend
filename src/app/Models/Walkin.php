<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Walkin extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'walk_in_customers';
    protected $fillable = ['id','uniqueid','barber_id','service_id','date','time_period_id','status','discount','discount_type','name','phone'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'uniqueid');
    }
    public function barber()
    {
        return $this->belongsTo(Barber::class, 'barber_id','uniqueid');
    }
}
