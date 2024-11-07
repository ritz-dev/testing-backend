<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $table = 'booking_time';
    protected $fillable = ['id','uniqueid','time_period'];
    
}
