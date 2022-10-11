<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ERPInvoice extends Model
{
    use HasFactory;
    protected $table = "erp_invoices";


    protected $guarded =[];

    public function invoiceProducts(): HasMany
    {
        return $this->hasMany(ERPInvoiceProduct::class,'invoice_id','id');
    }
}
