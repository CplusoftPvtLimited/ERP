<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AfterMarkitSupplier;
use App\Models\Ambrand;
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
        $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)->limit(10)->get();
        $manfuacture_array = [
            'type' => "P",
            'sub_type' => "home",
            'value' => 10,
        ];
        session()->put('manufacturer_load_more',$manfuacture_array);
        $model_array = [
            'type' => "null",
            'sub_type' => "null",
            'value' => 0,
        ];
        session()->put('model_load_more',$model_array);
        $engine_array = [
            'engine_type' => "null",
            'engine_sub_type' => "null",
            'value' => 0,
        ];
        session()->put('engine_load_more',$engine_array);
        $brands = Ambrand::where('lang','EN')->limit(10)->get();
        $brands_count = Ambrand::count();
        session()->put("record",10);
        session()->put('section_count',[]);
        session()->put('sub_section_count',[]);
        session()->put('section_brand_id',[]);

        return view('home_search.home_search',compact('manufacturers','brands','brands_count'));
    }   

    public function getManufacturers(Request $request){
        // dd($request->all());
        $type = ["V","L","B"];
        $type2 = ["C","T","M","A","K"];
        $manufacturers = "";
        $total_count = 0;
        $manufacturer_load_more = session()->get('manufacturer_load_more');
        if(isset($request->main)){
            $manfuacture_array = [
                'type' => "",
                'sub_type' => "",
                'value' => 0,
            ];
            session()->put('manufacturer_load_more',$manfuacture_array);
        }
        // dd($manufacturer_load_more);
        if($request->type == "P" && $request->sub_type == "home"){
            if($request->type == $manufacturer_load_more['type'] && $request->sub_type == $manufacturer_load_more['sub_type']){
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "P",
                    'sub_type' => "home",
                    'value' => $manufacturer_load_more['value'] + (int)10,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }else{
                $manfuacture_array = [
                    'type' => "P",
                    'sub_type' => "home",
                    'value' => 0,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "P",
                    'sub_type' => "home",
                    'value' => 10,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }
            $total_count = Manufacturer::whereIn('linkingTargetType', $type)->count();
        }else if($request->type == "O" && $request->sub_type == "home"){
            if($request->type == $manufacturer_load_more['type'] && $request->sub_type == $manufacturer_load_more['sub_type']){
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type2)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "O",
                    'sub_type' => "home",
                    'value' => $manufacturer_load_more['value'] + (int)10,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }else{
                $manfuacture_array = [
                    'type' => "O",
                    'sub_type' => "home",
                    'value' => 0,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "O",
                    'sub_type' => "home",
                    'value' => 10,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }
            $total_count = Manufacturer::whereIn('linkingTargetType', $type2)->count();
        }else {
            if($request->sub_type == $manufacturer_load_more['sub_type']){
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type2)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "",
                    'sub_type' => $request->sub_type,
                    'value' => $manufacturer_load_more['value'] + (int)10,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }else{
                $manfuacture_array = [
                    'type' => "",
                    'sub_type' => $request->sub_type,
                    'value' => 0,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
                $manufacturers = Manufacturer::whereIn('linkingTargetType', $type)
                ->skip($manufacturer_load_more['value'])->take((int)10)->get();
                $manfuacture_array = [
                    'type' => "",
                    'sub_type' => $request->sub_type,
                    'value' => 0,
                ];
                session()->put('manufacturer_load_more',$manfuacture_array);
            }
            $total_count = Manufacturer::where('linkingTargetType', $request->sub_type)->count();
        }
        
        return response()->json([
            'data' => $manufacturers,
            'total_count' => $total_count,
            'manu_more_data' => session()->get('manufacturer_load_more'),
        ]);
    }

    public function getModelsByManufacturer(Request $request)
    {
        
        try {
            $type = ["V","L","B"];
            $type2 = ["C","T","M","A","K"];
            $model_load_more = session()->get('model_load_more');
            // dd($model_load_more);
            if($request->main){
                $model_array = [
                    'type' => "null",
                    'sub_type' => "null",
                    'value' => 0,
                ];
                session()->put('model_load_more',$model_array);
            }
            $model_load_more = session()->get('model_load_more');
            $total_count = 0;
            
            if($request->engine_type == "P" && $request->engine_sub_type == "home"){
                
                if($request->engine_type == $model_load_more['type'] && $request->engine_sub_type == $model_load_more['sub_type']){
                    
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->whereIn('linkingTargetType', $type)
                    ->skip($model_load_more['value'])->take((int)10)->get();
                    $model_array = [
                        'type' => "P",
                        'sub_type' => "home",
                        'value' => $model_load_more['value'] + (int)10,
                    ];
                    session()->put('model_load_more',$model_array);
                }else{
                    
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->whereIn('linkingTargetType', $type)
                    ->skip(0)->take((int)10)->get();
                    $model_array = [
                        'type' => "P",
                        'sub_type' => "home",
                        'value' => 10,
                    ];
                    session()->put('model_load_more',$model_array);
                }

                $total_count = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                ->whereIn('linkingTargetType', $type)
                ->count();
            }else if($request->engine_type == "O" && $request->engine_sub_type == "home"){
                
                if($request->engine_type == $model_load_more['type'] && $request->engine_sub_type == $model_load_more['sub_type']){
                    
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->whereIn('linkingTargetType', $type2)
                    ->skip($model_load_more['value'])->take((int)10)->get();
                    $model_array = [
                        'type' => "O",
                        'sub_type' => "home",
                        'value' => $model_load_more['value'] + (int)10,
                    ];
                    session()->put('model_load_more',$model_array);
                }else{
                   
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->whereIn('linkingTargetType', $type2)
                    ->skip(0)->take((int)10)->get();
                    $model_array = [
                        'type' => "O",
                        'sub_type' => "home",
                        'value' => 10,
                    ];
                    session()->put('model_load_more',$model_array);
                }

                $total_count = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                ->whereIn('linkingTargetType', $type2)
                ->count();
            }else{
                if($request->engine_sub_type == $model_load_more['sub_type']){
                    
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->where('linkingTargetType', $request->engine_sub_type)
                    ->skip($model_load_more['value'])->take((int)10)->get();
                    $model_array = [
                        'type' => "",
                        'sub_type' => $request->engine_sub_type,
                        'value' => $model_load_more['value'] + (int)10,
                    ];
                    session()->put('model_load_more',$model_array);
                }else{
                    
                    $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->where('linkingTargetType', $request->engine_sub_type)
                    ->skip(0)->take((int)10)->get();
                    $model_array = [
                        'type' => "",
                        'sub_type' => $request->engine_sub_type,
                        'value' => 10,
                    ];
                    session()->put('model_load_more',$model_array);
                }

                $total_count =  ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                ->where('linkingTargetType', $request->engine_sub_type)
                ->count();
            }
            
            return response()->json([
                'data' => $models,
                'total_count' => $total_count,
                'load_more_model' => session()->get('model_load_more')
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEnginesByModel(Request $request)
    {
        try {
            $type = ["V","L","B"];
            $type2 = ["C","T","M","A","K"];
            $engine_load_more = session()->get('engine_load_more');
            // dd($model_load_more);
            if($request->main){
                $engine_array = [
                    'engine_type' => "null",
                    'engine_sub_type' => "null",
                    'value' => 0,
                ];
                session()->put('model_load_more',$engine_array);
            }
            $engine_load_more = session()->get('engine_load_more');
            $total_count = 0;
            if($request->engine_type == "P" && $request->engine_sub_type == "home"){
                
                if($request->engine_type == $engine_load_more['engine_type'] && $request->engine_sub_type == $engine_load_more['engine_sub_type']){
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->whereIn('linkageTargetType', $type)->skip($engine_load_more['value'])->take((int)10)->get();
                    $engine_array = [
                        'engine_type' => "P",
                        'engine_sub_type' => "home",
                        'value' => $engine_load_more['value'] + (int)10,
                    ];
                    session()->put('engine_load_more',$engine_array);
                }else{
                    
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->whereIn('linkageTargetType', $type)->skip(0)->take((int)10)->get();
                    $model_array = [
                        'engine_type' => "P",
                        'engine_sub_type' => "home",
                        'value' => 10,
                    ];
                    session()->put('engine_load_more',$model_array);
                }
                $total_count = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                ->where('vehicleModelSeriesId', $request->model_id)
                ->whereIn('linkageTargetType', $type)->count();
            }else if($request->engine_type == "O" && $request->engine_sub_type == "home"){
                
                if($request->engine_type == $engine_load_more['engine_type'] && $request->engine_sub_type == $engine_load_more['engine_sub_type']){
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->whereIn('linkageTargetType', $type)->skip($engine_load_more['value'])->take((int)10)->get();
                    $engine_array = [
                        'engine_type' => "O",
                        'engine_sub_type' => "home",
                        'value' => $engine_load_more['value'] + (int)10,
                    ];
                    session()->put('engine_load_more',$engine_array);
                }else{
                    
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->whereIn('linkageTargetType', $type)->skip(0)->take((int)10)->get();
                    $model_array = [
                        'engine_type' => "O",
                        'engine_sub_type' => "home",
                        'value' => 10,
                    ];
                    session()->put('engine_load_more',$model_array);
                }
                $total_count = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                ->where('vehicleModelSeriesId', $request->model_id)
                ->whereIn('linkageTargetType', $type2)->count();
            }else{
                if($request->engine_sub_type == $engine_load_more['engine_sub_type']){
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->where('linkageTargetType', $request->engine_sub_type)->skip($engine_load_more['value'])->take((int)10)->get();
                    $engine_array = [
                        'engine_type' => "",
                        'engine_sub_type' => $request->engine_sub_type,
                        'value' => $engine_load_more['value'] + (int)10,
                    ];
                    session()->put('engine_load_more',$engine_array);
                }else{
                    
                    $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                    ->where('vehicleModelSeriesId', $request->model_id)
                    // ->whereIn('subLinkageTargetType',$type)
                    ->where('linkageTargetType', $request->engine_sub_type)->skip(0)->take((int)10)->get();
                    $model_array = [
                        'engine_type' => "",
                        'engine_sub_type' => $request->engine_sub_type,
                        'value' => 10,
                    ];
                    session()->put('engine_load_more',$model_array);
                }
                $total_count = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                ->where('vehicleModelSeriesId', $request->model_id)
                ->where('linkageTargetType', $request->engine_sub_type)->count();
            }
            
            
            return response()->json([
                'data' => $engines,
                'total_count' => $total_count,
                'load_more_engine' => session()->get('engine_load_more'),
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
        $model_array = [
            'type' => "null",
            'sub_type' => "null",
            'value' => 0,
        ];
        session()->put('model_load_more',$model_array);
        $engine_array = [
            'engine_type' => "null",
            'engine_sub_type' => "null",
            'value' => 0,
        ];
        session()->put('engine_load_more',$engine_array);
        $engine = LinkageTarget::where('linkageTargetId',$request->engine_id)
                ->first();
                $type = ["V","L","B"];
                $type2 = ["C","T","M","A","K"];
        if($request->type == "P" && $request->sub_type == "home"){
            $sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')
            ->whereHas('articleVehicleTree', function($query) use ($request,$engine,$type){
                        $query->where('linkingTargetId', $request->engine_id)
                        ->whereIn('linkingTargetType', $type);
                        })
                        ->with('allSubSection', function($data){
                            $data->limit(10);
                        })
                   ->groupBy('assemblyGroupNodeId')
                   ->limit(30)
                    ->get();
        }else if($request->type == "O" && $request->sub_type == "home"){
            // dd($request->all());
            $sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')
            ->whereHas('articleVehicleTree', function($query) use ($request,$engine,$type2){
                        $query->where('linkingTargetId', $request->engine_id)
                        ->whereIn('linkingTargetType', $type2);
                        })
                        ->with('allSubSection', function($data){
                            $data->limit(10);
                        })
                   ->groupBy('assemblyGroupNodeId')
                   ->limit(30)
                    ->get();
        }else{
            $sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')
            ->whereHas('articleVehicleTree', function($query) use ($request,$engine,$type){
                        $query->where('linkingTargetId', $request->engine_id)
                        ->where('linkingTargetType', $request->engine_sub_type);
                        

                        })
                    ->with('allSubSection', function($data){
                        $data->limit(10);
                    })
                   ->groupBy('assemblyGroupNodeId')
                   ->limit(30)
                    ->get();
        }
        
        // dd($sections);
        // $sections = [];
        // if(count($all_sections) > 0){
        //     foreach ($all_sections as $sec) {
        //         $sub_sections = AssemblyGroupNode::where('parentNodeId',$sec->assemblyGroupNodeId)->get();
        //         $res = [
        //             'section' => $sec,
        //             'sub_sections' => $sub_sections
        //         ];
        //         array_push($sections,$res);
        //     }
        // }
        $type = $request->type;
        $sub_type = $request->sub_type;
        $model_year = $request->model_year;
        $fuel = $request->fuel;
        $cc = $request->cc;

        return view('home_search.sections_search_view',compact('sections','engine','type','sub_type','model_year','fuel','cc'));
    }

    public function articleSearchView($id,$section_id){
        $engine = LinkageTarget::where('linkageTargetId',$id)->first();
        
        
        // whereHas('section', function($query) {
        //         $query->whereNotNull('request__linkingTargetId');
        //     })
        $section_parts = Article::whereHas('articleVehicleTree', function ($query) use ($section_id,$engine) {
                $query->where('linkingTargetType', $engine->linkageTargetType)->where('assemblyGroupNodeId', $section_id);
            })
            ->limit(100)
            ->get();
        // dd($section_parts);
            // if(count($section_parts) <= 0 || empty($engine)){
            //     toastr()->error("Data not found against your request");
            //         return redirect()->back();
            // }
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

    public function articleSearchViewByBrandSection(Request $request){
        // dd($request->all());
        if(empty($request->sub_section_id)){
            toastr()->error('Please Select a Section');
            return redirect()->back();
        }
        $section = AssemblyGroupNode::where('assemblyGroupNodeId',$request->sub_section_id)->first();
        if(!empty($section)){
            $engine = LinkageTarget::where('linkageTargetId',$section->request__linkingTargetId)->first();
            if(empty($engine)){
                toastr()->error('Data not available');
                return redirect()->back();
            }
            $dual = $request->dual_search;
            return redirect()->route('get_article_by_sub_section',[$engine->linkageTargetId,$request->sub_section_id,$dual]);
        }else{
            toastr()->info('Section not available');
                return redirect()->back();
        }
        
    }

    public function articleSearchViewBySection($id,$section_id,$dual){
        // dd($request->all());
        $engine = LinkageTarget::where('linkageTargetId',$id)->first();
       
        
        // whereHas('section', function($query) {
        //         // $query->whereNotNull('request__linkingTargetId');
        //     })
        $section_parts = Article::whereHas('articleVehicleTree', function ($query) use ($section_id) {
                $query->where('assemblyGroupNodeId', $section_id);
            })
            ->limit(50)
            ->get();
           
            $type = $engine->linkageTargetType;
            $sub_type = $engine->subLinkageTargetType;
            $model_year = $engine->model_year;
            $fuel = $engine->fuelType;
            $cc = $engine->capacityCC;
            
            
        return view('home_search.article_search_view',compact('section_parts','section_id','engine','type','sub_type','model_year','fuel','cc','dual'));
    }

    public function addToCart(Request $request){
        // dd($request->all());
        $cart = Cart::where('retailer_id',auth()->user()->id)->first();
        try {
            if(!empty($cart)){  // if retailer has data in cart
                if($cart->cash_type != $request->cash_type){
                    $cash_type = $cart->cash_type == "white" ? "primary" : "secondary";
                    // session()->put('old_quantity',$request->quantity);
                    toastr()->info('You have already data against cash type "'.$cash_type.'"'." So Yo cannot change cash type");
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
        // dd($request->all());
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
            $cart_item->discount = $request->discount;
            $cart_item->additional_cost_without_vat = $request->additional_cost_without_vat;
            $cart_item->additional_cost_with_vat = $request->additional_cost_with_vat;
            $cart_item->vat = $request->vat;
            $cart_item->profit_margin = ($request->profit_margin);
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
        // dd($request->all());
        DB::beginTransaction();

        try {
            $cart_items = CartItem::where('cart_id',$cart->id)->get();
            
            $cart->total_vat = $cart->total_vat + $request->vat;
            $cart->total_qty = $cart->total_qty + $request->quantity;
            $cart->additional_cost = $cart->additional_cost + $request->purchase_additional_cost;
            $all_total_excluding_vat = 0;
            $cart->save();
            $article_id_check_array = [];
            foreach ($cart_items as $cart_item) {
                if($cart_item->product_id == $request->article){
                    $total_excluding_vat = (($request->purchase_price + $cart_item->actual_price) * ($request->quantity + $cart_item->qty)) + ($request->additional_cost_without_vat + $cart_item->additional_cost_without_vat);
                    $actual_cost_per_product =  ($total_excluding_vat / ($request->quantity + $cart_item->qty)) + ($cart->additional_cost / $cart->total_qty);
                    $sale_price = $actual_cost_per_product * (1 + (($request->profit_margin / 100) + ($cart_item->profit_margin /100)));
                    
                    $cart_item->qty = $cart_item->qty + $request->quantity;
                    $cart_item->actual_price = $cart_item->actual_price + $request->purchase_price;
                    $cart_item->sell_price = $sale_price;
                    $cart_item->discount = $cart_item->discount +  $request->discount;
                    $cart_item->additional_cost_without_vat = (float)$cart_item->additional_cost_without_vat + (float)$request->additional_cost_without_vat;
                    $cart_item->additional_cost_with_vat = (float)$cart_item->additional_cost_with_vat + (float)$request->additional_cost_with_vat;
                    $cart_item->vat = (float)$cart_item->vat + (float)$request->vat;
                    $cart_item->profit_margin = $cart_item->profit_margin + $request->profit_margin;
                    $cart_item->total_excluding_vat = $total_excluding_vat;
                    $cart_item->actual_cost_per_product = $actual_cost_per_product;
                    $date = date("Y-m-d");
                    $cart_item->date = $date;
                    $cart_item->save();
                }else{
                    // cart Item save code
                    if(!in_array($request->article,$article_id_check_array)){
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
                        $cart_item->discount = $request->discount;
                        $cart_item->additional_cost_without_vat = $request->additional_cost_without_vat;
                        $cart_item->additional_cost_with_vat = $request->additional_cost_with_vat;
                        $cart_item->vat = $request->vat;
                        $cart_item->profit_margin = ($request->profit_margin);
                        $cart_item->total_excluding_vat = $total_excluding_vat;
                        $cart_item->actual_cost_per_product = $actual_cost_per_product;
                        $date = date("Y-m-d");
                        $cart_item->date = $date;
                        $cart_item->save();

                        array_push($article_id_check_array,$request->article);
                    }
                    
                    
                }
            }
           
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

    public function getSubSectionByBrand(Request $request) {
        // dd($request->all());
        $section_count  = session()->get('section_count');
        $sub_section_count  = session()->get('sub_section_count');
        $section_brand_id  = session()->get('section_brand_id');
        if(!empty($section_brand_id) && $section_brand_id != $request->brand_id){
            session()->put('section_count',[]);
            session()->put('sub_section_count',[]);
        }
        
        
        if(empty($section_count)){
            $section_count = 0;
        }
        if(empty($sub_section_count)){
            $sub_section_count = 0;
        }

        
        $brand = AssemblyGroupNode::where('lang',"EN")->whereHas('articleVehicleTree', function($query) use ($request) {
            $query->whereHas('article', function($query) use ($request) {
                $query->whereHas('brand', function($sub_query) use ($request)  {
                    $sub_query->where('brandId', $request->brand_id)->where('lang','EN');
                });
            });
        })
        // ->with('subSection', function($query) use ($sub_section_count){
        //     $query->limit((int)$sub_section_count + (int)10);
        // })
        ->skip($section_count)->limit((int)$section_count + (int)10)->get();
        // dd($brand);
        session()->put('section_brand_id',$request->brand_id);
        session()->put('section_count', (int)$section_count);
        session()->put('sub_section_count',(int)$sub_section_count);
        return response()->json($brand);
    }

    public function loadMoreBrands(){
        $value = session()->get('record');
        
        $brands = Ambrand::skip($value)->take(10)->get();
        session()->put('record',[]);
        session()->put('record',$value + (int)10);
        $value2 = session()->get('record');
        return response()->json([
            'brands' => $brands,
            'count' => $value2
        ]);
    }
}
