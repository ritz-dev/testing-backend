<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customers extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer';
    protected $fillable = ['id','uniqueid','facebook_id','name','email','dob','contact_number','password'];

}
