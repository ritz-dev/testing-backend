<?php

namespace App\Models;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarberTime extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'barber_time';
    protected $fillable = ['id', 'barber_id', 'working_day', 'from', 'to'];

    public function barber()
    {
        return $this->belongsTo(Barber::class, 'uniqueid', 'barber_id');
    }
}
