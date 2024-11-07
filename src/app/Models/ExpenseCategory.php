<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'expense_category';

    protected $fillable = [
        "uniqueid",
        "title"
    ];
}
