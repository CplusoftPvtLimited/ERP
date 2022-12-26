<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssemblyGroupNode extends Model
{
    use HasFactory, SoftDeletes;

    // protected $table = "assemblygroupnodes";

    protected $table = "AssemblyGroupNodes";

    public function genericArticle()
    {
        return $this->hasMany(GenericArticle::class);
    }
    public function linkageTarget()
    {
        return $this->belongsTo(LinkageTarget::class, 'request__linkingTargetId', 'linkageTargetId');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }
    public function articleVehicleTree()
    {
        return $this->hasMany(ArticleVehicleTree::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }

    public function subSection()
    {
        return $this->hasMany(AssemblyGroupNode::class, 'parentNodeId', 'assemblyGroupNodeId');
    }

    public function allSubSection()
    {
        return $this->subSection()->with('allSubSection');
    }
}