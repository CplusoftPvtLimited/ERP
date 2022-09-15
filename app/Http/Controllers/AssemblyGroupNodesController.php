<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function __construct(AssemblyGroupNodeInterface $sectionInterface)
    {
        $this->sectionRepository = $sectionInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sections = AssemblyGroupNode::all();
            return DataTables::of($sections)
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
                ->rawColumns(['action'])
                ->make(true);
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
        $engines = LinkageTarget::all();
        return view('assembly_group_nodes.create',compact('languages','engines'));
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
        if($engine == true){
            return redirect()->route('section.index')->with('create_message', 'Section created successfully');
        }else{
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
        $engines = LinkageTarget::all();
        return view('assembly_group_nodes.edit',compact('languages','engines','section'));
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
        $engine = $this->sectionRepository->update($request,$id);
        if($engine == true){
            return redirect()->route('section.index')->with('create_message', 'Section Updated successfully');
        }else{
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
        if($engine == true){
            return redirect()->route('section.index')->with('create_message', 'Section Deleted successfully');
        }else{
            // dd($engine->getMessage());
            return redirect()->route('section.index')->with('error', $engine->getMessage());
        }
    }
}
