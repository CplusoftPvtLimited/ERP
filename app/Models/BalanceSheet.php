<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bankList()
    {
        return $this->belongsTo(BankList::class,'bank_id','id');
    }
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class,'account_source','id');
    }
    public function balanceCategory()
    {
        return $this->belongsTo(BalanceCategory::class,'category_id','id');
    }
    public function afterMarketSupplier()
    {
        return $this->belongsTo(AfterMarkitSupplier::class,'supplier_id','id');
    }
}
