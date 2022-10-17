<?php

namespace App\Repositories;

use App\Models\AfterMarkitSupplier;
use App\Models\Ambrand;
use App\Purchase;
use App\ProductPurchase;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\StockManagement;
use App\Models\ModelSeries;
use App\Repositories\Interfaces\PurchaseInterface;
use Exception;
use PDF;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use PhpParser\Node\Stmt\Catch_;

class PurchaseRepository implements PurchaseInterface
{
    public function store($request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $count_item = 0;
            for ($i = 0; $i < count($request->item_qty); $i++) {
                $count_item += $request->item_qty[$i];
            }
            if ($count_item == 0) {
                return "submit_purchase_not_allowed";
            }

            $purchase = new Purchase();
            $total_qty = 0;
            $total_amount = 0;

            $purchase->user_id = auth()->user()->id;
            $purchase->item = count($request->item_qty);
            $purchase->total_qty = $count_item;
            $purchase->total_cost = $request->total_to_be_paid;
            $purchase->grand_total = $request->total_to_be_paid;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->cash_type = $request->cash_type;
            $purchase->additional_cost = $request->purchase_additional_cost;
            $purchase->status = 0;
            $purchase->date = date('Y-m-d');
            $purchase->save();
            $document = $request->document;
            if ($document) {
                $documentName = $document->getClientOriginalName();
                $document->move('public/purchase/documents', $documentName);
                $purchase->document = $documentName;
                $purchase->save();
            }
            for ($i = 0; $i < count($request->item_qty); $i++) {
                $product_purchase = new ProductPurchase();
                $artcle = Article::where('legacyArticleId', $request->sectionn_part_id[$i])->withTrashed()->first();
                
                $linkage = LinkageTarget::where('linkageTargetId', $request->enginee_id[$i])->withTrashed()->first();
                if ($request->item_qty[$i] <= 0) {
                    continue;
                }
                $product_purchase->purchase_id = $purchase->id;
                $product_purchase->reference_no = $artcle->articleNumber;
                $product_purchase->engine_details = $linkage->description;
                $product_purchase->product_id = $request->sectionn_part_id[$i];
                $product_purchase->qty = $request->item_qty[$i];
                $product_purchase->actual_price = $request->purchase_price[$i];
                $product_purchase->sell_price = $request->sale_price[$i];
                $product_purchase->manufacture_id = $request->manufacturer_id[$i];
                $product_purchase->model_id = $request->modell_id[$i];
                $product_purchase->eng_linkage_target_id = $request->enginee_id[$i];
                $product_purchase->assembly_group_node_id = $request->sectionn_id[$i];
                $product_purchase->legacy_article_id = $request->sectionn_part_id[$i];
                $product_purchase->status = $request->statuss[$i];
                $product_purchase->supplier_id = $request->supplier_id;
                $product_purchase->linkage_target_type = $request->linkage_target_type[$i];
                $product_purchase->linkage_target_sub_type = $request->linkage_target_sub_type[$i];
                $product_purchase->cash_type = $request->cash_type;
                $product_purchase->brand_id = $request->brand_id[$i];
                $product_purchase->discount = $request->discount[$i];
                $product_purchase->additional_cost_without_vat = $request->additional_cost_without_vat[$i];
                $product_purchase->additional_cost_with_vat = !empty($request->additional_cost_with_vat) ? $request->additional_cost_with_vat[$i] : null;
                $product_purchase->vat = !empty($request->vat) ? $request->vat[$i] : null;
                $product_purchase->profit_margin = $request->profit_margin[$i];
                $product_purchase->total_excluding_vat = $request->total_excluding_vat[$i];
                $product_purchase->actual_cost_per_product = $request->actual_cost_per_product[$i];
                $date = date("Y-m-d", strtotime($request->datee[$i]));
                $product_purchase->date = $date;
                $product_purchase->save();

                if ($request->statuss[$i] == "received") {
                    $cash_type = $product_purchase->cash_type;
                    self::createStock($purchase,$product_purchase,$cash_type);
                }
            }
            DB::commit();
            return "true";
        } catch (\Exception $e) {
            DB::rollback();
            
            return $e->getMessage();
        }
    }

    public static function createStock($purchase,$product_purchase,$cash_type){
        $stock_exists = StockManagement::where('reference_no',$product_purchase->reference_no)->where('retailer_id',$purchase->user_id)->withTrashed()->first();
        if(!empty($stock_exists)){
            $stock_exists->update([
                'white_items' => ($cash_type == "white") ? $product_purchase->qty+$stock_exists->white_items : $stock_exists->white_items, 
                'black_items' => ($cash_type == "black") ? $product_purchase->qty+$stock_exists->black_items : $stock_exists->black_items,
                'unit_purchase_price_of_white_cash' => isset($product_purchase->actual_price) && $cash_type == "white" ? $product_purchase->actual_price : $stock_exists->unit_purchase_price_of_white_cash, // purchase price white
                'unit_purchase_price_of_black_cash' => isset($product_purchase->actual_price) && $cash_type == "black" ? $product_purchase->actual_price : $stock_exists->unit_purchase_price_of_black_cash, // purchase price black
                'unit_sale_price_of_white_cash' => isset($product_purchase->sell_price) && $cash_type == "white" ? $product_purchase->sell_price : $stock_exists->unit_sale_price_of_black_cash, // Sale price white
                'unit_sale_price_of_black_cash' => isset($product_purchase->sell_price) && $cash_type == "black" ? $product_purchase->sell_price : $stock_exists->unit_sale_price_of_black_cash, // Sale price black
                'total_qty' => isset($product_purchase->qty) ? $product_purchase->qty + $stock_exists->total_qty : $stock_exists->total_qty,
                'discount' => isset($product_purchase->discount) ? $product_purchase->discount : $stock_exists->total_qty,
                'additional_cost_without_vat' => isset($product_purchase->additional_cost_without_vat) ? $product_purchase->additional_cost_without_vat : $stock_exists->additional_cost_without_vat,
                'additional_cost_with_vat' => isset($product_purchase->additional_cost_with_vat) ? $product_purchase->additional_cost_with_vat : $stock_exists->additional_cost_with_vat,
                'vat' => isset($product_purchase->vat) ? $product_purchase->vat :  $stock_exists->vat,
                'profit_margin' => isset($product_purchase->profit_margin) ? $product_purchase->profit_margin : $stock_exists->profit_margin,
                'total_excluding_vat' => isset($product_purchase->total_excluding_vat) ? $product_purchase->total_excluding_vat : $stock_exists->total_excluding_vat,
                'actual_cost_per_product' => isset($product_purchase->actual_cost_per_product) ? $product_purchase->actual_cost_per_product : $stock_exists->actual_cost_per_product,
            ]);
        }else{
            StockManagement::create([
                'product_id' => isset($product_purchase->legacy_article_id) ? $product_purchase->legacy_article_id : null,
                'purchase_product_id' => isset($product_purchase->id) ? $product_purchase->id: null,
                'reference_no' => isset($product_purchase->reference_no) ? $product_purchase->reference_no : null,
                'retailer_id' => isset($purchase->user_id) ? $purchase->user_id : null,
                'white_items' => ($cash_type == "white") ? $product_purchase->qty : null, 
                'black_items' => ($cash_type == "black") ? $product_purchase->qty : null, 
                'unit_purchase_price_of_white_cash' => isset($product_purchase->actual_price) && $cash_type == "white" ? $product_purchase->actual_price : NULL, // purchase price white
                'unit_purchase_price_of_black_cash' => isset($product_purchase->actual_price) && $cash_type == "black" ? $product_purchase->actual_price : NULL, // purchase price black
                'unit_sale_price_of_white_cash' => isset($product_purchase->sell_price) && $cash_type == "white" ? $product_purchase->sell_price : NULL, // Sale price white
                'unit_sale_price_of_black_cash' => isset($product_purchase->sell_price) && $cash_type == "black" ? $product_purchase->sell_price : NULL, // Sale price black
                'total_qty' => isset($product_purchase->qty) ? $product_purchase->qty : null,
                'discount' => isset($product_purchase->discount) ? $product_purchase->discount : null,
                'additional_cost_without_vat' => isset($product_purchase->additional_cost_without_vat) ? $product_purchase->additional_cost_without_vat : null,
                'additional_cost_with_vat' => isset($product_purchase->additional_cost_with_vat) ? $product_purchase->additional_cost_with_vat : null,
                'vat' => isset($product_purchase->vat) ? $product_purchase->vat : null,
                'profit_margin' => isset($product_purchase->profit_margin) ? $product_purchase->profit_margin : null,
                'total_excluding_vat' => isset($product_purchase->total_excluding_vat) ? $product_purchase->total_excluding_vat : null,
                'actual_cost_per_product' => isset($product_purchase->actual_cost_per_product) ? $product_purchase->actual_cost_per_product : null,
            ]);
        }
    }
    public function view($id)
    {
        $purchase_get = Purchase::find($id);
        if ($purchase_get) {
            $purchase_products = [];
            $purchases_products = ProductPurchase::where('purchase_id', $purchase_get->id)->withTrashed()->get();
            foreach ($purchases_products as $lims_purchase_data) {
                $manufacturer = Manufacturer::where('manuId', $lims_purchase_data->manufacture_id)->withTrashed()->first();
                $model = ModelSeries::where('modelId', $lims_purchase_data->model_id)->withTrashed()->first();
                $engine = LinkageTarget::where('linkageTargetId', $lims_purchase_data->eng_linkage_target_id)->withTrashed()->first();
                $section = AssemblyGroupNode::where('assemblyGroupNodeId', $lims_purchase_data->assembly_group_node_id)->withTrashed()->first();
                $section_part = Article::where('legacyArticleId', $lims_purchase_data->legacy_article_id)->withTrashed()->first();
                $brand = Ambrand::where('BrandId',$section_part ? $section_part->dataSupplierId : 0)->withTrashed()->first();

                $supplier = Ambrand::where('BrandId', $lims_purchase_data->supplier_id)->withTrashed()->first();
                $lims_purchase_data['manufacturer'] = isset($manufacturer->manuName) ? $manufacturer->manuName : '';
                $lims_purchase_data['model'] = isset($model->modelname) ? $model->modelname : '';
                $lims_purchase_data['engine'] = isset($engine->description) ? $engine->description : '';
                $lims_purchase_data['section'] = isset($section->assemblyGroupName) ? $section->assemblyGroupName : '';
                $lims_purchase_data['section_part'] = isset($section_part->articleNumber) ? $section_part->articleNumber : '';
                $lims_purchase_data['supplier'] = isset($supplier->name) ? $supplier->name : '';
                $lims_purchase_data['brand'] = isset($brand->brandName) ? $brand->brandName : '';

                array_push($purchase_products, $lims_purchase_data);
            }
            $purchase = [
                'purchase' => $purchase_get,
                'purchase_products' => $purchase_products
            ];
            return $purchase;
        } else {
            return "null";
        }
    }

    public function edit($id)
    {
        $purchase_get = Purchase::find($id);
        if (!empty($purchase_get)) {
            $purchase_products = [];
            $purchases_products = ProductPurchase::where('purchase_id', $purchase_get->id)->withTrashed()->get();
            
            foreach ($purchases_products as $lims_purchase_data) {
                $manufacturer = Manufacturer::where('manuId', $lims_purchase_data->manufacture_id)->withTrashed()->first();
                $model = ModelSeries::where('modelId', $lims_purchase_data->model_id)->withTrashed()->first();
                $engine = LinkageTarget::where('linkageTargetId', $lims_purchase_data->eng_linkage_target_id)->withTrashed()->first();
                $section = AssemblyGroupNode::where('assemblyGroupNodeId', $lims_purchase_data->assembly_group_node_id)->withTrashed()->first();
                $section_part = Article::where('legacyArticleId', $lims_purchase_data->legacy_article_id)->withTrashed()->first();
                $brand = Ambrand::where('BrandId',$section_part ? $section_part->dataSupplierId : 0)->withTrashed()->first();
                $supplier = AfterMarkitSupplier::where('id', $lims_purchase_data->supplier_id)->withTrashed()->first();
                $lims_purchase_data['manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                $lims_purchase_data['model'] = isset($model) ? $model->modelname : '';
                $lims_purchase_data['engine'] = isset($engine) ? $engine->description : '';
                $lims_purchase_data['section'] = isset($section) ? $section->assemblyGroupName : '';
                $lims_purchase_data['section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                $lims_purchase_data['brand'] = isset($brand) ? $brand->brandName : '';
                $lims_purchase_data['supplier'] = isset($supplier) ? $supplier->name : '';
                array_push($purchase_products, $lims_purchase_data);
            }
            $purchase = [
                'purchase' => $purchase_get,
                'purchase_products' => $purchase_products
            ];
            return $purchase;
        } else {
            return "null";
        }
    }

    public function updatePurchase($request)
    {
        try {
            $product_purchase = ProductPurchase::find($request->id);
            DB::beginTransaction();
            if (!empty($product_purchase)) {
                $purchase = Purchase::find($product_purchase->purchase_id);
                $stock = StockManagement::where('retailer_id', auth()->user()->id)->where('reference_no', $product_purchase->reference_no)->first();
                $product_purchase->update([
                    'status' => $request->status
                ]);
                if (!empty($stock)) {
                    $stock->update([
                        'white_items' => (!empty($purchase->cash_type) && $purchase->cash_type == "white") ? $stock->white_items + $product_purchase->qty : $stock->white_items,
                        'black_items' => (!empty($purchase->cash_type) && $purchase->cash_type == "black") ? $stock->black_items + $product_purchase->qty : $stock->black_items,
                        'unit_purchase_price_of_white_cash' => isset($product_purchase->actual_price) && $purchase->cash_type == "white" ? $product_purchase->actual_price : $stock->unit_purchase_price_of_white_cash, // purchase price white
                        'unit_purchase_price_of_black_cash' => isset($product_purchase->actual_price) && $purchase->cash_type == "black" ? $product_purchase->actual_price : $stock->unit_purchase_price_of_black_cash, // purchase price black
                        'unit_sale_price_of_white_cash' => isset($product_purchase->sell_price) && $purchase->cash_type == "white" ? $product_purchase->sell_price : $stock->unit_sale_price_of_black_cash, // Sale price white
                        'unit_sale_price_of_black_cash' => isset($product_purchase->sell_price) && $purchase->cash_type == "black" ? $product_purchase->sell_price : $stock->unit_sale_price_of_black_cash, // Sale price black
                        'total_qty' => ( $stock->total_qty + $product_purchase->qty),
                    ]);
                } else {
                    StockManagement::create([
                        'product_id' => $product_purchase->legacy_article_id,
                        'purchase_product_id' => $product_purchase->id,
                        'reference_no' => $product_purchase->reference_no,
                        'retailer_id' => $purchase->user_id,
                        'white_items' => (!empty($purchase->cash_type) && $purchase->cash_type == "white") ? $product_purchase->qty : null,
                        'black_items' => (!empty($purchase->cash_type) && $purchase->cash_type == "black") ? $product_purchase->qty : null,
                        'unit_purchase_price_of_white_cash' => isset($product_purchase->actual_price) && $purchase->cash_type == "white" ? $product_purchase->actual_price : NULL, // purchase price white
                        'unit_purchase_price_of_black_cash' => isset($product_purchase->actual_price) && $purchase->cash_type == "black" ? $product_purchase->actual_price : NULL, // purchase price black
                        'unit_sale_price_of_white_cash' => isset($product_purchase->sell_price) && $purchase->cash_type == "white" ? $product_purchase->sell_price : NULL, // Sale price white
                        'unit_sale_price_of_black_cash' => isset($product_purchase->sell_price) && $purchase->cash_type == "black" ? $product_purchase->sell_price : NULL, // Sale price black
                        'total_qty' => $product_purchase->qty,
                        'discount' => isset($product_purchase->discount) ? $product_purchase->discount : null,
                        'additional_cost_without_vat' => isset($product_purchase->additional_cost_without_vat) ? $product_purchase->additional_cost_without_vat : null,
                        'additional_cost_with_vat' => isset($product_purchase->additional_cost_with_vat) ? $product_purchase->additional_cost_with_vat : null,
                        'vat' => isset($product_purchase->vat) ? $product_purchase->vat : null,
                        'profit_margin' => isset($product_purchase->profit_margin) ? $product_purchase->profit_margin : null,
                        'total_excluding_vat' => isset($product_purchase->total_excluding_vat) ? $product_purchase->total_excluding_vat : null,
                        'actual_cost_per_product' => isset($product_purchase->actual_cost_per_product) ? $product_purchase->actual_cost_per_product : null,
                    ]);
                }
                DB::commit();
                return true;
            }
            return false;
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }


    public function updatePurchaseProductQuantity($request){
        try {
            $product_purchase = ProductPurchase::find($request->id);
            DB::beginTransaction();
            
                $purchase = Purchase::where('id',$product_purchase->purchase_id)->with(['productPurchases'=>function($query) use ($product_purchase){
                    $query->select(['qty','purchase_id','actual_cost_per_product'])->where('id','!=',$product_purchase->id);
                }])->withTrashed()->first();

                if(!empty($purchase)){
                    $sum = 0;
                    $grand_total = 0;
                    if(isset($purchase->productPurchases) && count($purchase->productPurchases) > 0){
                        foreach ($purchase->productPurchases as $key => $item) {
                            $sum += $item->qty;
                            $grand_total += $item->actual_cost_per_product;
                        }
                    }
                    $sum = $sum + $request->quantity;
                    $total_without_vat = ($product_purchase->actual_price * $request->quantity) + $product_purchase->additional_cost_without_vat;
                    $actual_price_per_product = ($total_without_vat / $request->quantity) + ($purchase->additional_cost / $sum);
                    $sale_price = $actual_price_per_product * (1 + $product_purchase->profit_margin);

                    $grand_total = $grand_total + round($actual_price_per_product,2);
                    $product_purchase->qty = $request->quantity;
                    $product_purchase->total_excluding_vat = round($total_without_vat,2);
                    $product_purchase->actual_cost_per_product = round($actual_price_per_product,2);
                    $product_purchase->sell_price = round($sale_price,2);
                    $product_purchase->update();
    
                    $purchase->total_qty = $sum;
                    $purchase->total_cost = round($grand_total,2);
                    $purchase->grand_total = round($grand_total,2);
                    $purchase->update();
                    DB::commit();
                    return "update";
                }else{
                    return "purchase_no_found";
                }
                
              
            }catch(Exception $e){
                DB::rollBack();

                return $e->getMessage();
            }
    }




    public function deletePurchaseProduct($purchase_id, $id)
    {
        $product_purchase = ProductPurchase::find($id);
        
        if ($product_purchase) {
            // $product_stock = $product_purchase->productsStock;
            $purchase = Purchase::find($purchase_id);
            $purchase->total_qty = $purchase->total_qty - ($product_purchase->white_item_qty + $product_purchase->black_item_qty);
            $purchase->grand_total = $purchase->grand_total - $product_purchase->actual_price;
            $purchase->save();
            
            $product_purchase->delete();
            $product_purchases = ProductPurchase::where('purchase_id', $purchase_id)->withTrashed()->get();

            if (count($product_purchases) <= 0) {

                if ($purchase) {
                    $purchase->delete();
                }
            }
            return "true";
        } else {
            return "false";
        }
    }

    public function deleteParentPurchase($purchase_id)
    {
        $purchase_with_products = Purchase::where('id', $purchase_id)->with(['productPurchases'])->withTrashed()->first();
        if (!empty($purchase_with_products)) {
            foreach ($purchase_with_products->productPurchases as $key => $product) {
                $product->delete();
            }

            $purchase_with_products->delete();
            return "true";
        } else {
            return "false";
        }
    }


    public function exportPurchases()
    {

        $purchase_with_products = Purchase::with(['productPurchases'])->where('user_id',auth()->user()->id)->get();
        $all_data = [];
        if (count($purchase_with_products) > 0) {
            foreach ($purchase_with_products as $purchase) {
                $purchase_data = [];
                $purchase_data['Retailer ID'] = auth()->user()->id;
                $purchase_data['Retailer'] = auth()->user()->name;
                $purchase_data['Total Quantity'] = $purchase->total_qty;
                $purchase_data['Total Items'] = $purchase->item;
                $purchase_data['Grand Total'] = $purchase->grand_total;
                foreach ($purchase->productPurchases as $key => $lims_purchase_data) {
                    $manufacturer = Manufacturer::where('manuId', $lims_purchase_data->manufacture_id)->withTrashed()->first();
                    $model = ModelSeries::where('modelId', $lims_purchase_data->model_id)->withTrashed()->first();
                    $engine = LinkageTarget::where('linkageTargetId', $lims_purchase_data->eng_linkage_target_id)->withTrashed()->first();
                    $section = AssemblyGroupNode::where('assemblyGroupNodeId', $lims_purchase_data->assembly_group_node_id)->withTrashed()->first();
                    $section_part = Article::where('legacyArticleId', $lims_purchase_data->legacy_article_id)->withTrashed()->first();
                    $supplier = Ambrand::where('BrandId', $lims_purchase_data->supplier_id)->withTrashed()->first();

                    $purchase_data['Manufacturer ID'] = isset($manufacturer) ? $manufacturer->manuId : '';
                    $purchase_data['Manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                    $purchase_data['Model ID'] = isset($model) ? $model->modelId : '';
                    $purchase_data['Model'] = isset($model) ? $model->modelname : '';
                    $purchase_data['Engine ID'] = isset($engine) ? $engine->linkageTargetId : '';
                    $purchase_data['Engine'] = isset($engine) ? $engine->description : '';
                    $purchase_data['Section ID'] = isset($section) ? $section->assemblyGroupNodeId : '';
                    $purchase_data['Section'] = isset($section) ? $section->assemblyGroupName : '';
                    $purchase_data['Section Part ID'] = isset($section_part) ? $section_part->legacyArticleId : '';
                    $purchase_data['Section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                    $purchase_data['Supplier ID'] = isset($supplier) ? $supplier->brandId : '';
                    $purchase_data['Supplier'] = isset($supplier) ? $supplier->brandName : '';
                    $purchase_data['White Item'] = $lims_purchase_data->white_item_qty;
                    $purchase_data['Black Item'] = $lims_purchase_data->black_item_qty;
                    $purchase_data['Total Cost'] = $lims_purchase_data->total_cost;
                    $purchase_data['Engine Detail'] = $lims_purchase_data->engine_details;
                    $purchase_data['Engine Type'] = $lims_purchase_data->linkage_target_type;
                    $purchase_data['Engine Sub-Type'] = $lims_purchase_data->linkage_target_sub_type;

                    array_push($all_data, $purchase_data);
                }
            }

            return $all_data;
        } else {
            return "false";
        }
    }

    public function pdfDownload()
    {

        try {

            $purchases = Purchase::all();
            $all_data = [];
            if (count($purchases) > 0) {
                foreach ($purchases as $purchase_get) {
                    $purchase_products = [];
                    $purchases_products = ProductPurchase::where('purchase_id', $purchase_get->id)->get();
                    foreach ($purchases_products as $lims_purchase_data) {
                        $manufacturer = Manufacturer::where('manuId', $lims_purchase_data->manufacture_id)->withTrashed()->first();
                        $model = ModelSeries::where('modelId', $lims_purchase_data->model_id)->withTrashed()->first();
                        $engine = LinkageTarget::where('linkageTargetId', $lims_purchase_data->eng_linkage_target_id)->withTrashed()->first();
                        $section = AssemblyGroupNode::where('assemblyGroupNodeId', $lims_purchase_data->assembly_group_node_id)->withTrashed()->first();
                        $section_part = Article::where('legacyArticleId', $lims_purchase_data->legacy_article_id)->withTrashed()->first();
                        $supplier = Ambrand::where('BrandId', $lims_purchase_data->supplier_id)->withTrashed()->first();

                        $lims_purchase_data['manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                        $lims_purchase_data['model'] = isset($model) ? $model->modelname : '';
                        $lims_purchase_data['engine'] = isset($engine) ? $engine->description : '';
                        $lims_purchase_data['section'] = isset($section) ? $section->assemblyGroupName : '';
                        $lims_purchase_data['section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                        $lims_purchase_data['supplier'] = isset($supplier) ? $supplier->brandName : '';
                        
                        array_push($purchase_products, $lims_purchase_data);
                    }
                    $purchase = [
                        'purchase' => $purchase_get,
                        'purchase_products' => $purchase_products
                    ];
                    array_push($all_data, $purchase);
                }

                $pdf = PDF::loadView('purchase.purchase_pdf', $all_data);
                return $pdf->download('product_purchases.pdf');
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
