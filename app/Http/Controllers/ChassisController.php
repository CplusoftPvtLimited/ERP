<?php

namespace App\Http\Controllers;

use App\Models\Chassis;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChassisRequest;
use App\Http\Requests\UpdateChassisRequest;
use App\Repositories\Interfaces\ChassisInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ChassisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $chassis;
    public $val = 0;

    public function __construct(ChassisInterface $chassis)
    {
        $this->chassis = $chassis;
        // $this->auth_user = auth()->guard('api')->user();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $chassis = Chassis::orderBy('id', 'desc')->get();
            return DataTables::of($chassis)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                            <div class="col-md-2 mr-1">
                                <a href="chassis/' . $row["id"] . '"> <button
                                class="btn btn-primary btn-sm " type="button"
                                data-original-title="btn btn-danger btn-xs"
                                title=""><i class="fa fa-eye"></i></button></a>
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
        return view('chassis.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChassisRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChassisRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chassis  $chassis
     * @return \Illuminate\Http\Response
     */
    public function show(Chassis $chassis)
    {
        // dd($chassis);
        return view('chassis.view', compact('chassis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chassis  $chassis
     * @return \Illuminate\Http\Response
     */
    public function edit(Chassis $chassis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChassisRequest  $request
     * @param  \App\Models\Chassis  $chassis
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChassisRequest $request, Chassis $chassis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chassis  $chassis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chassis $chassis)
    {
        //
    }

    public function import(Request $request)
    {
            $result = $this->chassis->import($request);
            return redirect()->route('chassis.index');
    }
}
