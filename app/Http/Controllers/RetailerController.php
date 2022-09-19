<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use App\Http\Requests\StoreRetailerRequest;
use App\Http\Requests\UpdateRetailerRequest;
use App\Repositories\Interfaces\RetailerInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RetailerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $retailer;

    public function __construct(RetailerInterface $retailer)
    {
        $this->retailer = $retailer;
    }
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $retailers = Retailer::where('role_id',15)->where('is_deleted', false)->get();
            return DataTables::of($retailers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div class="row">
                            <div class="col-md-2 mr-1">
                                <a href="retailers/' . $row["id"].'/edit"> <button
                                class="btn btn-primary btn-sm " type="button"
                                data-original-title="btn btn-danger btn-xs"
                                title=""><i class="fa fa-edit"></i></button></a>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-danger btn-sm" onclick="deleteRetailer(\''.$row["id"].'\')"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                     ';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        }
        return view('retailers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('retailers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRetailerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRetailerRequest $request)
    {
        $item = $this->retailer->store($request);
        if(is_object($item)){
            return redirect()->route('retailers.index')->withSuccess(__('Retailer Added Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\Response
     */
    public function show(Retailer $retailer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\Response
     */
    public function edit(Retailer $retailer)
    {
        // dd($retailer);
        return view('retailers.edit',compact('retailer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRetailerRequest  $request
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRetailerRequest $request, Retailer $retailer)
    {
        $item = $this->retailer->update($request,$retailer);
        if(is_object($item)){
            return redirect()->route('retailers.index')->withSuccess(__('Retailer Updated Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retailer $retailer)
    {
        //
    }
    public function delete(Request $request)
    {
            $retailer = Retailer::findOrFail($request->id);
            $item = $this->retailer->delete($retailer);
            if($item == true)
            {
                return redirect()->back()->withSuccess(__('Data Deleted Successfully.'));
            }else{
                return redirect()->back()->withError($item);
            }
     }
}
