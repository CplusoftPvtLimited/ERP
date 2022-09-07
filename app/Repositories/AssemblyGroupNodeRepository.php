<?php

namespace App\Repositories;

use App\Models\ArticleVehicleTree;
use App\Models\AssemblyGroupNode;
use App\Models\Ambrand;

use App\Repositories\Interfaces\AssemblyGroupNodeInterface;

class AssemblyGroupNodeRepository implements AssemblyGroupNodeInterface
{
    // SELECT distinct assemblyGroupNodeId, assemblyGroupName FROM assemblygroupnodes
    public function index()
    {
          $assemblyGroupNodes = AssemblyGroupNode::Select('id','assemblyGroupNodeId','assemblyGroupName','request__linkingTargetId','request__linkingTargetType')->distinct()->paginate(10);
          return $assemblyGroupNodes;
    }
    public function show($id){
         $assemblyGroupNode = AssemblyGroupNode::find($id);
         return $assemblyGroupNode;
    }

    public function getSectionParts($id)
    {
        $assemblyGroupNode = AssemblyGroupNode::find($id); // itself is a section
        $getSectionParts = ArticleVehicleTree::where('linkingTargetId',$assemblyGroupNode->request__linkingTargetId)->where('assemblyGroupNodeId',$assemblyGroupNode->assemblyGroupNodeId)->where('linkingTargetType',$assemblyGroupNode->request__linkingTargetType)->with(['article' => function($query){
            $query->select(['legacyArticleId','genericArticleDescription','articleNumber','dataSupplierId']);
            $query->with('brand');
        }])->distinct()->paginate(10);

        return $getSectionParts;
        // dd($getSectionParts);
    }

    public function languages($id)
    {
        $brand = Ambrand::find($id);
        $assemblyGroupNode = AssemblyGroupNode::find($id); // itself is a section
        $getSectionParts = ArticleVehicleTree::where('linkingTargetId',$assemblyGroupNode->request__linkingTargetId)->where('assemblyGroupNodeId',$assemblyGroupNode->assemblyGroupNodeId)->where('linkingTargetType',$assemblyGroupNode->request__linkingTargetType)->with(['article' => function($query){
            $query->select(['legacyArticleId','genericArticleDescription','articleNumber','dataSupplierId']);
            $query->with('brand');
        }])->distinct()->paginate(10);

        return $getSectionParts;
        // dd($getSectionParts);
    }
}
