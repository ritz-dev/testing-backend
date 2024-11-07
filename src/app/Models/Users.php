<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    use HasFactory,SoftDeletes;
    protected $table = 'user';
    protected $fillable = ['id','name','email','role_id','password','uniqueid','phone'];

}
