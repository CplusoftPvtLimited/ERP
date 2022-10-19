<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use Illuminate\Http\Request;

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
        $engine = LinkageTarget::where('linkageTargetId',$request->engine_id)
                ->where('linkageTargetType', $request->type)
                ->orWhere('subLinkageTargetType',$request->sub_type)->first();
        dd($engine);
    }
}
