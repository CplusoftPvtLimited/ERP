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
    private $val = 0;
    private $manufacturer;
    private $linkage_target_type;

    public function __construct(ManufacturerInterface $manufacturer)
    {
        $this->manufacturer = $manufacturer;
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

    protected function getLinkageTargetTypeName($linkageType)
    {
        foreach ($this->linkage_target_type as $key => $type) {
                foreach ($type as $key_val => $sub_type) {
                    if ($linkageType== $key_val) {
                        return $sub_type . ' (' .$key_val . ')';
                    }
                }
            }
        }

    protected function checkLinkageTargetType($manufacturer_linkageType)
    {
        foreach ($this->linkage_target_type as $key => $type) {
            foreach ($type as $key_val => $sub_type) {
                if ($manufacturer_linkageType== $key_val) {
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
            return DataTables::of(Manufacturer::orderBy('id','desc'))
                    ->addIndexColumn()
                    ->addColumn('linkingTargetType', function($row) {
                        return $this->getLinkageTargetTypeName($row->linkingTargetType);
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="row">
                            <div class="col-md-2 mr-1">
                                <a href="manufacturer/' . $row["id"].'/edit"> <button
                                class="btn btn-primary btn-sm " type="button"
                                data-original-title="btn btn-danger btn-xs"
                                title=""><i class="fa fa-edit"></i></button></a>
                            </div>
                            <div class="col-md-2">
                            <button class="btn btn-danger btn-sm" onclick="deleteManufacturer(\''.$row["id"].'\')"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                     ';
                            return $btn;
                    })
                    ->addColumn('index', function ($row) {
                        $value = ++$this->val;
                        return $value;
                    })
                    ->rawColumns(['action', 'linkingTargetType','index'])
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
        $item = $this->manufacturer->store($request);
        if(is_object($item)){
            return redirect()->route('manufacturer.index')->withSuccess(__('Manufacturer Added Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
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
    public function edit($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            if(empty($manufacturer)) {
                return redirect(route('manufacturer.edit'))->withError('Manufacturer not found');
            }
            $data = $this->checkLinkageTargetType($manufacturer->linkingTargetType);

            
            $sub_target_type = isset($data) ? $data['sub_target_type'] : [];
            $target_type = isset($data) ? $data['target_type'] : [];
            $types = isset($data) ? $data['types'] : [];
            return view('manufacturer.edit',compact('manufacturer', 'sub_target_type', 'target_type', 'types'));
        } catch (\Exception $e) {
            return redirect(route('manufacturer.index'))->withError($e->getMessage());
        }
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
        $item = $this->manufacturer->update($request,$manufacturer);
        if(is_object($item)){
            return redirect()->route('manufacturer.index')->withSuccess(__('Manufacturer Updated Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        // $message = $this->manufacturer->delete($manufacturer);
        // return redirect()->back()->withMessage($message);
    }

    public function delete(Request $request)
    {
            $manufacturer = Manufacturer::findOrFail($request->id);
            $item = $this->manufacturer->delete($manufacturer);
            if($item == true)
            {
                return redirect()->back()->withSuccess(__('Manufacturer Deleted Successfully.'));
            }else{
                return redirect()->back()->withError($item);
            }
     }
     public function getManufacturersByEngineType(Request $request)
     {
        try {
            $manufacturers = Manufacturer::where('linkingTargetType', $request->engine_sub_type)->get();
            // dd($manufacturers);
            return response()->json([
                'data' => $manufacturers
            ], 200);
         } catch (\Exception $e) {
             return $e->getMessage();
         }
     }
}
        
