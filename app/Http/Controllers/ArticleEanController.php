<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ArticleEanInterface;
use Illuminate\Http\Request;

class ArticleEanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $articleEan;

    public function __construct(ArticleEanInterface $articleEan)
    {
        $this->articleEan = $articleEan;
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
        $item = $this->articleEan->store($request);
        // dd($item);
        if($request->has('ajax')){
            if($item == true){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Data inserted successfully',
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
                return redirect()->route('article.index')->withSuccess(__('Product Ean Added Successfully.'));
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
