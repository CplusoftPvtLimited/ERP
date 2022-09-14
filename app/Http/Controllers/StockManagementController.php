<?php

namespace App\Http\Controllers;

use App\Models\StockManagement;
use App\Repositories\Interfaces\StockManagementInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StockManagementController extends Controller
{
    // private $stockManagementRepository;

    // public function __construct(StockManagementInterface $stockManagementInterface)
    // {
    //     $this->stockManagementRepository = $stockManagementInterface;
    //     // $this->auth_user = auth()->guard('api')->user();
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug($request->all());
        $validator = Validator::make($request->all(), [
            'white_items' => 'nullable|min:1',
            'black_items' => 'nullable|min:1',
            'unit_actual_price' => 'nullable|min:1',
            'unit_sale_price' => 'nullable|min:1',
        ]);
        if ($validator->fails()) {
            toastr()->error('make sure you entered valid data!');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $stock = StockManagement::find($request->id);
            $white_items = !empty($request->white_items) ? $request->white_items : $stock->white_items;
            $black_items = !empty($request->black_items) ? $request->black_items : $stock->black_items;

            $stock->update([
                'white_items' => isset($request->white_items) ? $request->white_items : $stock->white_items,
                'black_items' => isset($request->black_items) ? $request->black_items : $stock->black_items,
                'unit_actual_price' => isset($request->unit_actual_price) ? $request->unit_actual_price : $stock->unit_actual_price,
                'unit_sale_price' => isset($request->unit_sale_price) ? $request->unit_sale_price : $stock->unit_sale_price,
                'total_qty' => $white_items + $black_items
            ]);
            DB::commit();
            toastr()->success('record updated successfully!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
