<?php

namespace App\Models;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberCommission extends Model
{
    use HasFactory;
protected $table = "barber_commissions";

    protected $fillable = [
        "barber_id",
        "date",
        "commission_rate",
    ];

    public function barber(){
        return $this->belongsTo(Barber::class,'barber_id','id');
    }
}
