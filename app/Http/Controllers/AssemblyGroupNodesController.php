<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\Language;
use App\Models\LinkageTarget;
use App\Repositories\Interfaces\AssemblyGroupNodeInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssemblyGroupNodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $sectionRepository;
    private $val = 0;

    public function __construct(AssemblyGroupNodeInterface $sectionInterface)
    {
        $this->sectionRepository = $sectionInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(AssemblyGroupNode::orderBy('id', 'desc'))
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                                <div class="col-md-2 mr-1">
                                    <a href="section/' . $row["id"] . '/edit"> <button
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
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['action', 'index'])
                ->toJson();
        }

        return view('assembly_group_nodes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')->get();
        $sections = AssemblyGroupNode::all();
        return view('assembly_group_nodes.create', compact('languages', 'engines', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $engine = $this->sectionRepository->store($request);
        if ($engine == true) {
            return redirect()->route('section.index')->with('create_message', 'Section created successfully');
        } else if ($engine == "notfound") {
            return redirect()->route('section.index')->with('error', "Please enter section name");
        } else {
            // dd($engine->getMessage());
            return redirect()->route('section.index')->with('error', $engine->getMessage());
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
        $section = AssemblyGroupNode::find($id);

        $languages = Language::all();
        $engines = LinkageTarget::withTrashed()->get();
        $sections = AssemblyGroupNode::withTrashed()->get();
        return view('assembly_group_nodes.edit', compact('languages', 'engines', 'section', 'sections'));
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
        $engine = $this->sectionRepository->update($request, $id);
        if ($engine == true) {
            return redirect()->route('section.index')->with('create_message', 'Section Updated successfully');
        } else {
            // dd($engine->getMessage());
            return redirect()->route('section.index')->with('error', $engine->getMessage());
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
        $engine = $this->sectionRepository->delete($request);
        if ($engine == true) {
            return redirect()->route('section.index')->with('create_message', 'Section Deleted successfully');
        } else {
            // dd($engine->getMessage());
            return redirect()->route('section.index')->with('error', $engine->getMessage());
        }
    }
    public function getSectionsByEngine(Request $request)
    {
        try {
            $sections = AssemblyGroupNode::select('assemblyGroupNodeId', 'assemblyGroupName')
                ->where('request__linkingTargetId', $request->engine_id)
                ->where('request__linkingTargetType',  $request->engine_sub_type)
                ->get();
            return response()->json([
                'data' => $sections
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function searchSectionsByEngine(Request $request)
    {
        // dd($request->all());
        try {
            $sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')->whereHas('articleVehicleTree', function ($query) use ($request) {
                $query->where('linkingTargetId', $request->engine_id)
                    ->where('linkingTargetType', $request->engine_sub_type);
            })
                ->limit(100)
                ->get();
            return response()->json([
                'data' => $sections
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getSectionParts(Request $request)
    {
        try {
            $section_parts = Article::select('legacyArticleId', 'dataSupplierId', 'genericArticleDescription', 'articleNumber')->whereHas('articleVehicleTree', function ($query) use ($request) {
                $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
            })
                ->limit(100)
                ->get();

            // dd($section_parts);
            return response()->json([
                'data' => $section_parts
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
