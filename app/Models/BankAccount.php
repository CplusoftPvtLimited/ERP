<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function balanceSheet()
    {
        return $this->hasMany(BalanceSheet::class,'account_source','id');
    }
    public function bankList()
    {
        return $this->belongsTo(BankList::class,'bank_id','id');
    }
}
