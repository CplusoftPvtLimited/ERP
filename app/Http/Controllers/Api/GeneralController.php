<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EngineResource;
use Illuminate\Http\Request;
use App\Models\VehicleDetail;
use App\Http\Resources\ManufactureResource;
use App\Http\Resources\SectionPartResource;
use App\Http\Resources\SectionResource;
use Illuminate\Http\JsonResponse;
use App\Models\Car;
use App\Repositories\Interfaces\GeneralInterface;
use PhpParser\Node\Stmt\TryCatch;

class GeneralController extends Controller
{

    private $general;

    public function __construct(GeneralInterface $generalinterface)
    {
        $this->general = $generalinterface;
    }


    public function getManufacturer()
    {
        try {
            $manufacturers = $this->general->getManufacturer();
            // dd($manufacturers);
            $message ="All manufactures";
            $transformed_manufactures = ManufactureResource::collection($manufacturers);
            // $transformed_manufactures = new ManufactureResource($manufacture);    use only when you retrive the single object from the collection
            // resources are only work on the modal retrival objects

            return $this->apiResponse(JsonResponse::HTTP_OK, 'data',$manufacturers,[],$message);
        } catch (\Exception $e) {
            return $this->apiResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'message', $e->getMessage());
        }
    }

    public function getModel(Request $request)
    {
        try {
         $all_models = $this->general->getModel($request->id);
         $message ="All Models of the Selected Manufacturer";
         return $this->apiResponse(JsonResponse::HTTP_OK, 'data',$all_models,[],$message);

        } catch (\Exception $e) {
            return $this->apiResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'message', $e->getMessage());
        }
        

        $response = [
            'success' => true,
            'data' => $all_models,
        ];

        return response()->json($response);


    }
    public function getEngineDetails(Request $request)
    {
        try {
            $enginedetails = $this->general->getEngineDetails($request->id);
            $message = "All Enigine Details of the Selected Model";
            $transformed_enigines = EngineResource::collection($enginedetails);
            return $this->apiResponse(JsonResponse::HTTP_OK, 'data',$transformed_enigines,[],$message);
            
        } catch (\Exception $e) {
            return $this->apiResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'message', $e->getMessage());
        }
    }
    public function getSections(Request $request)
    {
        try {
            $sections = $this->general->getSections($request->id);
            $message = "All Sections of the Selected Engine";
            $transformed_sections= SectionResource::collection($sections);
            return $this->apiResponse(JsonResponse::HTTP_OK, 'data',$transformed_sections,[],$message);
        } catch (\Exception $e) {
            return $this->apiResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'message', $e->getMessage());
        }
    }
    public function getSectionParts(Request $request)
    {
        try {
            $sectionparts = $this->general->getSectionParts($request->id);
            $message = "All Section Parts of the Selected Section";
            $transformed_sectionparts = SectionPartResource::collection($sectionparts);
            return $this->apiResponse(JsonResponse::HTTP_OK, 'data',$transformed_sectionparts,[],$message);
        } catch (\Exception $e) {
            return $this->apiResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'message', $e->getMessage());
        }
    }