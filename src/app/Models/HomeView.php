<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeView extends Model
{
    use HasFactory;
    protected $table = "home_views";
    protected $fillable = [
        "photo",
        "description"
    ];
}
