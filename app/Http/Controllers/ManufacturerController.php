<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Repositories\Interfaces\ManufacturerInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $manufacturer;

    public function __construct(ManufacturerInterface $manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }
    public function index(Request $request)
    {
        // $manufacturers= $this->manufacturer->index();
        // return view ('manufacturer.index', compact('manufacturers'));
        if ($request->ajax()) {
        $manufacturers = Manufacturer::orderBy('id','desc')->get();
            return DataTables::of($manufacturers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div class="row">
                            <div class="col-md-2 mr-1">
                                <a href="editManufacturer/' . $row["id"].'"> <button
                                class="btn btn-primary btn-sm " type="button"
                                data-original-title="btn btn-danger btn-xs"
                                title=""><i class="fa fa-edit"></i></button></a>
                            </div>
                            <div class="col-md-2">
                                <a href="deleteManufacturer/' . $row['id'].'"> <button
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
      
        return view('manufacturer.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manufacturer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreManufacturerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreManufacturerRequest $request)
    {
        $message = $this->manufacturer->store($request);
        return redirect(route('manufacturer.index'))->withMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturer.edit',compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateManufacturerRequest  $request
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $message = $this->manufacturer->update($request,$manufacturer);
        return redirect()->back()->withMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $message = $this->manufacturer->delete($manufacturer);
        return redirect()->back()->withMessage($message);
    }

    public function editManufacturer($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            return view('manufacturer.edit',compact('manufacturer'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function delete($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            $message = $this->manufacturer->delete($manufacturer);
            return redirect()->back()->withMessage($message);
        } catch (\Exception $e) {
            return $e;
        }
        
    }
}
