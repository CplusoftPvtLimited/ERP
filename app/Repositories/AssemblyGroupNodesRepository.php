<?php

namespace App\Repositories;

use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
use App\Models\Manufacturer;
use App\Repositories\Interfaces\AssemblyGroupNodeInterface;
// use App\Repositories\Interfaces\AssemblyGroupNodesInterface;
use App\Repositories\Interfaces\LinkageTargetInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AssemblyGroupNodesRepository implements AssemblyGroupNodeInterface
{
    public function store($request){
        
        try {
            // dd($request->all());
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'assemblyGroupName' => 'required',
                'hasChilds' => 'required',
                'shortCutId' => 'required',
                'lang' => 'required',
                'request__linkingTargetId' => 'required',
                'parentNodeId' => 'required',
            ]);
     
            if ($validator->fails()) {
                return redirect('section.index')
                            ->withErrors($validator)
                            ->withInput();
            }   
            $data = $request->except('_token');
            // dd($data);
            $max_section_id = AssemblyGroupNode::max('assemblyGroupNodeId');
            // dd($max_engine_id);
            $engine = LinkageTarget::where('linkageTargetId',$request->request__linkingTargetId)->first();
            // dd($manufacture_name);
            if (!empty($max_section_id)) {
                $data['assemblyGroupNodeId'] = $max_section_id + 1;
            } else {
                $data['assemblyGroupNodeId'] = 1;
            }
            $data['request__linkingTargetType'] = $engine->linkageTargetType;
            // dd($data);
            AssemblyGroupNode::create($data);
           
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return $e->getMessage();
        }
    }

    public function update($request,$id){
        
        try {
            DB::beginTransaction();
            $section = AssemblyGroupNode::find($id);
        //    dd($manufacture_name);
            $engine = LinkageTarget::where('linkageTargetId',$request->request__linkingTargetId)->first();
            $data = [
                'assemblyGroupName' => $request->assemblyGroupName,
                'hasChilds' => $request->hasChilds,
                'shortCutId' => $request->shortCutId,
                'lang' => $request->lang,
                'request__linkingTargetId' => $request->request__linkingTargetId,
                'request__linkingTargetType' => $engine->linkageTargetType,
                'parentNodeId' => $request->parentNodeId,
            ];
            // dd($data);
            $section->update($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }




    public function delete($request){
        
        try {
            DB::beginTransaction();
            $section = AssemblyGroupNode::find($request->id);
            
            $section->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
