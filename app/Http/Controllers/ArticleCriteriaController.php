<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ArticleCriteriaInterface;
use Illuminate\Http\Request;

class ArticleCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $articleCriteria;

    public function __construct(ArticleCriteriaInterface $articleCriteria)
    {
        $this->articleCriteria = $articleCriteria;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = $this->articleCriteria->store($request);
        // dd($item);
        if($request->has('ajax')){
            if($item == true){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Product Criteria Details Saved Successfully.',
                        'data' => $item,
                    ]
                );
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Some thing went wrong'
                    ]
                );
            }    
        }
        else{
            if(isset($item->id)){
                return redirect()->route('article.index')->withSuccess(__('Product Criteria Details Saved Successfully.'));
            }else{
                return redirect()->back();
            }
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
        $item = $this->articleCriteria->update($request, $id);
        // dd($item);
        if ($request->has('ajax')) {
            if (isset($item->id)) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Product Criteria Details Updated Successfully.',
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
                return redirect()->route('article.index')->withSuccess(__('Product Criteria Details Updated Successfully.'));
            } else {
                return redirect()->back();
            }
        }
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
