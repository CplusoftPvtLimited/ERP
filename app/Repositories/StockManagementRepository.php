<?php

namespace App\Repositories;

use App\Purchase;
use App\ProductPurchase;
use App\Models\Article;
use App\Models\LinkageTarget;
use App\Models\StockManagement;
use App\Repositories\Interfaces\StockManagementInterface;
use Illuminate\Support\Facades\DB;

class StockManagementRepository implements StockManagementInterface
{
    // public function store($product_purchase, $purchase)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $stock_management = new StockManagement();
    //         $stock_management->product_id = $product_purchase->legacy_article_id;
    //         $stock_management->reference_no = $product_purchase->reference_no;
    //         $stock_management->retailer_id = $purchase->user_id;
    //         $stock_management->white_items = $product_purchase->white_item_qty;
    //         $stock_management->black_items = $product_purchase->black_item_qty;
    //         $stock_management->actual_price = $product_purchase->actual_price;
    //         $stock_management->sale_price = $product_purchase->sell_price;
    //         $stock_management->total_qty = $product_purchase->qty;
    //         $stock_management->save();
    //         return $stock_management;
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return $e->getMessage();
    //     }
    // }
}
