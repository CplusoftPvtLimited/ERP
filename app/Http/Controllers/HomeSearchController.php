<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AfterMarkitSupplier;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeSearchController extends Controller
{
    public function homeSearchView(){ 
        $type = ["V","L","B"];
        $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)->get();
       
        return view('home_search.home_search',compact('manufacturers'));
    }

    public function getManufacturers(Request $request){
        // dd($request->all());
        $type = ["V","L","B"];
        $type2 = ["C","T","M","A","K"];
        $manufacturers = "";
        if($request->type == "P" && $request->sub_type == "home"){
            $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)->get();
        }else if($request->type == "O" && $request->sub_type == "home"){
            $manufacturers = Manufacturer::whereIn('linkingTargetType', $type2)->get();
        }else {
            $manufacturers = Manufacturer::where('linkingTargetType', $request->sub_type)->get();
        }
        
        return response()->json([
            'data' => $manufacturers,
        ]);
    }

    public function getModelsByManufacturer(Request $request)
    {
        // dd($request->all());
        try {

            $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                ->where('linkingTargetType', $request->engine_sub_type)->get();
            // dd($models);
            return response()->json([
                'data' => $models
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEnginesByModel(Request $request)
    {
        try {
            $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                ->where('vehicleModelSeriesId', $request->model_id)
                ->where('linkageTargetType', $request->engine_type)
                ->orWhere('subLinkageTargetType',$request->engine_sub_type)->get();
            
            return response()->json([
                'data' => $engines
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEngineData(Request $request)
    {
        try {
            $engine = LinkageTarget::where('linkageTargetId',$request->engine_id)->first();
            // dd($engine);
            return response()->json([
                'data' => $engine
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchSectionByEngine(Request $request){
        // dd($request->all());
        $engine = LinkageTarget::where('linkageTargetId',$request->engine_id)
                ->where('linkageTargetType', $request->type)
                ->orWhere('subLinkageTargetType',$request->sub_type)->first();
        $all_sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')->whereHas('articleVehicleTree', function($query) use ($request){
                    $query->where('linkingTargetId', $request->engine_id)
                    ->where('linkingTargetType', $request->sub_type);
                })
               ->groupBy('assemblyGroupNodeId')
               ->limit(100)
                ->get();
        $sections = [];
        if(count($all_sections) > 0){
            foreach ($all_sections as $sec) {
                $sub_sections = AssemblyGroupNode::where('parentNodeId',$sec->assemblyGroupNodeId)->get();
                $res = [
                    'section' => $sec,
                    'sub_sections' => $sub_sections
                ];
                array_push($sections,$res);
            }
        }
        $type = $request->type;
        $sub_type = $request->sub_type;
        $model_year = $request->model_year;
        $fuel = $request->fuel;
        $cc = $request->cc;

        return view('home_search.sections_search_view',compact('sections','engine','type','sub_type','model_year','fuel','cc'));
    }

    public function articleSearchView($id,$section_id){
        $engine = LinkageTarget::where('linkageTargetId',$id)->first();
        $section_parts = Article::whereHas('section', function($query) {
                $query->whereNotNull('request__linkingTargetId');
            })->whereHas('articleVehicleTree', function ($query) use ($section_id,$engine) {
                $query->where('linkingTargetType', $engine->linkageTargetType)->where('assemblyGroupNodeId', $section_id);
            })
            ->limit(100)
            ->get();

            $type = $engine->linkageTargetType;
            $sub_type = $engine->subLinkageTargetType;
            $model_year = $engine->model_year;
            $fuel = $engine->fuelType;
            $cc = $engine->capacityCC;
            
            
        return view('home_search.article_search_view',compact('section_parts','section_id','engine','type','sub_type','model_year','fuel','cc'));
    }

    public function articleView($id,$engine_id,$sub_section_id){
        $article = Article::where('legacyArticleId',$id)->first();
        $section = $article->section;
        $sub_section = AssemblyGroupNode::where('assemblyGroupNodeId',$sub_section_id)->first();
        $brand = $article->brand;
        $engine = LinkageTarget::where('linkageTargetId',$engine_id)->first();
        
        return view('home_search.article_view',compact('article','section','engine','brand','sub_section'));
    }

    public function addToCart(Request $request){
        // dd($request->all());
        $cart = Cart::where('retailer_id',auth()->user()->id)->first();
        try {
            if(!empty($cart)){  // if retailer has data in cart
                if($cart->cash_type != $request->cash_type){
                    toastr()->info('You have already data against cash type "'.$cart->cash_type.'"'." So Yo cannot change cash type");
                    return redirect()->back();
                }
                $cart_data = $this->cartFilledData($cart,$request);
                if($cart_data != true){
                    toastr()->error($cart_data);
                    return redirect()->back();
                }else{
                    toastr()->success('Item Added to Cart Successfully');
                    return redirect()->route('cart');
                }
    
            }else{ // if retailer does not have data in cart
                $cart_data = $this->cartEmptyData($request);
                if($cart_data != true){
                    toastr()->error($cart_data);
                    return redirect()->back();
                }else{
                    toastr()->success('Item Added to Cart Successfully');
                    return redirect()->route('cart');
                }
                
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    // When Cart is empty 
    public function cartEmptyData($request){
        DB::beginTransaction();
        try {
            
            $total_excluding_vat = ($request->purchase_price * $request->quantity) + $request->additional_cost_without_vat;
            $actual_cost_per_product =  ($total_excluding_vat / $request->quantity) + ($request->purchase_additional_cost / $request->quantity);
            $sale_price = $actual_cost_per_product * (1 + ($request->profit_margin / 100));
            $total_vat = $request->vat + $request->additional_cost_with_vat + $request->purchase_additional_cost;
            $total_to_be_paid = 0;
            $total_to_be_paid_white = (float)$total_excluding_vat + (float) $total_vat + (float) $request->tax_stamp;
            $total_to_be_paid_black = (float)$total_excluding_vat;
            $cart = new Cart();
            $cart->retailer_id = auth()->user()->id;
            $cart->item = 1;
            $cart->total_qty = $cart->total_qty + $request->quantity;
            $cart->total_cost = $request->cash_type == "white" ? $total_to_be_paid_white : $total_to_be_paid_black;
            $cart->grand_total = $request->cash_type == "white" ? $total_to_be_paid_white : $total_to_be_paid_black;
            $cart->supplier_id = NULL;
            $cart->cash_type = $request->cash_type;
            $cart->additional_cost = $request->purchase_additional_cost;
            $cart->total_exculding_vat = $total_excluding_vat;
            $cart->tax_stamp = $request->tax_stamp;
            $cart->total_vat = $cart->total_vat + $request->total_vat;
            $cart->status = 0;
            $cart->date = date('Y-m-d');
            $cart->save();
            
            // cart Item save code
            $article = Article::where('legacyArticleId',$request->article)->first();
            $linkage = LinkageTarget::where('linkageTargetId',$request->engine)->first();
            $cart_item = new CartItem();
            $cart_item->cart_id = $cart->id;
            $cart_item->reference_no = $article->articleNumber;
            $cart_item->engine_details = $linkage->description;
            $cart_item->product_id = $request->article;
            $cart_item->qty = $request->quantity;
            $cart_item->actual_price = $request->purchase_price;
            $cart_item->sell_price = $sale_price;
            $cart_item->manufacture_id = $linkage->mfrId;
            $cart_item->model_id = $linkage->vehicleModelSeriesId;
            $cart_item->eng_linkage_target_id = $request->engine;
            $cart_item->assembly_group_node_id = $request->sub_section;
            $cart_item->legacy_article_id = $request->article;
            $cart_item->status = 'ordered';
            $cart_item->supplier_id = null;
            $cart_item->linkage_target_type = $linkage->linkageTargetType;
            $cart_item->linkage_target_sub_type = $linkage->subLinkageTargetType;
            $cart_item->cash_type = $request->cash_type;
            $cart_item->brand_id = $request->brand;
            $cart_item->discount = $request->discount / 100;
            $cart_item->additional_cost_without_vat = $request->additional_cost_without_vat;
            $cart_item->additional_cost_with_vat = $request->additional_cost_with_vat;
            $cart_item->vat = $request->vat;
            $cart_item->profit_margin = ($request->profit_margin / 100);
            $cart_item->total_excluding_vat = $total_excluding_vat;
            $cart_item->actual_cost_per_product = $actual_cost_per_product;
            $date = date("Y-m-d");
            $cart_item->date = $date;
            $cart_item->save();
            
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        
                
                    
    }

    // When cart is filled 

    public function cartFilledData($cart,$request){
        DB::beginTransaction();

        try {
            $cart_items = CartItem::where('cart_id',$cart->id)->get();
            
            $cart->total_vat = $cart->total_vat + $request->vat;
            $cart->total_qty = $cart->total_qty + $request->quantity;
            $cart->additional_cost = $cart->additional_cost + $request->purchase_additional_cost;
            $all_total_excluding_vat = 0;
            $cart->save();
            foreach ($cart_items as $cart_item) {
                if($cart_item->product_id == $request->article){
                    $total_excluding_vat = (($request->purchase_price + $cart_item->actual_price) * ($request->quantity + $cart_item->qty)) + ($request->additional_cost_without_vat + $cart_item->additional_cost_without_vat);
                    $actual_cost_per_product =  ($total_excluding_vat / ($request->quantity + $cart_item->qty)) + ($cart->additional_cost / $cart->total_qty);
                    $sale_price = $actual_cost_per_product * (1 + (($request->profit_margin / 100) + $cart_item->profit_margin));
                    
                    $cart_item->qty = $cart_item->qty + $request->quantity;
                    $cart_item->actual_price = $cart_item->actual_price + $request->purchase_price;
                    $cart_item->sell_price = $sale_price;
                    $cart_item->discount = $cart_item->discount +  ($request->discount / 100);
                    $cart_item->additional_cost_without_vat = $cart_item->additional_cost_without_vat + $request->additional_cost_without_vat;
                    $cart_item->additional_cost_with_vat = $cart_item->additional_cost_with_vat + $request->additional_cost_with_vat;
                    $cart_item->vat = $cart_item->vat + ($request->vat / 100);
                    $cart_item->profit_margin = $cart_item->profit_margin + ($request->profit_margin / 100);
                    $cart_item->total_excluding_vat = $total_excluding_vat;
                    $cart_item->actual_cost_per_product = $actual_cost_per_product;
                    $date = date("Y-m-d");
                    $cart_item->date = $date;
                    $cart_item->save();
                }else{
                    // cart Item save code
                    $total_excluding_vat = ($request->purchase_price  * $request->quantity ) + $request->additional_cost_without_vat ;
                    $actual_cost_per_product =  ($total_excluding_vat / $request->quantity) + ($cart->additional_cost / $cart->total_qty);
                    $sale_price = $actual_cost_per_product * (1 + ($request->profit_margin / 100));
                    $article = Article::where('legacyArticleId',$request->article)->first();
                    $linkage = LinkageTarget::where('linkageTargetId',$request->engine)->first();
                    $cart_item = new CartItem();
                    $cart_item->cart_id = $cart->id;
                    $cart_item->reference_no = $article->articleNumber;
                    $cart_item->engine_details = $linkage->description;
                    $cart_item->product_id = $request->article;
                    $cart_item->qty = $request->quantity;
                    $cart_item->actual_price = $request->purchase_price;
                    $cart_item->sell_price = $sale_price;
                    $cart_item->manufacture_id = $linkage->mfrId;
                    $cart_item->model_id = $linkage->vehicleModelSeriesId;
                    $cart_item->eng_linkage_target_id = $request->engine;
                    $cart_item->assembly_group_node_id = $request->sub_section;
                    $cart_item->legacy_article_id = $request->article;
                    $cart_item->status = 'ordered';
                    $cart_item->supplier_id = null;
                    $cart_item->linkage_target_type = $linkage->linkageTargetType;
                    $cart_item->linkage_target_sub_type = $linkage->subLinkageTargetType;
                    $cart_item->cash_type = $request->cash_type;
                    $cart_item->brand_id = $request->brand;
                    $cart_item->discount = $request->discount / 100;
                    $cart_item->additional_cost_without_vat = $request->additional_cost_without_vat;
                    $cart_item->additional_cost_with_vat = $request->additional_cost_with_vat;
                    $cart_item->vat = $request->vat;
                    $cart_item->profit_margin = ($request->profit_margin / 100);
                    $cart_item->total_excluding_vat = $total_excluding_vat;
                    $cart_item->actual_cost_per_product = $actual_cost_per_product;
                    $date = date("Y-m-d");
                    $cart_item->date = $date;
                    $cart_item->save();

                    
                }
                
            }

            // if($request->cash_type == "white"){
            //     $cart->total_cost = (float)$all_total_excluding_vat + $cart->total_vat + $cart->tax_stamp + $request->tax_stamp;
            //     $cart->grand_total = (float)$all_total_excluding_vat + $cart->total_vat + $cart->tax_stamp + $request->tax_stamp;
            // }else{
            //     $cart->total_cost = (float)$all_total_excluding_vat;
            //     $cart->grand_total = (float)$all_total_excluding_vat;
            // }
            $cart_items = CartItem::where('cart_id',$cart->id)->get();
            foreach($cart_items as $cart_item){
                $all_total_excluding_vat += $cart_item->total_excluding_vat;
            }
            if($request->cash_type == "white"){
                $cart->total_vat = $request->entire_vat + $cart->total_vat;
                $cart->tax_stamp = $request->tax_stamp + $cart->tax_stamp;
                $cart->total_exculding_vat = (float)$all_total_excluding_vat;
                $cart->total_cost = (float)$all_total_excluding_vat + $cart->entire_vat + $cart->tax_stamp;
                $cart->grand_total = (float)$all_total_excluding_vat + $cart->entire_vat + $cart->tax_stamp;
                
            }else{
                $cart->total_cost = (float)$all_total_excluding_vat;
                $cart->grand_total = (float)$all_total_excluding_vat;
                $cart->total_exculding_vat = (float)$all_total_excluding_vat;
            }
            $cart->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function cart(){
        $cart = Cart::where('retailer_id',auth()->user()->id)->first();
        if(!empty($cart)){
            $cart_items = CartItem::where('cart_id',$cart->id)->get();
            $manufacturers = Manufacturer::all();
            $suppliers = AfterMarkitSupplier::select('id', 'name')->where('retailer_id', auth()->user()->id)->get();
            
            
            return view('home_search.cart',compact('cart','cart_items','suppliers'));
        }else{
            toastr()->info('Your cart is empty');
            return redirect()->back();
        }
    }


    public function removeCartItem($id){
        DB::beginTransaction();
        try {
            $cart_item = CartItem::find($id);
            $cart = Cart::find($cart_item->cart_id);
            $cart->total_qty = $cart->total_qty - $cart_item->qty;
            $cart->save();
            $cart_item->delete();
            $cart_items = CartItem::where('cart_id',$cart->id)->get();
            if(count($cart_items) <= 0){
                $cart->delete();
                DB::commit();
                toastr()->success('All Items deleted from cart successfully');
                return redirect('/');

            }else{
                $counter = 0;
                $all_total_excluding_vat = 0;
                foreach($cart_items as $cart_item){
                    $total_excluding_vat = ($cart_item->actual_price * $cart_item->qty) + $cart_item->additional_cost_without_vat;
                    $actual_cost_per_product =  ($total_excluding_vat / $cart_item->qty) + ($cart->purchase_additional_cost / $cart->total_qty);
                    $sale_price = $actual_cost_per_product * (1 + ($cart_item->profit_margin / 100));
                    $all_total_excluding_vat += $total_excluding_vat;

                    
                    $cart_item->total_excluding_vat = $total_excluding_vat;
                    $cart_item->actual_cost_per_product = $actual_cost_per_product;
                    
                    $cart_item->save();
                }

                if($cart->cash_type == "white"){
                    $cart->total_cost = (float)$all_total_excluding_vat + $cart->entire_vat + $cart->tax_stamp;
                    $cart->grand_total = (float)$all_total_excluding_vat + $cart->entire_vat + $cart->tax_stamp;
                    $cart->total_exculding_vat = (float)$all_total_excluding_vat;
                }else{
                    $cart->total_cost = (float)$all_total_excluding_vat;
                    $cart->grand_total = (float)$all_total_excluding_vat;
                    $cart->total_exculding_vat = (float)$all_total_excluding_vat;
                }
                $cart->save();

                DB::commit();
                toastr()->success('Item deleted from cart successfully');
                return redirect()->back();
            }
            


        } catch (Exception $e) {
            DB::commit();
            toastr()->error($e->getMessage());
            return redirect()->route('cart');
        }
    }
}
