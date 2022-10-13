<?php

namespace App\Repositories;

use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use App\Repositories\Interfaces\LinkageTargetInterface;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LinkageTargetRepository implements LinkageTargetInterface
{
    public function store($request){
        DB::beginTransaction();
        try {
            // dd($request->all());
            $request->validate([
                'horsePowerTo' => 'required',
                'horsePowerFrom' => 'required',
                'kiloWattsFrom' => 'required',
                'kiloWattsTo' => 'required',
            ]);
            $data = $request->all();
            // dd($data);
            $max_engine_id = LinkageTarget::max('linkageTargetId');
            // dd($max_engine_id);
            $manufacture_name = Manufacturer::where('manuId',$request->mfrId)->first();
            $model = ModelSeries::where('modelId', $request->vehicleModelSeriesId)->first();
            // dd($manufacture_name);
            if (!empty($max_model_id)) {
                $data['linkageTargetId'] = $max_engine_id + 1;
            } else {
                $data['linkageTargetId'] = 1;
            }
            $data['mfrName'] = $manufacture_name->manuName;
            $data['vehicleModelSeriesName'] = $model->modelname;
            $data['linkageTargetType'] = $data['subLinkageTargetType'];
            // dd($data);
            $LinkageTarget = LinkageTarget::create($data);
            // dd($LinkageTarget);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function update($request,$id){
        
        try {
            DB::beginTransaction();
            $engine = LinkageTarget::find($id);
            $manufacture_name = Manufacturer::where('manuId',$request->mfrId)->first();
            $model = ModelSeries::where('modelId', $request->vehicleModelSeriesId)->first();

        //    dd($manufacture_name);

            $data = [
                'vehicleModelSeriesId'=> $request->vehicleModelSeriesId,
                'vehicleModelSeriesName' => $model->modelname,
                'mfrId' => $request->mfrId,
                'mfrName' => $manufacture_name->manuName,
                'capacityCC' => $request->capacityCC,
                'capacityLiters' => $request->capacityLiters,
                'kiloWattsFrom' => $request->kiloWattsFrom,
                'kiloWattsTo' => $request->kiloWattsTo,
                'horsePowerFrom' => $request->horsePowerFrom,
                'horsePowerTo' => $request->horsePowerTo,
                'description' => $request->description,
                'linkageTargetType' => $request->subLinkageTargetType,
                'subLinkageTargetType' => $request->subLinkageTargetType,
                'code' => $request->code,
            ];
            // dd($data);
            $engine->update($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }




    public function delete($request){
        
        try {
            DB::beginTransaction();
            $engine = LinkageTarget::find($request->id);
            
            $engine->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
