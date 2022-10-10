<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankList extends Model
{
    use HasFactory;

    public function balanceSheet()
    {
        return $this->hasMany(BalanceSheet::class,'bank_id','id');
    }
}
