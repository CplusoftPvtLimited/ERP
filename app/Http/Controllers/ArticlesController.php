<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ambrand;
use App\Models\Article;
use App\Models\ArticleCriteria;
use App\Models\ArticleCross;
use App\Models\ArticleEAN;
use App\Models\ArticleLinks;
use App\Models\ArticleVehicleTree;
use App\Models\AssemblyGroupNode;
use App\Models\KeyValue;
use App\Models\Language;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
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
    private $val = 0;

    public function __construct(ArticleInterface $articleInterface)
    {
        $this->article = $articleInterface;
    }
    public function index(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $articles = Article::select('id', 'legacyArticleId', 'articleNumber', 'mfrId', 'additionalDescription', 'assemblyGroupNodeId', 'created_at');
            if (isset($request['article_id']) && $request['article_id'] != null) {
                $articles =  $articles->where('articleNumber', 'LIKE',  '%' . $request['article_id'] . '%');
            } elseif (isset($request['engine_sub_type']) && !empty($request['engine_sub_type']) && !empty($request['section_id']) && isset($request['section_id'])) {

                $articles =  $articles->whereHas('articleVehicleTree', function ($query) use ($request) {
                    $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
                });
            }
            $articles->with(['assemblyGroup' => function ($query) {
                $query->select('assemblyGroupNodeId', 'assemblyGroupName')->get();
            }, 'manufacturer'])->orderBy('id', 'desc');
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
                                <button class="btn btn-danger btn-sm" type="button"
                                data-original-title="btn btn-danger btn-sm"
                                id="show_confirm_' . $row["id"] . '"
                                data-toggle="tooltip"><i
                                    class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                     <script type="text/javascript">
                        $.ajaxSetup({
                            headers: {
                                "X-CSRF-TOKEN": $(`meta[name="csrf-token"]`).attr("content")
                            }
                        });
                        </script>
                     <script type="text/javascript">
                         
                         $("#show_confirm_' . $row["id"] . '").click(async function(event) {
                 
                             const {
                                 value: email
                             } = await Swal.fire({
                                 title: "Are you sure?",
                                 text: "You wont be able to revert this!",
                                 icon: "warning",
                                 input: "text",
                                 inputLabel: "Type product/delete to delete item",
                                 inputPlaceholder: "Type product/delete to delete item",
                                 showCancelButton: true,
                                 inputValidator: (value) => {
                                     return new Promise((resolve) => {
                                         if (value != "product/delete") {
                                             resolve("Type product/delete to delete item")
                                         } else {
                                             resolve()
                                         }
                                     })
                                 },
                             })
                             if (email) {
                                 $.ajax({
                                    url: "article/' . $row["id"] . '",
                                    type: "DELETE",
                                    cache: false,
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    success: function(data) {
                                        $("#show_confirm_' . $row['id'] . '").parents("tr").remove();
                                    }
                                 })
                             }
                         });</script>';
                    return $btn;
                })
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['action', 'section', 'manufacturer', 'index'])
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
        $keyValues = KeyValue::all();
        $languages = Language::select('lang')->distinct()->get();

        return view('articles.create', compact('suppliers', 'sections', 'manufacturers', 'keyValues', 'languages'));
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
        if ($request->has('ajax')) {
            if ($item == true) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Product Basic Details Saved Successfully.',
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
                return redirect()->route('article.index')->withSuccess(__('Product Basic Details Saved Successfully.'));
            } else {
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
        $suppliers = Ambrand::all();
        $sections = AssemblyGroupNode::all();
        $article = Article::find($id);
        $manufacturers = Manufacturer::all();

        if ($article) {
            $manufacturer = Manufacturer::where('manuId', $article->mfrId)->first();
            $avt = ArticleVehicleTree::where('legacyArticleId', $article->legacyArticleId)->first();
            $engine = LinkageTarget::where('linkageTargetId', $avt->linkingTargetId)->first();
            $model = ModelSeries::where('modelId', $engine->vehicleModelSeriesId)->first();
            $section = AssemblyGroupNode::where('assemblyGroupNodeId', $article->assemblyGroupNodeId)->first();
            $keyValues = KeyValue::all();
            $languages = Language::select('lang')->distinct()->get();
            $art_criteria = ArticleCriteria::where('legacyArticleId', $article->legacyArticleId)->first();
            $art_crosses = ArticleCross::where('legacyArticleId', $article->legacyArticleId)->first();
            $art_ean = ArticleEAN::where('legacyArticleId', $article->legacyArticleId)->first();
            $art_link = ArticleLinks::where('legacyArticleId', $article->legacyArticleId)->first();
            return view('articles.edit', compact('suppliers', 'sections', 'manufacturers','manufacturer', 'article', 'keyValues', 'languages', 'engine', 'model', 'section', 'art_criteria', 'art_crosses', 'art_ean', 'art_link', 'avt'));
        } else {
            return redirect(url()->previous());
        }
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
        $item = $this->article->update($request, $id);
        // dd($item);
        if ($request->has('ajax')) {
            if (isset($item->id)) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Product Basic Details Updated Successfully.',
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
                return redirect()->route('article.index')->withSuccess(__('Product Basic Details Updated Successfully.'));
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
    public function delete(Request $request)
    {
        $item = $this->article->delete($request);
        if ($item == true) {
            return redirect()->route('article.index')->withSuccess(__('Product Deleted Successfully.'));
        } else {
            return redirect()->back()->withError(__('Some thing went wrong'));
        }
    }
    public function destroy($id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->delete();
            return response()->json(['data' => "true", 'id' => $id]);
        } else {
            return response()->json("false");
        }
    }
    public function articlesByReferenceNo(Request $request)
    {
        try {
            $articles = Article::where('articleNumber', 'LIKE', '%' . $request->name . '%')->paginate(10);
            // dd($articles);
            return response()->json([
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
