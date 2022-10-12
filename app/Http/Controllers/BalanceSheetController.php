<?php

namespace App\Http\Controllers;

use App\Models\BalanceSheet;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBalanceSheetRequest;
use App\Http\Requests\UpdateBalanceSheetRequest;
use App\Models\BalanceCategory;
use App\Repositories\Interfaces\BalanceSheetInterface;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $balanceSheet;

    public function __construct(BalanceSheetInterface $balanceSheet)
    {
        $this->balanceSheet = $balanceSheet;
        // $this->auth_user = auth()->guard('api')->user();
    }
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
     * @param  \App\Http\Requests\StoreBalanceSheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBalanceSheetRequest $request)
    {
        // dd($request->all());
        $item = $this->balanceSheet->store($request);
        // dd($item);
        if ($request->has('ajax')) {
            if ($item == true) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Payment has been done Successfully !',
                        'data' => $item,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Some thing went wrong'
                    ]
                );
            }
        } else {
            if (isset($item->id)) {
                toastr()->success('Payment has been done Successfully !');
                return redirect()->back();
            } else {
                toastr()->error($item);
                return redirect()->back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Http\Response
     */
    public function show(BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Http\Response
     */
    public function edit(BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBalanceSheetRequest  $request
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBalanceSheetRequest $request, BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(BalanceSheet $balanceSheet)
    {
        //
    }
    public function getBalanaceCategories(Request $request)
    {
        $categories = BalanceCategory::where('type', $request->transaction_type)->get();
        return response()->json(
            [
                'data' => $categories,
            ],200
        );
    }
}
