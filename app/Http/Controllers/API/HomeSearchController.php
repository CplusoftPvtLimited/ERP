<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}
