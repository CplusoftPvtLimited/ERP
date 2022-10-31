<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ambrand;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\KeyValue;
use App\Models\Language;
use App\Models\Manufacturer;
use App\Repositories\Interfaces\ArticleInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $articles = Article::select('id', 'legacyArticleId', 'articleNumber', 'mfrId', 'additionalDescription', 'assemblyGroupNodeId', 'created_at')
                ->when(isset($request['article_id']) && $request['article_id'] != null, function ($query) use ($request) {
                    $query->where('articleNumber', 'LIKE',  '%' . $request['article_id'] . '%');
                })
                ->when(isset($request['engine_sub_type']) && !empty($request['engine_sub_type']) && !empty($request['section_id']) && isset($request['section_id']), function ($query) use ($request) {
                    $query->whereHas('articleVehicleTree', function ($query) use ($request) {
                        $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
                    });
                })
                ->with(['assemblyGroup' => function ($query) {
                    $query->select('assemblyGroupNodeId', 'assemblyGroupName')->get();
                }, 'manufacturer'])
                ->orderBy('id', 'desc');
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
                                    url: "article/delete",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        "id" : ' . $row["id"] . ',
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    success: function(data) {
                                        location.reload();
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
    public function archivedProducts(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $articles = Article::select('id', 'legacyArticleId', 'articleNumber', 'mfrId', 'additionalDescription', 'assemblyGroupNodeId', 'created_at')
                ->when(isset($request['article_id']) && $request['article_id'] != null, function ($query) use ($request) {
                    $query->where('articleNumber', 'LIKE',  '%' . $request['article_id'] . '%');
                    $query->onlyTrashed();
                })
                ->when(isset($request['engine_sub_type']) && !empty($request['engine_sub_type']) && !empty($request['section_id']) && isset($request['section_id']), function ($query) use ($request) {
                    $query->whereHas('articleVehicleTree', function ($query) use ($request) {
                        $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
                        $query->onlyTrashed();
                    });
                })
                ->with(['assemblyGroup' => function ($query) {
                    $query->select('assemblyGroupNodeId', 'assemblyGroupName')->get();
                }, 'manufacturer'])
                ->onlyTrashed()
                ->orderBy('id', 'desc');
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
                                <button class="btn btn-info btn-sm" onclick="restoreProduct(\'' . $row["id"] . '\')"><i class="fa fa-undo"></i></button>
                                </div>
                            </div>
                            ';
                    return $btn;
                })
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['action', 'section', 'manufacturer', 'index'])
                ->make(true);
        }

        return view('articles.archived-products');
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
        $suppliers = Ambrand::withTrashed()->get();
        $sections = AssemblyGroupNode::withTrashed()->get();
        $manufacturers = Manufacturer::withTrashed()->get();
        $keyValues = KeyValue::all();
        $languages = Language::select('lang')->distinct()->get();
        $article = Article::select()
            ->with('articleCriteria', function ($query) {
                $query->withTrashed();
            })
            ->with('articleCrosses', function ($query) {
                $query->withTrashed();
            })
            ->with('articleEAN', function ($query) {
                $query->withTrashed();
            })
            ->with('articleLink', function ($query) {
                $query->withTrashed();
            })
            ->with('articleVehicleTree', function ($query) {
                $query->with('linkageTarget', function ($query) {
                    $query->select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth', 'vehicleModelSeriesId');
                    $query->withTrashed();
                    $query->with('modelSeries', function ($query) {
                        $query->select('modelId', 'modelname');
                        $query->withTrashed();
                    });
                    $query->withTrashed();
                });
            })
            ->with('manufacturer', function ($query) {
                $query->select('linkingTargetType', 'manuId', 'manuName');
                $query->withTrashed();
            })
            ->with('assemblyGroup', function ($query) {
                $query->select('assemblyGroupNodeId', 'assemblyGroupName');
                $query->withTrashed();
            })
            ->find($id);

        if ($article) {
            return view('articles.edit', compact('suppliers', 'sections', 'manufacturers', 'article', 'keyValues', 'languages'));
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
        $article = Article::find($request->id);
        if ($article) {
            $article->articleCriteria()->delete();
            $article->articleCrosses()->delete();
            $article->articleEAN()->delete();
            $article->articleLink()->delete();
            $article->articleVehicleTree()->delete();
            $article->delete();
            DB::commit();
            toastr()->success('Product Deleted Successfully');
            return redirect()->route('article.archived');
        } else {
            DB::rollBack();
            toastr()->error('Something went wrong');
            return redirect()->route('article.archived');
        }
    }

    public function articlesByReferenceNo(Request $request)
    {
        try {
            $articles = Article::where('articleNumber', 'LIKE', '%' . $request->name . '%')->paginate(10);
            return response()->json([
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    
    public function restoreProduct(Request $request)
    {
        // dd($request->id);
        try {
            $article = Article::onlyTrashed()
                ->with('articleCriteria', function ($query) {
                    $query->onlyTrashed()->restore();
                })
                ->with('articleCrosses', function ($query) {
                    $query->onlyTrashed()->restore();
                })
                ->with('articleEAN', function ($query) {
                    $query->onlyTrashed()->restore();
                })
                ->with('articleLink', function ($query) {
                    $query->onlyTrashed()->restore();
                })
                ->with('articleVehicleTree', function ($query) {
                    $query->onlyTrashed()->restore();
                })
                ->findOrFail($request->id)
                ->restore();
            toastr()->success('Product Restored Successfully');
            return redirect()->route('article.archived');
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->route('article.archived');
        }
    }
}
