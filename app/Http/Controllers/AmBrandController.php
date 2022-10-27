<?php

namespace App\Http\Controllers;

use App\Models\AmBrand;
use App\Http\Requests\StoreAmBrandRequest;
use App\Http\Requests\UpdateAmBrandRequest;
use App\Language;
use App\Models\Country;
use App\Repositories\Interfaces\AmBrandInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AmBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $amBrand;
    private $val = 0;

    public function __construct(AmBrandInterface $amBrand)
    {
        $this->amBrand = $amBrand;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = AmBrand::orderBy('id','desc')->get();
                return DataTables::of($brands)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="suppliers/' . $row["id"].'/edit"> <button
                                    class="btn btn-primary btn-sm " type="button"
                                    data-original-title="btn btn-danger btn-xs"
                                    title=""><i class="fa fa-edit"></i></button></a>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-danger btn-sm" onclick="deleteSupplier(\''.$row["id"].'\')"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                         ';
                                return $btn;
                        })
                        ->addColumn('index', function ($row) {
                            $value = ++$this->val;
                            return $value;
                        })
                        ->rawColumns(['action','index'])
                        ->make(true);
            }
          
            return view('ambrand.index');
    }

    public function archiveSupplier(Request $request)
    {
        if ($request->ajax()) {
            $brands = AmBrand::onlyTrashed()->orderBy('id','desc')->get();
                return DataTables::of($brands)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                <button class="btn btn-info btn-sm" onclick="restoreSupplier(\'' . $row["id"] . '\')"><i class="fa fa-undo"></i></button>
                                </div>
                            </div>
                         ';
                                return $btn;
                        })
                        ->addColumn('index', function ($row) {
                            $value = ++$this->val;
                            return $value;
                        })
                        ->rawColumns(['action','index'])
                        ->make(true);
            }
          
            return view('ambrand.archive');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::select('languageCode','languageName')->get();
        $countries = Country::select('id','countryCode','countryName')->get();
        return view('ambrand.create',compact('languages','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAmBrandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAmBrandRequest $request)
    {
        $item = $this->amBrand->store($request);
        if(is_object($item)){
            return redirect()->route('suppliers.index')->withSuccess(__('Supplier Added Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AmBrand  $amBrand
     * @return \Illuminate\Http\Response
     */
    public function show(AmBrand $amBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AmBrand  $amBrand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $supplier = AmBrand::findOrFail($id);
            $languages = Language::select('languageCode','languageName')->get();
            $countries = Country::select('id','countryCode','countryName')->get();
            return view('ambrand.edit',compact('supplier','languages','countries'));
        } catch (\Exception $e) {
            return redirect(route('suppliers.index'))->withError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAmBrandRequest  $request
     * @param  \App\Models\AmBrand  $amBrand
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAmBrandRequest $request, $id)
    {
        $amBrand = AmBrand::find($id);
        if($amBrand){
            $item = $this->amBrand->update($request, $amBrand);
            if(is_object($item)){
                return redirect()->route('suppliers.index')->withSuccess(__('Supplier Updated Successfully.'));
            }else{
                return redirect()->back()->withError($item);
            }
        }else{
            return redirect()->back()->withError('Something Went Wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AmBrand  $amBrand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $supplier = AmBrand::findOrFail($request->id);
        $item = $this->amBrand->delete($supplier);
        if($item == true){
            return redirect()->route('suppliers.index')->withSuccess(__('Supplier Deleted Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }
    public function delete(Request $request)
    {
        $supplier = AmBrand::findOrFail($request->id);
        $item = $this->amBrand->delete($supplier);
        if($item == true){
            return redirect()->route('suppliers.index')->withSuccess(__('Supplier Deleted Successfully.'));
        }else{
            return redirect()->back()->withError($item);
        }
    }
    public function getBrandsBySectionPart(Request $request)
    {
        try {
            $id = explode('-', $request->section_part_id);
            $suppliers = Ambrand::select('brandId', 'brandName')
                ->where('brandId', $id[0])->get();
            return response()->json([
                'data' => $suppliers
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function restore(Request $request)
    {
        try {
            $section = AmBrand::onlyTrashed()->findOrFail($request->id)->restore();
            toastr()->success('Supplier Restored Successfully');
            return redirect()->route('suppliers.archive');
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->route('suppliers.archive');
        }
    }
}
