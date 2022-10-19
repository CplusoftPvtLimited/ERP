<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
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

    public function articleSearchView(Request $request){
        // dd($request->all());
        $section_parts = Article::whereHas('section', function($query) {
                $query->whereNotNull('request__linkingTargetId');
            })->whereHas('articleVehicleTree', function ($query) use ($request) {
                $query->where('linkingTargetType', $request->sub_type)->where('assemblyGroupNodeId', $request->section);
            })
            ->limit(100)
            ->get();

            $type = $request->type;
            $sub_type = $request->sub_type;
            $model_year = $request->model_year;
            $fuel = $request->fuel;
            $cc = $request->cc;
            $engine = json_decode($request->engine);
            
        return view('home_search.article_search_view',compact('section_parts','engine','type','sub_type','model_year','fuel','cc'));
    }
}
