<?php

namespace App\Http\Controllers;

use App\Models\ModelSeries;
use App\Http\Requests\StoreModelSeriesRequest;
use App\Http\Requests\UpdateModelSeriesRequest;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ModelSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $val = 0;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = ModelSeries::orderBy('id', 'desc')->join('manufacturers', 'modelseries.manuId', '=', 'manufacturers.manuId')->select('modelseries.*', 'manufacturers.manuName')->get();
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="/modelseries/' . $row["id"] . '/edit"> <button
                                    class="btn btn-primary btn-sm " type="button"
                                    data-original-title="btn btn-danger btn-xs"
                                    title=""><i class="fa fa-edit"></i></button></a>
                                </div>
                                <div class="col-md-2">
                                    <a> <button
                                    class="btn btn-danger btn-sm" onclick="deleteModel(' . $row['id'] . ')" style="" type="button"
                                    data-original-title="btn btn-danger btn-sm"
                                    title=""><i class="fa fa-trash"></i></button></a>
                                </div>
                            </div>
                         ';
                    return $btn;
                })
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['action', 'index'])
                ->make(true);
        }

        return view('model_series.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manufacturers = Manufacturer::all();
        $earliest_year = 1900;
        $latest_year = date('Y');
        return view('model_series.create', compact('manufacturers', 'latest_year', 'earliest_year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreModelSeriesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $request->validate([
                // 'tags' => 'required',
                'yearOfConstrFrom' => 'required|integer',
                'yearOfConstrTo' => 'required|integer',
                'manuId' => 'required',
                'linkingTargetType' => 'required',
            ]);
            $data = $request->except(['tags','_token']);
           
            if(!empty($request->tags) && str_contains($request->tags,",")){
                $model_names = explode(',',$request->tags);
                if (count($model_names) > 0) {
                    foreach($model_names as $name){
                        $data['modelname'] = $name;
                        $max_model_id = ModelSeries::max('modelId');
                        if (!empty($max_model_id)) {
                            $data['modelId'] = $max_model_id + 1;
                        } else {
                            $data['modelId'] = 1;
                        }
                        ModelSeries::create($data);
                    }

                    DB::commit();
                    return redirect()->route('modelseries.index')->with('create_message', 'Model created successfully');
                }
            }else if(!empty($request->tags) && !str_contains($request->tags,",")){
                $data['modelname'] = $request->tags;
                $max_model_id = ModelSeries::max('modelId');
                if (!empty($max_model_id)) {
                    $data['modelId'] = $max_model_id + 1;
                } else {
                    $data['modelId'] = 1;
                }
                ModelSeries::create($data);
                DB::commit();
                return redirect()->route('modelseries.index')->with('create_message', 'Model created successfully');
            }else{
                return redirect()->route('modelseries.create')->with('error', "Please select model name");
            }
            
            
            
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('modelseries.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function show(ModelSeries $modelSeries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modelSeries = ModelSeries::find($id);
        $manufacturers = Manufacturer::all();
        $earliest_year = 1900;
        $latest_year = date('Y');
        return view('model_series.edit', compact('modelSeries', 'manufacturers', 'latest_year', 'earliest_year'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateModelSeriesRequest  $request
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'modelname' => 'required',
                'yearOfConstrFrom' => 'required|integer',
                'yearOfConstrTo' => 'required|integer',
                'manuId' => 'required',
                'linkingTargetType' => 'required',
            ]);
            $data = $request->all();
            $modelSeries = ModelSeries::find($id);
            $modelSeries->update($data);
            DB::commit();

            return redirect()->route('modelseries.index')->with('create_message', 'Model Updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('modelseries.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {
            ModelSeries::find($request->id)->delete();
            return redirect()->route('modelseries.index')->with('create_message', 'Model Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('modelseries.index')->with('error', $e->getMessage());
        }
    }
    public function getModelsByManufacturer(Request $request)
    {
        $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)->get();
        return response()->json(
            [
                'data' => $models,
            ],200
        );
    }
}
