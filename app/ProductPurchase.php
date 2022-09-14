<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPurchase extends Model
{
    use SoftDeletes;

    protected $table = 'product_purchases';
    // protected $fillable =[

    //     "purchase_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "recieved", "purchase_unit_id", "net_unit_cost", "discount", "tax_rate", "tax", "total"
    // ];
    protected $guarded =[];

    public function purchase() : belongsTo
    { 
      return $this->belongsTo(Purchase::class,'purchase_id','id'); 
    }
}

