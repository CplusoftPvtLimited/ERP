<?php

namespace App;

use App\Models\Ambrand;
use App\Models\Manufacturer;
use App\Models\StockManagement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPurchase extends Model
{
  use SoftDeletes;

  protected $table = 'product_purchases';
  // protected $fillable =[

  //     "purchase_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "recieved", "purchase_unit_id", "net_unit_cost", "discount", "tax_rate", "tax", "total"
  // ];
  protected $guarded = [];

  public function purchase(): belongsTo
  {
    return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
  }
  public function manufacture(): BelongsTo
  {
    return $this->belongsTo(Manufacturer::class, 'manufacture_id', 'manuId');
  }
  
  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Ambrand::class,'supplier_id','brandId');
  }
  public function productsStock(): HasOne
  {
    return $this->hasOne(StockManagement::class,'purchase_product_id','id');
  }

}
