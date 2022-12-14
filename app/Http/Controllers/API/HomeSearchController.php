<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use Illuminate\Http\Request;

class HomeSearchController extends Controller
{
    public function getAllManufacturers(Request $request){
        $type = ["V","L","B","P"];
        $manufacturers = [];
        $count = Manufacturer::whereIn('linkingTargetType', $type)->count();
        $manufacturerss = Manufacturer::whereIn('linkingTargetType', $type)->get();
        foreach ($manufacturerss as $key => $manufacturer) {
            array_push($manufacturers,$manufacturer);
        }
        $page = $request->page;
        $manufacturers_per_page = 10;
        // $products = SupplierProduct::IsActive()->where('supplier_id', $id)->skip(4)->take(4)->get();
        $page_count = (int)ceil($count / $manufacturers_per_page);
        $manufacturer_visit = $page * $manufacturers_per_page;
        $manufacturers = array_slice($manufacturers, $manufacturer_visit - (int)10, $manufacturers_per_page);
        
        $response = [
            
            'success' => true,
            'message' => "good",
            'manufacturers' => $manufacturers,
            "pagination" =>  [
                "total_pages" => $page_count,
                "current_page" => $page,
                "previous_page" => $page - (int)1,
                "next_page" => $page + (int)1,
                "has_next" => ($count > $manufacturer_visit) ? true : false,
                "has_previous" => false
            ],
        ];
        return response()->json($response);
    }

    public function getManufacturers(Request $request){
        $manufacturers = [];
        $count = Manufacturer::where('linkingTargetType', $request->sub_type)->count();
        $manufacturerss = Manufacturer::where('linkingTargetType', $request->sub_type)->get();
        foreach ($manufacturerss as $key => $manufacturer) {
            array_push($manufacturers,$manufacturer);
        }
        $page = $request->page;
        $manufacturers_per_page = 10;
        // $products = SupplierProduct::IsActive()->where('supplier_id', $id)->skip(4)->take(4)->get();
        $page_count = (int)ceil($count / $manufacturers_per_page);
        $manufacturer_visit = $page * $manufacturers_per_page;
        $manufacturers = array_slice($manufacturers, $manufacturer_visit - (int)10, $manufacturers_per_page);
        
        $response = [
            
            'success' => true,
            'message' => "good",
            'manufacturers' => $manufacturers,
            "pagination" =>  [
                "total_pages" => $page_count,
                "current_page" => $page,
                "previous_page" => $page - (int)1,
                "next_page" => $page + (int)1,
                "has_next" => ($count > $manufacturer_visit) ? true : false,
                "has_previous" => false
            ],
        ];
        return response()->json($response);
    }

    public function getModelsByManufacturer(Request $request){
        $type = ["V","L","B","P"];
        if($request->sub_type == "P"){
            $models = [];
            $count = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
            ->whereIn('linkingTargetType', $type)->count();
            $modelss = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->whereIn('linkingTargetType', $type)->get();
            foreach ($modelss as $key => $model) {
                array_push($models,$model);
            }
            $page = $request->page;
            $models_per_page = 10;
            $page_count = (int)ceil($count / $models_per_page);
            $model_visit = $page * $models_per_page;
            $models = array_slice($models, $model_visit - (int)10, $models_per_page);
            
            $response = [
                
                'success' => true,
                'message' => "good",
                'models' => $models,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $model_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        }else{
            $models = [];
            $count = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
            ->where('linkingTargetType', $request->sub_type)->count();
            $modelss = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                    ->where('linkingTargetType', $request->sub_type)->get();
            foreach ($modelss as $key => $model) {
                array_push($models,$model);
            }
            $page = $request->page;
            $models_per_page = 10;
            $page_count = (int)ceil($count / $models_per_page);
            $model_visit = $page * $models_per_page;
            $models = array_slice($models, $model_visit - (int)10, $models_per_page);
            
            $response = [
                
                'success' => true,
                'message' => "good",
                'models' => $models,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $model_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        }
    }


    public function getEnginesByModel(Request $request){
        $type = ["V","L","B","P"];
        if($request->sub_type == "P"){
            $engines = [];
            $count = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
            ->where('vehicleModelSeriesId', $request->model_id)
            ->whereIn('subLinkageTargetType',$type)->count();
            $enginess = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
            ->where('vehicleModelSeriesId', $request->model_id)
            ->whereIn('subLinkageTargetType',$type)->get();
            foreach ($enginess as $key => $engine) {
                array_push($engines,$engine);
            }
            $page = $request->page;
            $engines_per_page = 10;
            $page_count = (int)ceil($count / $engines_per_page);
            $engine_visit = $page * $engines_per_page;
            $engines = array_slice($engines, $engine_visit - (int)10, $engines_per_page);
            
            $response = [
                
                'success' => true,
                'message' => "good",
                'engines' => $engines,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $engine_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        }else{
            $engines = [];
            $count = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
            ->where('vehicleModelSeriesId', $request->model_id)
            ->where('subLinkageTargetType',$request->sub_type)->count();
            $enginess = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
            ->where('vehicleModelSeriesId', $request->model_id)
            ->where('subLinkageTargetType',$request->sub_type)->get();
            foreach ($enginess as $key => $engine) {
                array_push($engines,$engine);
            }
            $page = $request->page;
            $engines_per_page = 10;
            $page_count = (int)ceil($count / $engines_per_page);
            $engine_visit = $page * $engines_per_page;
            $engines = array_slice($engines, $engine_visit - (int)10, $engines_per_page);
            
            $response = [
                
                'success' => true,
                'message' => "good",
                'engines' => $engines,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $engine_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        }
    }

    public function getSearchSectionByEngine(Request $request){
        $type = ["V","L","B","P"];
        $engine = LinkageTarget::where('linkageTargetId', $request->engine_id)->where('sublinkageTargetType', $request->sub_type)->where('lang', 'en')->first();
            $sections = [];
            $count = AssemblyGroupNode::groupBy('assemblyGroupNodeId')
            ->whereHas('articleVehicleTree', function($query) use ($engine){
                $query->where('linkingTargetId', $engine->linkageTargetId)
                ->where('linkingTargetType', $engine->linkageTargetType);
                })
                ->with('allSubSection', function($data){
                    $data->limit(3);
                })
            ->groupBy('assemblyGroupNodeId')
            ->count();
            $sectionss = AssemblyGroupNode::groupBy('assemblyGroupNodeId')
            ->whereHas('articleVehicleTree', function($query) use ($engine){
                $query->where('linkingTargetId', $engine->linkageTargetId)
                ->where('linkingTargetType', $engine->linkageTargetType);
                })
                ->with('allSubSection', function($data){
                    $data->limit(3);
                })
            ->groupBy('assemblyGroupNodeId')->get();
            foreach ($sectionss as $key => $section) {
                array_push($sections,$section);
            }
            $page = $request->page;
            $sections_per_page = 10;
            $page_count = (int)ceil($count / $sections_per_page);
            $section_visit = $page * $sections_per_page;
            $sections = array_slice($sections, $section_visit - (int)10, $sections_per_page);
            
            $response = [
                
                'success' => true,
                'message' => "good",
                'sections' => $sections,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $section_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        
    } 

    public function articleSearchView(Request $request){
        $section_parts = [];
            $engine = LinkageTarget::where('linkageTargetId', $request->engine_id)->where('lang', 'en')->whereIn('linkageTargetType',$request->sub_type)
            ->first();
            $section_partss = Article::whereHas('articleVehicleTree', function ($query) use ($request,$engine) {
                    $query->where('linkingTargetType', $engine->linkageTargetType)->where('assemblyGroupNodeId', $request->section_id);
                })->get();
            $count = Article::whereHas('articleVehicleTree', function ($query) use ($request,$engine) {
                    $query->where('linkingTargetType', $engine->linkageTargetType)->where('assemblyGroupNodeId', $request->section_id);
                })->count();
            foreach ($section_partss as $key => $part) {
                array_push($section_parts,$part);
            }
            $page = $request->page;
            $section_parts_per_page = 10;
            $page_count = (int)ceil($count / $section_parts_per_page);
            $section_part_visit = $page * $section_parts_per_page;
            $section_parts = array_slice($section_parts, $section_part_visit - (int)10, $section_parts_per_page);

          
            $response = [
                
                'success' => true,
                'message' => "good",
                'section_parts' => $section_parts,
                "pagination" =>  [
                    "total_pages" => $page_count,
                    "current_page" => $page,
                    "previous_page" => $page - (int)1,
                    "next_page" => $page + (int)1,
                    "has_next" => ($count > $section_part_visit) ? true : false,
                    "has_previous" => false
                ],
            ];
            return response()->json($response);
        
            
    }

    public function articleView(Request $request){
        $article = Article::where('legacyArticleId',$request->article_id)->first();
        $section = $article->section;
        $sub_section = AssemblyGroupNode::where('assemblyGroupNodeId',$request->sub_section_id)->first();
        $brand = $article->brand;
        // $engine = LinkageTarget::where('linkageTargetId',$)->first();
        $response = [
            
            'success' => true,
            'message' => "good",
            'article' => $article,
            'section' => $section,
            'sub_section' => $sub_section,
            'brand' => $brand,
            
        ];
        return response()->json($response);
    }
}
