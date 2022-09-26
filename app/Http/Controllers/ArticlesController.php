<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ambrand;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\Manufacturer;
use App\Repositories\Interfaces\ArticleInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $article;

    public function __construct(ArticleInterface $articleInterface)
    {
        $this->article = $articleInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $articles = Article::select('id','legacyArticleId','articleNumber','mfrId','additionalDescription','assemblyGroupNodeId')
            ->with(['assemblyGroup' => function($query){
                $query->select('assemblyGroupNodeId','assemblyGroupName')->get();
            }, 'manufacturer']);
            // $articles = [];
            // foreach ($articless as $article) {
            //     // $manufacturer = Manufacturer::where('manuId',$article->mfrId)->first();
            //     $section = AssemblyGroupNode::where('assemblyGroupNodeId',$article->assemblyGroupNodeId)->first();
            //     // $article['manufacturer'] = $manufacturer ? $manufacturer->manuName : 'N/A';
            //     $article['section'] = $section ? $section->assemblyGroupName : 'N/A';
            //     array_push($articles,$article);
            // }
            return DataTables::of($articles)
                ->addIndexColumn()
                ->addColumn('manufacturer', function ($row) {
                    return isset($row->manufacturer->manuName) ? $row->manufacturer->manuName : "N/A";
                    
                })
                ->addColumn('section', function ($row) {
                    return isset($row->assemblyGroup->assemblyGroupName) ? $row->assemblyGroup->assemblyGroupName : "N/A";
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="article/' . $row["id"] . '/edit"> <button
                                    class="btn btn-primary btn-sm " type="button"
                                    data-original-title="btn btn-danger btn-xs"
                                    title=""><i class="fa fa-edit"></i></button></a>
                                </div>
                                <div class="col-md-2">
                                    <a> <button
                                    class="btn btn-danger btn-sm" onclick="deleteSection(' . $row['id'] . ')" style="" type="button"
                                    data-original-title="btn btn-danger btn-sm"
                                    title=""><i class="fa fa-trash"></i></button></a>
                                </div>
                            </div>
                         ';
                    return $btn;
                })
                ->rawColumns(['action','section', 'manufacturer'])
                ->make(true);
        }

        return view('articles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Ambrand::all();
        $sections = AssemblyGroupNode::all();
        $manufacturers = Manufacturer::all();

        return view('articles.create',compact('suppliers','sections','manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = $this->article->store($request);
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
            if(is_object($item)){
                return redirect()->route('article.index')->withSuccess(__('Product Added Successfully.'));
            }else{
                return redirect()->back()->withError(__('Some thing went wrong'));
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
        $suppliers = Ambrand::all();
        $sections = AssemblyGroupNode::all();
        $manufacturers = Manufacturer::all();
        $article = Article::find($id);

        return view('articles.edit',compact('suppliers','sections','manufacturers','article'));
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
        $item = $this->article->update($request,$id);
        if($item == true){
            return redirect()->route('article.index')->withSuccess(__('Product Updated Successfully.'));
        }else{
            return redirect()->back()->withError(__('Some thing went wrong'));
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
        $item = $this->article->delete($request);
        if($item == true){
            return redirect()->route('article.index')->withSuccess(__('Product Deleted Successfully.'));
        }else{
            return redirect()->back()->withError(__('Some thing went wrong'));
        }
    }
}
