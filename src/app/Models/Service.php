<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service';
    protected $fillable = ['id','uniqueid','service_name','duration','pricing','description','is_package'];
}
