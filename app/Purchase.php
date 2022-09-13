<?php

namespace App;

use App\Models\Ambrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $fillable =[

        "reference_no", "user_id", "warehouse_id", "supplier_id", "item", "total_qty", "total_discount", "total_tax", "total_cost", "order_tax_rate", "order_tax", "order_discount", "shipping_cost", "grand_total","paid_amount", "status", "payment_status", "document", "note", "created_at"
    ];

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }
    public function brand()
    {
    	return $this->belongsTo(Ambrand::class,'supplier_id','brandId');
    }
    

    public function warehouse()
    {
    	return $this->belongsTo('App\Warehouse');
    }

    public function productPurchases() :HasMany
    {
        return  $this->hasMany(ProductPurchase::class,'purchase_id','id');
    }
}
