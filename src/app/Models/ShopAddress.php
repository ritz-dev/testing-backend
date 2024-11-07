<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAddress extends Model
{
    use HasFactory;
    protected $table = 'shop_addresses';
    protected $fillable = ['id','uniqueid','address','contact_number'];
}
