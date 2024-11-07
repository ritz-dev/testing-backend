<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSold extends Model
{
    use HasFactory;
    protected $table = 'products_sold';
    protected $fillable = ['id', 'product_id', 'qty', 'total_amount', 'date'];
}
