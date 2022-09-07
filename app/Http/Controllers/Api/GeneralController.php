<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleDetail;
use App\Http\Resources\ManufactureResource;
use Illuminate\Http\JsonResponse;
use App\Models\Car;
use App\Repositories\Interfaces\GeneralInterface;




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
         $message ="All Models of a Specific Manufacturer";
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
    public function getVehical(Request $request)
    {
        $cars = Car::where('modId',$request->id)->get();
        $all_data = [];
        $id_arr = [];
        foreach($cars as $car)
        {
            if(!in_array($car->carId,$id_arr)){
                $vehicles = VehicleDetail::where('modId',$request->id)->where('carId',$car->carId)->get();
                // dump($car->id);
                // dump($vehicles);
                
                $res = [
                    'car' => $car,
                    'vehicles' => $vehicles
                ];
                array_push($all_data,$res);
                array_push($id_arr,$car->carId);

            }
            
        }
        // dd('ff');
        $response = [
            'success' => true,
            'data' => $all_data
        ];

        return response()->json($response);
    }
}
