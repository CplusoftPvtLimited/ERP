<?php

namespace App\Http\Controllers;

use App\Models\ModelSeries;
use App\Http\Requests\StoreModelSeriesRequest;
use App\Http\Requests\UpdateModelSeriesRequest;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ModelSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = ModelSeries::orderBy('id','desc')->join('manufacturers','modelseries.manuId', '=', 'manufacturers.manuId')->select('modelseries.*', 'manufacturers.manuName')->get();
                return DataTables::of($models)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="editModel/' . $row["id"].'"> <button
                                    class="btn btn-primary btn-sm " type="button"
                                    data-original-title="btn btn-danger btn-xs"
                                    title=""><i class="fa fa-edit"></i></button></a>
                                </div>
                                <div class="col-md-2">
                                    <a href="deleteModel/' . $row['id'].'"> <button
                                    class="btn btn-danger btn-sm " style="" type="button"
                                    data-original-title="btn btn-danger btn-sm"
                                    title=""><i class="fa fa-trash"></i></button></a>
                                </div>
                            </div>
                         ';
                                return $btn;
                        })
                        ->rawColumns(['action'])
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
        return view('model_series.create',compact('manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreModelSeriesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModelSeriesRequest $request)
    {
        //
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
    public function edit(ModelSeries $modelSeries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateModelSeriesRequest  $request
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateModelSeriesRequest $request, ModelSeries $modelSeries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelSeries  $modelSeries
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelSeries $modelSeries)
    {
        //
    }
}
