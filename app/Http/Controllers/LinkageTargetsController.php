<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Repositories\Interfaces\LinkageTargetInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LinkageTargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $engineRepository;
    private $linkage_target_type;

    public function __construct(LinkageTargetInterface $engineInterface)
    {
        $this->engineRepository = $engineInterface;
        $this->linkage_target_type = [
            'P' => [
                'V' => 'Passenger Car', 
                'L' => 'LCV', 
                'B' => 'Motorcycle'
            ], 
            'O' => [
                'C' => 'Commercial Vehicle', 
                'T' => 'Tractor', 
                'M' => 'Engine', 
                'A' => 'Axle',
                'K' => 'CV Body Type'
            ]
        ];
    }


    protected function checkLinkageTargetType($manufacturer_linkageType)
    {
        foreach ($this->linkage_target_type as $key => $type) {
            foreach ($type as $key_val => $sub_type) {
                if ($manufacturer_linkageType == $key_val) {
                    return [
                        'sub_target_type' => $key_val,
                        'target_type' => $key,
                        'types' => $type,
                    ];
                    break;
                }
            }
        }
        
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $engines = LinkageTarget::select('linkageTargetId','capacityCC','capacityLiters','code','kiloWattsFrom','kiloWattsTo','horsePowerTo','horsePowerFrom','engineType','id')->get();
            return DataTables::of($engines)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="engine/' . $row["id"] . '/edit"> <button
                                    class="btn btn-primary btn-sm " type="button"
                                    data-original-title="btn btn-danger btn-xs"
                                    title=""><i class="fa fa-edit"></i></button></a>
                                </div>
                                <div class="col-md-2">
                                    <a> <button
                                    class="btn btn-danger btn-sm" onclick="deleteEngine(' . $row['id'] . ')" style="" type="button"
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

        return view('linkage_targets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manufacturers = Manufacturer::all();
        return view('linkage_targets.create',compact('manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $engine = $this->engineRepository->store($request);
        if($engine == true){
            return redirect()->route('engine.index')->with('create_message', 'Engine created successfully');
        }else{
            // dd($engine->getMessage());
            return redirect()->route('engine.index')->with('error', $engine->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $engine = LinkageTarget::find($id);
        $manufacturers = Manufacturer::all();
        $data = $this->checkLinkageTargetType($engine->subLinkageTargetType);
        $sub_target_type = $data['sub_target_type'];
        $target_type = $data['target_type'];
        $types = $data['types'];
        return view('linkage_targets.edit', compact('engine','manufacturers', 'sub_target_type', 'target_type', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $engine = $this->engineRepository->update($request,$id);
        if($engine == true){
            return redirect()->route('engine.edit')->with('create_message', 'Engine Update successfully');
        }else{
            // dd($engine->getMessage());
            return redirect()->route('engine.edit')->with('error', $engine);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $engine = $this->engineRepository->delete($request);
        if($engine == true){
            return redirect()->route('engine.index')->with('create_message', 'Engine Deleted successfully');
        }else{
            // dd($engine->getMessage());
            return redirect()->route('engine.index')->with('error', $engine);
        }
    }
}
