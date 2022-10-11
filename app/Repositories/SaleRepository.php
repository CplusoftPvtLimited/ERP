<?php

namespace App\Repositories;

use App\Models\Ambrand;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use App\Models\NewSale;
use App\Models\NewSaleProduct;
use App\Models\StockManagement;
use App\Models\WhiteBlackQuantityManagement;
use App\ProductPurchase;
use App\Purchase;
use App\Repositories\Interfaces\SaleInterface;
use Carbon\Carbon;

class SaleRepository implements SaleInterface
{
    public function store($data)
    {
        // dd($data);
        $total_qty = 0;
        for ($i = 0; $i < count($data['item_qty']); $i++) {
            $total_qty += (int) $data['item_qty'][$i];
        }
        $newSale = NewSale::create([
            'date' => isset($data['date']) ? Carbon::parse($data['date'])->format('Y-m-d') : '',
            'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : '',
            'retailer_id' => auth()->user()->id,
            'cash_type' => isset($data['cash_type']) ? $data['cash_type'] : '',
            'entire_vat' => isset($data['entire_vat']) ? $data['entire_vat'] : '',
            'shipping_cost' => isset($data['shipping_cost']) ? $data['shipping_cost'] : '',
            'discount' => isset($data['sale_discount']) ? $data['sale_discount'] : '',
            'tax_stamp' => isset($data['tax_stamp']) ? $data['tax_stamp'] : '',
            'sale_note' => isset($data['sale_note']) ? $data['sale_note'] : '',
            'staff_note' => isset($data['staff_note']) ? $data['staff_note'] : '',
            'sale_entire_total_exculding_vat' => isset($data['sale_entire_total_exculding_vat']) ? $data['sale_entire_total_exculding_vat'] : '',
            'total_qty' => $total_qty,
            'total_bill' => isset($data['total_to_be_paid']) ? $data['total_to_be_paid'] : '',
        ]);
        $loop_iterations = count($data['item_qty']);
        for ($i = 0; $i < $loop_iterations; $i++) {
            $stock = StockManagement::where('retailer_id',auth()->user()->id)->where('reference_no',$data['article_number'][$i])->first();
            $sum_of_black_white = $stock->white_items + $stock->black_items;
            if($sum_of_black_white < $data['item_qty'][$i]){
                $res = [
                    'reference_no' => $data['article_number'][$i],
                    'message' => "quantity-exceeded"
                ];
                return $res;
            }
            $quantity_management = "";
            $quantity_management_true = false;
            if($data['cash_type'] == "white"){
                if($data['item_qty'][$i] <= $stock->white_items){
                    $stock->white_items = $stock->white_items - $data['item_qty'][$i];
                    $stock->update();
                }else{
                    $black_items_needed = $data['item_qty'][$i] - $stock->white_items;
                    
                    $quantity_management_true = true;
                    $quantity_management = WhiteBlackQuantityManagement::create([
                        'sale_id' => $newSale->id,
                        'black_quantity' => $black_items_needed,
                        'white_quantity' => $stock->white_items
                    ]);
                    $stock->white_items = $stock->white_items - $stock->white_items;
                    $stock->black_items = $stock->black_items - $black_items_needed;
                    $stock->update();
                }
            }else if($data['cash_type'] == "black"){
                if($data['item_qty'][$i] <= $stock->black_items){
                    $stock->black_items = $stock->black_items - $data['item_qty'][$i];
                    $stock->update();
                }else{
                    $white_items_needed = $data['item_qty'][$i] - $stock->black_items;
                    $stock->black_items = $stock->black_items - $stock->black_items;
                    $stock->white_items = $stock->white_items - $white_items_needed;
                    $stock->update();
 
                    // Creating Purchase

                    $purchase = new Purchase();
                    $total_qty = 0;
                    $total_amount = 0;
                    $total_to_be_paid = ($white_items_needed * $stock->unit_purchase_price_of_white_cash)/ $white_items_needed;
                    $purchase->user_id = auth()->user()->id;
                    $purchase->item = $white_items_needed;
                    $purchase->total_qty = $white_items_needed;
                    $purchase->total_cost = $total_to_be_paid;
                    $purchase->grand_total = $total_to_be_paid;
                    $purchase->supplier_id = NULL;
                    $purchase->cash_type = "white";
                    $purchase->additional_cost = 0;
                    $purchase->status = 0;
                    $purchase->sale_id = $newSale->id;
                    $purchase->date = date('Y-m-d');
                    $purchase->save();

                    $article = Article::where('articleNumber',$data['article_number'][$i])->first();
                    $section = AssemblyGroupNode::where('assemblyGroupNodeId',$article->assemblyGroupNodeId)->first();
                    $engine = LinkageTarget::where('linkageTargetId',isset($section) ? $section->request__linkingTargetId : 0)->first();
                    $model = ModelSeries::where('modelId',isset($engine) ? $engine->vehicleModelSeriesId : 0)->first();
                    $manufacturer = Manufacturer::where('manuId',isset($model) ? $model->manuId : 0)->first();
                    $brand = Ambrand::where('brandId',$article->dataSupplierid)->first();
                    // creating purchase product
                    $product_purchase = new ProductPurchase();
                    $product_purchase->purchase_id = $purchase->id;
                    $product_purchase->reference_no = $data['article_number'][$i];
                    $product_purchase->engine_details = $engine ? $engine->description : '';
                    $product_purchase->product_id = $article->legacyArticleId;
                    $product_purchase->qty = $white_items_needed;
                    $product_purchase->actual_price = $stock->unit_purchase_price_of_white_cash;
                    $product_purchase->sell_price = $stock->unit_sale_price_of_white_cash;
                    $product_purchase->manufacture_id = $manufacturer ? $manufacturer->manuId : '';
                    $product_purchase->model_id = $model ? $model->modelId : '';
                    $product_purchase->eng_linkage_target_id = $engine ? $engine->linkageTargetId : '';
                    $product_purchase->assembly_group_node_id = $section ? $section->assemblyGroupNodeId : '';
                    $product_purchase->legacy_article_id = $article->legacyArticleId;
                    $product_purchase->status = "ordered";
                    $product_purchase->supplier_id = 'N/A';
                    $product_purchase->linkage_target_type = $engine ? $engine->linkageTargetType : '';
                    $product_purchase->linkage_target_sub_type = $engine ? $engine->subLinkageTargetType : '';
                    $product_purchase->cash_type = "white";
                    $product_purchase->brand_id = $brand ? $brand->brandId : '0';
                    $product_purchase->discount = 0;
                    $product_purchase->additional_cost_without_vat = 0;
                    $product_purchase->additional_cost_with_vat = 0;
                    $product_purchase->vat = 0;
                    $product_purchase->profit_margin = 0;
                    $product_purchase->total_excluding_vat = ($white_items_needed * $stock->unit_purchase_price_of_white_cash);
                    $product_purchase->actual_cost_per_product = ($white_items_needed * $stock->unit_purchase_price_of_white_cash);
                    $date = date("Y-m-d");
                    $product_purchase->flag = 1;
                    $product_purchase->date = $date;
                    $product_purchase->save();
                }
            }

            $sale_product = NewSaleProduct::create([
                'sale_id' => $newSale->id,
                'reference_no' => isset($data['article_number']) ? $data['article_number'][$i] : '',
                'quantity' => isset($data['item_qty']) ? $data['item_qty'][$i] : 0,
                'sale_price' => isset($data['sale_price']) ? $data['sale_price'][$i] : 0,
                'discount' => isset($data['discount']) ? $data['discount'][$i] : 0,
                'vat' => isset($data['vat']) ? $data['vat'][$i] : 0,
                'total_with_discount' => isset($data['sale_total_with_discount']) ? $data['sale_total_with_discount'][$i] : 0,
                'total_without_discount' => isset($data['sale_total_without_discount']) ? $data['sale_total_without_discount'][$i] : 0,
            ]);

            if($data['cash_type'] == "white" && $quantity_management_true == true){
                $quantity_management->sale_item_id = $sale_product->id;
                $quantity_management->save();
            }
        }
        return true;
    }

    public function update($data,$id)
    {
        // dd($data);
        $sale = NewSale::find($id);
        $sale_products = NewSaleProduct::where('sale_id',$id)->get();
        if($sale->cash_type == "black"){
            
            $total_qty = 0;
            for ($i = 0; $i < count($data['sale_item_qty']); $i++) {
                $total_qty += (int) $data['sale_item_qty'][$i];
            }
            $sale->update([
                'entire_vat' => isset($data['entire_vat']) ? $data['entire_vat'] : '',
                'shipping_cost' => isset($data['shipping_cost']) ? $data['shipping_cost'] : '',
                'discount' => isset($data['sale_discount']) ? $data['sale_discount'] : '',
                'tax_stamp' => isset($data['tax_stamp']) ? $data['tax_stamp'] : '',
                'sale_note' => isset($data['sale_note']) ? $data['sale_note'] : '',
                'staff_note' => isset($data['staff_note']) ? $data['staff_note'] : '',
                'sale_entire_total_exculding_vat' => isset($data['sale_entire_total_exculding_vat']) ? $data['sale_entire_total_exculding_vat'] : '',
                'total_qty' => $total_qty,
                'total_bill' => isset($data['total_to_be_paid']) ? $data['total_to_be_paid'] : '',
            ]);
           
            for ($i = 0; $i < count($sale_products); $i++) {
                $purchase = Purchase::where('sale_id',$id)->first();
                $product_purchase = ProductPurchase::where('purchase_id',$purchase->id)->first();
                $sale_product = NewSaleProduct::where('sale_id',$id)->where('reference_no',$product_purchase->reference_no)->first();
                $white_item_quantity_for_black_sale = $purchase->total_qty;
                $black_item_quantity_for_black_sale = $sale_product->quantity - $white_item_quantity_for_black_sale;
                $stock = StockManagement::where('reference_no',$sale_product->reference_no)->first();
                $sum_of_quantities = $stock->white_items + $stock->black_items 
                                        + $white_item_quantity_for_black_sale + $black_item_quantity_for_black_sale;
                if($data['sale_item_qty'][$i] > $sum_of_quantities){
                    $res = [
                        'reference_no' => $sale_product->reference_no,
                        'message' => "quantity-exceeded"
                    ];
                    return $res;
                }
                if($data['sale_item_qty'][$i] <= $sum_of_quantities){
                    $stock->black_items = $stock->black_items + $black_item_quantity_for_black_sale;
                $stock->white_items = $stock->white_items + $white_item_quantity_for_black_sale;
                $stock->save();
                $product_purchase->delete();
                $purchase->delete();
                if($data['sale_item_qty'][$i] <= $stock->black_items){
                    $stock->black_items = $stock->black_items - $data['sale_item_qty'][$i];
                    $stock->save();
                }else{
                    $white_items_needed = $data['sale_item_qty'][$i] - $stock->black_items;
                    $stock->black_items = $stock->black_items - $stock->black_items;
                    $stock->white_items = $stock->white_items - $white_items_needed;
                    $stock->save();

                    // Creating Purchase

                    $purchase = new Purchase();
                    $total_qty = 0;
                    $total_amount = 0;
                    $total_to_be_paid = ($white_items_needed * $stock->unit_purchase_price_of_white_cash)/ $white_items_needed;
                    $purchase->user_id = auth()->user()->id;
                    $purchase->item = $white_items_needed;
                    $purchase->total_qty = $white_items_needed;
                    $purchase->total_cost = $total_to_be_paid;
                    $purchase->grand_total = $total_to_be_paid;
                    $purchase->supplier_id = NULL;
                    $purchase->cash_type = "white";
                    $purchase->additional_cost = 0;
                    $purchase->status = 0;
                    $purchase->sale_id = $sale->id;
                    $purchase->date = date('Y-m-d');
                    $purchase->save();

                    $article = Article::where('articleNumber',$stock->reference_no)->first();
                    $section = AssemblyGroupNode::where('assemblyGroupNodeId',$article->assemblyGroupNodeId)->first();
                    $engine = LinkageTarget::where('linkageTargetId',isset($section) ? $section->request__linkingTargetId : 0)->first();
                    $model = ModelSeries::where('modelId',isset($engine) ? $engine->vehicleModelSeriesId : 0)->first();
                    $manufacturer = Manufacturer::where('manuId',isset($model) ? $model->manuId : 0)->first();
                    $brand = Ambrand::where('brandId',$article->dataSupplierid)->first();
                    // creating purchase product
                    $product_purchase = new ProductPurchase();
                    $product_purchase->purchase_id = $purchase->id;
                    $product_purchase->reference_no = $stock->reference_no;
                    $product_purchase->engine_details = $engine ? $engine->description : '';
                    $product_purchase->product_id = $article->legacyArticleId;
                    $product_purchase->qty = $white_items_needed;
                    $product_purchase->actual_price = $stock->unit_purchase_price_of_white_cash;
                    $product_purchase->sell_price = $stock->unit_sale_price_of_white_cash;
                    $product_purchase->manufacture_id = $manufacturer ? $manufacturer->manuId : '';
                    $product_purchase->model_id = $model ? $model->modelId : '';
                    $product_purchase->eng_linkage_target_id = $engine ? $engine->linkageTargetId : '';
                    $product_purchase->assembly_group_node_id = $section ? $section->assemblyGroupNodeId : '';
                    $product_purchase->legacy_article_id = $article->legacyArticleId;
                    $product_purchase->status = "ordered";
                    $product_purchase->supplier_id = 'N/A';
                    $product_purchase->linkage_target_type = $engine ? $engine->linkageTargetType : '';
                    $product_purchase->linkage_target_sub_type = $engine ? $engine->subLinkageTargetType : '';
                    $product_purchase->cash_type = "white";
                    $product_purchase->brand_id = $brand ? $brand->brandId : '0';
                    $product_purchase->discount = 0;
                    $product_purchase->additional_cost_without_vat = 0;
                    $product_purchase->additional_cost_with_vat = 0;
                    $product_purchase->vat = 0;
                    $product_purchase->profit_margin = 0;
                    $product_purchase->total_excluding_vat = ($white_items_needed * $stock->unit_purchase_price_of_white_cash);
                    $product_purchase->actual_cost_per_product = ($white_items_needed * $stock->unit_purchase_price_of_white_cash);
                    $date = date("Y-m-d");
                    $product_purchase->flag = 1;
                    $product_purchase->date = $date;
                    $product_purchase->save();
                }
                
                $sale_products[$i]->update([
                    'quantity' => isset($data['sale_item_qty']) ? $data['sale_item_qty'][$i] : 0,
                    'sale_price' => isset($data['sale_price']) ? $data['sale_price'][$i] : 0,
                    'discount' => isset($data['discount']) ? $data['discount'][$i] : 0,
                    'vat' => isset($data['vat']) ? $data['vat'][$i] : 0,
                    'total_with_discount' => isset($data['sale_total_with_discount']) ? $data['sale_total_with_discount'][$i] : 0,
                    'total_without_discount' => isset($data['sale_total_without_discount']) ? $data['sale_total_without_discount'][$i] : 0,
                    ]);
                }
                
            }
            return true;
        }else{
            $total_qty = 0;
            for ($i = 0; $i < count($data['sale_item_qty']); $i++) {
                $total_qty += (int) $data['sale_item_qty'][$i];
            }
            

            

            for ($i = 0; $i < count($sale_products); $i++) {
                $manage_quantity = WhiteBlackQuantityManagement::where('sale_id',$id)->first();
                $sale_product = NewSaleProduct::find($manage_quantity->sale_item_id);
                $stock = StockManagement::where('retailer_id',auth()->user()->id)->where('reference_no',$sale_product->reference_no)->first();
                
                $overall_qty = $manage_quantity->white_quantity + $manage_quantity->black_quantity + $stock->white_items + $stock->black_items;
                $stock->white_items = $stock->white_items + $manage_quantity->white_quantity;
                $stock->black_items = $stock->black_items + $manage_quantity->black_quantity;
                
                if($data['sale_item_qty'][$i] > $overall_qty){
                    $res = [
                        'reference_no' => $sale_product->reference_no,
                        'message' => "quantity-exceeded"
                    ];
                    return $res;
                }
                $stock->save();
                $manage_quantity->delete();
                
                if($data['sale_item_qty'][$i] <= $stock->white_items){
                    $stock->white_items = $stock->white_items - $data['sale_item_qty'][$i];
                    $stock->save();
                }else{
                    $black_items_needed = $data['sale_item_qty'][$i] - $stock->white_items;

                    $quantity_management = WhiteBlackQuantityManagement::create([
                        'sale_id' => $sale->id,
                        'sale_item_id' => $sale_product->id,
                        'black_quantity' => $black_items_needed,
                        'white_quantity' => $stock->white_items
                    ]);
                    $stock->white_items = $stock->white_items - $stock->white_items;
                    $stock->black_items = $stock->black_items - $black_items_needed;
                    $stock->save();
                }
                
                $sale_products[$i]->update([
                    'quantity' => isset($data['sale_item_qty']) ? $data['sale_item_qty'][$i] : 0,
                    'sale_price' => isset($data['sale_price']) ? $data['sale_price'][$i] : 0,
                    'discount' => isset($data['discount']) ? $data['discount'][$i] : 0,
                    'vat' => isset($data['vat']) ? $data['vat'][$i] : 0,
                    'total_with_discount' => isset($data['sale_total_with_discount']) ? $data['sale_total_with_discount'][$i] : 0,
                    'total_without_discount' => isset($data['sale_total_without_discount']) ? $data['sale_total_without_discount'][$i] : 0,
                    ]);
            }
            
            $sale->update([
                'entire_vat' => isset($data['entire_vat']) ? $data['entire_vat'] : '',
                'shipping_cost' => isset($data['shipping_cost']) ? $data['shipping_cost'] : '',
                'discount' => isset($data['sale_discount']) ? $data['sale_discount'] : '',
                'tax_stamp' => isset($data['tax_stamp']) ? $data['tax_stamp'] : '',
                'sale_note' => isset($data['sale_note']) ? $data['sale_note'] : '',
                'staff_note' => isset($data['staff_note']) ? $data['staff_note'] : '',
                'sale_entire_total_exculding_vat' => isset($data['sale_entire_total_exculding_vat']) ? $data['sale_entire_total_exculding_vat'] : '',
                'total_qty' => $total_qty,
                'total_bill' => isset($data['total_to_be_paid']) ? $data['total_to_be_paid'] : '',
            ]);
            return true;
        }
        
    }
}
