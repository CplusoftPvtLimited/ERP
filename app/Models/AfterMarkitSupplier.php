<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AfterMarkitSupplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function retailer():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function balanceSheet()
    {
        return $this->hasMany(BalanceSheet::class,'supplier_id','id');
    }
}
