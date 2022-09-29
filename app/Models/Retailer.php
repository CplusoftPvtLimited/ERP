<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retailer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "users";
    protected $fillable = [
        'name', 'email', 'password',"phone","role_id", "is_active", "is_deleted", "shop_name"
    ];
}
