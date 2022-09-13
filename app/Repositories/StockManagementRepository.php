<?php

namespace App\Repositories;
use App\Purchase;
use App\ProductPurchase;
use App\Models\Article;
use App\Models\LinkageTarget;
use App\Repositories\Interfaces\StockManagementInterface;
use Illuminate\Support\Facades\DB;

class StockManagementRepository implements StockManagementInterface
{
    // public function store($request){
    //     DB::beginTransaction();
    //     try {
    //         // dd($request->all());
    //         $purchase = new Purchase();
    //         $total_qty = 0;
    //         $total_amount = 0;
    //         for($i=0; $i < count($request->black_qty); $i++){
    //             $total_qty = $total_qty + ($request->black_qty[$i] + $request->white_qty[$i]);
    //         }
    //         for($i=0; $i < count($request->purchase_price); $i++){
    //             $total_amount = $total_amount + ($request->purchase_price[$i]);
    //         }

    //         $purchase->user_id = auth()->user()->id;
    //         $purchase->item = count($request->black_qty);
    //         $purchase->total_qty = $total_qty;
    //         $purchase->total_cost = $total_amount;
    //         $purchase->grand_total = $total_amount;
    //         $purchase->supplier_id = $request->supplier_id;
    //         $purchase->status = 0;

    //         $purchase->date = date('Y-m-d');
    //         $purchase->save();
    //         $document = $request->document;
    //         if ($document) {
    //             $documentName = $document->getClientOriginalName();
    //             $document->move('public/purchase/documents', $documentName);
    //             $purchase->document = $documentName;
    //             $purchase->save();
    //         }
        
    //         for($i=0; $i < count($request->black_qty); $i++){
    //             $product_purchase = new ProductPurchase();
    //             $artcle = Article::where('legacyArticleId',$request->sectionn_part_id[$i])->first();
    //             $linkage = LinkageTarget::where('linkageTargetId',$request->enginee_id[$i])->first();
    //             $product_purchase->purchase_id = $purchase->id;
    //             $product_purchase->reference_no = $artcle->articleNumber;
    //             $product_purchase->engine_details = $linkage->description;
    //             $product_purchase->product_id = $request->sectionn_part_id[$i];
    //             $product_purchase->black_item_qty = $request->black_qty[$i];
    //             $product_purchase->white_item_qty = $request->white_qty[$i];
    //             $product_purchase->actual_price = $request->purchase_price[$i];
    //             $product_purchase->sell_price = $request->sale_price[$i];
    //             $product_purchase->manufacture_id = $request->manufacturer_id[$i];
    //             $product_purchase->model_id = $request->modell_id[$i];
    //             $product_purchase->eng_linkage_target_id = $request->enginee_id[$i];
    //             $product_purchase->assembly_group_node_id = $request->sectionn_id[$i];
    //             $product_purchase->legacy_article_id = $request->sectionn_part_id[$i];
    //             $product_purchase->status = $request->statuss[$i];
    //             $product_purchase->supplier_id = $request->supplier_id;
    //             $product_purchase->total_cost = $request->total_price[$i];

    //             $date = date("Y-m-d", strtotime($request->datee[$i]));
    //             $product_purchase->date = $date;
    //             $product_purchase->qty = $request->black_qty[$i] + $request->white_qty[$i];
    //             $product_purchase->save();
    //         }

    //         DB::commit();
    //         return "true";

    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return $e->getMessage();
    //     }
    // }    
}
