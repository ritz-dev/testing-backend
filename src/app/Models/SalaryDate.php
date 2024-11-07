<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryDate extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'salary_date';
    protected $fillable = ['id', 'barber_uniqueid', 'salary', 'updated_date'];
}
