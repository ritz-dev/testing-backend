<?php

namespace App\Models;

use App\Models\BarberCommission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barber extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barber';
    protected $fillable = ['id','uniqueid','barber_name','barber_photo','email','contact_number','base_salary','working_days','shop_id','commission_rate','join_date','password'];

    public function times()
    {
        return $this->hasMany(BarberTime::class, 'barber_id', 'uniqueid');
    }


 public function commissions()
    {
        return $this->hasMany(BarberCommission::class);
    }
}
