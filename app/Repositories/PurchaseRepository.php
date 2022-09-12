<?php

namespace App\Repositories;

use App\Models\Ambrand;
use App\Purchase;
use App\ProductPurchase;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use App\Repositories\Interfaces\PurchaseInterface;
use Illuminate\Support\Facades\DB;

class PurchaseRepository implements PurchaseInterface
{
    public function store($request){
        DB::beginTransaction();
        try {
            $purchase = new Purchase();
            $total_qty = 0;
            $total_amount = 0;
            for($i=0; $i < count($request->black_qty); $i++){
                $total_qty = $total_qty + ($request->black_qty[$i] + $request->white_qty[$i]);
            }
            for($i=0; $i < count($request->purchase_price); $i++){
                $total_amount = $total_amount + ($request->purchase_price[$i]) + ($request->sale_price[$i]);
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
                $product_purchase->product_id = $request->section_part_id[$i];
                $product_purchase->black_item_qty = $request->black_qty[$i];
                $product_purchase->white_item_qty = $request->white_qty[$i];
                $product_purchase->actual_price = $request->purchase_price[$i];
                $product_purchase->sell_price = $request->sale_price[$i];
                $product_purchase->manufacture_id = $request->manufacturer_id[$i];
                $product_purchase->model_id = $request->modell_id[$i];
                $product_purchase->eng_linkage_target_id = $request->enginee_id[$i];
                $product_purchase->assembly_group_node_id = $request->sectionn_id[$i];
                $product_purchase->legacy_article_id = $request->section_part_id[$i];
                $product_purchase->status = $request->statuss[$i];
                $product_purchase->supplier_id = $request->supplier_id;
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
                $section_part = Article::where('legacyArticleId',$lims_purchase_data->legacyArticleId)->first();
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
                $section_part = Article::where('legacyArticleId',$lims_purchase_data->legacyArticleId)->first();
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
        if($product_purchase){
            $product_purchase->status = $request->status;
            $product_purchase->save();

            return "true";
        }else{
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
}
