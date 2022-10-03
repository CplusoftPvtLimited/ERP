<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AssemblyGroupNodeInterface;
use Illuminate\Http\Request;
use App\Models\Ambrand;
use App\Models\Language;

class AssemblyGroupNodeController extends Controller
{
    private $assemblyGroupNode;

    public function __construct(AssemblyGroupNodeInterface $assemblyGroupNodeInterface)
    {
        $this->assemblyGroupNode = $assemblyGroupNodeInterface;
        // $this->auth_user = auth()->guard('api')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assemblyGroupNodes = $this->assemblyGroupNode->index();
        //    dd($assemblyGroupNodes);
        return view('assembly_group_node.index', compact('assemblyGroupNodes'));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
        $assemblyGroupNode = $this->assemblyGroupNode->show($id);
        return view('assembly_group_node.show', compact('assemblyGroupNode'));
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

    public  function getSectionParts($id)
    {

        $getSectionParts = $this->assemblyGroupNode->getSectionParts($id);
        // dd($getSectionParts);
        return view('assembly_group_node.section_parts', compact('getSectionParts'));
    }

    public  function getLanguage($id)
    {

        $brand = Ambrand::find($id);
        if ($brand) {
            $languages = Language::select('languageCode', 'languageName')->where('lang', $brand->lang)->orderBy('lang', 'desc')->paginate(10);
            return view('assembly_group_node.brand_language_index', compact('languages'));
        }
        return redirect()->back();
    }
}
