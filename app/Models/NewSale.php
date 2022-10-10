<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewSale extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded =[];

    public function saleProducts(): HasMany
    {
        return $this->hasMany(NewSaleProduct::class,'sale_id','id');
    }
}
