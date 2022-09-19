<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;
    protected $table = "users";
    protected $fillable = [
        'name', 'email', 'password',"phone","role_id", "is_active", "is_deleted", "shop_name"
    ];
}
