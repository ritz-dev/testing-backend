<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'products';
    protected $fillable = ['id', 'uniqueid', 'product_name', 'description','quantity', 'stocks_left','price','photo'];
}
