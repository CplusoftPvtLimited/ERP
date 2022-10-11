<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ERPInvoiceProduct extends Model
{
    use HasFactory;

    protected $table = "erp_invoice_products";


    protected $guarded = [];
}
