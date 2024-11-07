<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class AuditLog extends Model
{
    use HasFactory;
    protected $table = 'audit_logs';
    protected $fillable = ['id','customer_id','staff','status','service','discount','discount_type'];
    public function customer()
{
    return $this->belongsTo(Customers::class, 'customer_id','uniqueid');
}
public function user()
{
    return $this->belongsTo(Users::class, 'staff','id');
}
public function services()
{
    return $this->belongsTo(Service::class, 'service', 'uniqueid');
}


}
