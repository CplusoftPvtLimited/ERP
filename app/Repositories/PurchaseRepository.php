<?php

namespace App\Repositories;

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
use Illuminate\Support\Facades\DB;

class PurchaseRepository implements PurchaseInterface
{
    public function store($request){
        DB::beginTransaction();
        try {
            // dd($request->all());
            $purchase = new Purchase();
            $total_qty = 0;
            $total_amount = 0;
            for($i=0; $i < count($request->black_qty); $i++){
                $total_qty = $total_qty + ($request->black_qty[$i] + $request->white_qty[$i]);
            }
            for($i=0; $i < count($request->purchase_price); $i++){
                $total_amount = $total_amount + ($request->purchase_price[$i]);
            }

            $purchase->user_id = auth()->user()->id;
            $purchase->item = count($request->black_qty);
            $purchase->total_qty = $total_qty;
            $purchase->total_cost = $total_amount;
            $purchase->grand_total = $total_amount;
            $purchase->supplier_id = $request->supplier_id;
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
        
            for($i=0; $i < count($request->black_qty); $i++){
                $product_purchase = new ProductPurchase();
                $artcle = Article::where('legacyArticleId',$request->sectionn_part_id[$i])->first();
                $linkage = LinkageTarget::where('linkageTargetId',$request->enginee_id[$i])->first();
                $product_purchase->purchase_id = $purchase->id;
                $product_purchase->reference_no = $artcle->articleNumber;
                $product_purchase->engine_details = $linkage->description;
                $product_purchase->product_id = $request->sectionn_part_id[$i];
                $product_purchase->black_item_qty = $request->black_qty[$i];
                $product_purchase->white_item_qty = $request->white_qty[$i];
                $product_purchase->actual_price = $request->purchase_price[$i];
                $product_purchase->sell_price = $request->sale_price[$i];
                $product_purchase->manufacture_id = $request->manufacturer_id[$i];
                $product_purchase->model_id = $request->modell_id[$i];
                $product_purchase->eng_linkage_target_id = $request->enginee_id[$i];
                $product_purchase->assembly_group_node_id = $request->sectionn_id[$i];
                $product_purchase->legacy_article_id = $request->sectionn_part_id[$i];
                $product_purchase->status = $request->statuss[$i];
                $product_purchase->supplier_id = $request->supplier_id;
                $product_purchase->total_cost = $request->total_price[$i];

                $date = date("Y-m-d", strtotime($request->datee[$i]));
                $product_purchase->date = $date;
                $product_purchase->qty = $request->black_qty[$i] + $request->white_qty[$i];
                $product_purchase->save();
            }

            DB::commit();
            return "true";

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }    

    public function view($id){
        $purchase_get = Purchase::find($id);

        if($purchase_get){
            $purchase_products = [];
            $purchases_products = ProductPurchase::where('purchase_id',$purchase_get->id)->get();
            foreach($purchases_products as $lims_purchase_data){
                $manufacturer = Manufacturer::where('manuId',$lims_purchase_data->manufacture_id)->first();
                $model = ModelSeries::where('modelId',$lims_purchase_data->model_id)->first();
                $engine = LinkageTarget::where('linkageTargetId',$lims_purchase_data->eng_linkage_target_id)->first();
                $section = AssemblyGroupNode::where('assemblyGroupNodeId',$lims_purchase_data->assembly_group_node_id)->first();
                $section_part = Article::where('legacyArticleId',$lims_purchase_data->legacy_article_id)->first();
                // dd($lims_purchase_data->legacy_article_id);
                $supplier = Ambrand::where('BrandId',$lims_purchase_data->supplier_id)->first();

                $lims_purchase_data['manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                $lims_purchase_data['model'] = isset($model) ? $model->modelname : '';
                $lims_purchase_data['engine'] = isset($engine) ? $engine->description : '';
                $lims_purchase_data['section'] = isset($section) ? $section->assemblyGroupName : '';
                $lims_purchase_data['section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                $lims_purchase_data['supplier'] = isset($supplier) ? $supplier->brandName : '';

                array_push($purchase_products,$lims_purchase_data);
            }
            $purchase = [
                'purchase' => $purchase_get,
                'purchase_products' => $purchase_products
            ];
             return $purchase;
        }else{
            return "null";
        }
    }

    public function edit($id){
        $purchase_get = Purchase::find($id);

        if($purchase_get){
            $purchase_products = [];
            $purchases_products = ProductPurchase::where('purchase_id',$purchase_get->id)->get();
            foreach($purchases_products as $lims_purchase_data){
                $manufacturer = Manufacturer::where('manuId',$lims_purchase_data->manufacture_id)->first();
                $model = ModelSeries::where('modelId',$lims_purchase_data->model_id)->first();
                $engine = LinkageTarget::where('linkageTargetId',$lims_purchase_data->eng_linkage_target_id)->first();
                $section = AssemblyGroupNode::where('assemblyGroupNodeId',$lims_purchase_data->assembly_group_node_id)->first();
                $section_part = Article::where('legacyArticleId',$lims_purchase_data->legacy_article_id)->first();
                $supplier = Ambrand::where('BrandId',$lims_purchase_data->supplier_id)->first();

                $lims_purchase_data['manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                $lims_purchase_data['model'] = isset($model) ? $model->modelname : '';
                $lims_purchase_data['engine'] = isset($engine) ? $engine->description : '';
                $lims_purchase_data['section'] = isset($section) ? $section->assemblyGroupName : '';
                $lims_purchase_data['section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                $lims_purchase_data['supplier'] = isset($supplier) ? $supplier->brandName : '';

                array_push($purchase_products,$lims_purchase_data);
            }
            $purchase = [
                'purchase' => $purchase_get,
                'purchase_products' => $purchase_products
            ];
             return $purchase;
        }else{
            return "null";
        }
    }

    public function updatePurchase($request){
        
        $product_purchase = ProductPurchase::find($request->id);
        DB::beginTransaction();
        if($product_purchase){
            $product_purchase->status = $request->status;
            $product_purchase->save();
            $purchase = Purchase::find($product_purchase->purchase_id);
            $stock_management = new StockManagement();
            $stock_management->product_id = $product_purchase->legacy_article_id;
            $stock_management->reference_no = $product_purchase->reference_no;
            $stock_management->retailer_id = $purchase->user_id;
            $stock_management->white_items = $product_purchase->white_item_qty;
            $stock_management->black_items = $product_purchase->black_item_qty;
            $stock_management->actual_price = $product_purchase->actual_price;
            $stock_management->sale_price = $product_purchase->sell_price;
            $stock_management->total_qty = $product_purchase->qty;
            $stock_management->save();
            DB::commit();
            return "true";
        }else{
            DB::rollBack();
            return "false";
        }
    }


    public function deletePurchaseProduct($purchase_id,$id){
        $product_purchase = ProductPurchase::find($id);
        if($product_purchase){
            
            $product_purchase->delete();
            $product_purchases = ProductPurchase::where('purchase_id',$purchase_id)->get();
            if(count($product_purchases) <= 0){
                $purchase = Purchase::find($purchase_id);
                if($purchase){
                    $purchase->delete();
                }
            }
            return "true";
        }else{
            return "false";
        }
    }

    public function deleteParentPurchase($purchase_id){
        $purchase_with_products = Purchase::where('id',$purchase_id)->with(['productPurchases'])->first();
        // dd($purchase_with_products);
        if(!empty($purchase_with_products)){
                 foreach ($purchase_with_products->productPurchases as $key => $product) {
                    $product->delete();
                 }

                 $purchase_with_products->delete();
                 return "true";
        }else{
            return "false";
        }
    }


    public function exportPurchases(){

        $purchase_with_products = Purchase::with(['productPurchases'])->get();
        // dd($purchase_with_products);
        $all_data = [];
        if(count($purchase_with_products) > 0){
                foreach($purchase_with_products as $purchase){
                    $purchase_data = [];
                    $purchase_data['Retailer ID'] = auth()->user()->id;
                    $purchase_data['Retailer'] = auth()->user()->name;
                    $purchase_data['Total Quantity'] = $purchase->total_qty;
                    $purchase_data['Total Items'] = $purchase->item;
                    $purchase_data['Grand Total'] = $purchase->grand_total;
                    foreach ($purchase->productPurchases as $key => $lims_purchase_data) {
                        $manufacturer = Manufacturer::where('manuId',$lims_purchase_data->manufacture_id)->first();
                        $model = ModelSeries::where('modelId',$lims_purchase_data->model_id)->first();
                        $engine = LinkageTarget::where('linkageTargetId',$lims_purchase_data->eng_linkage_target_id)->first();
                        $section = AssemblyGroupNode::where('assemblyGroupNodeId',$lims_purchase_data->assembly_group_node_id)->first();
                        $section_part = Article::where('legacyArticleId',$lims_purchase_data->legacy_article_id)->first();
                        $supplier = Ambrand::where('BrandId',$lims_purchase_data->supplier_id)->first();

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

                        array_push($all_data,$purchase_data);
                    }
                }
                
                 return $all_data;
        }else{
            return "false";
        }
    }

    public function pdfDownload(){}
}
