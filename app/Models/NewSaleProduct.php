<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewSaleProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded =[];

    public function sale():BelongsTo
    {
        return $this->belongsTo(NewSale::class,'sale_id','id');
    }
}
