<?php

namespace App\Models;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarberAbsence extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "barber_absence";

    protected $fillable = [
        "barber_id",
        "time_period_id",
        "day"
    ];

    public function barber(){
        return $this->belongsTo(Barber::class,'barber_id','uniqueid');
    }
}

