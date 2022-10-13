<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleVehicleTree extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "articlesvehicletrees";
    protected $guarded = [];

    public function article() {
        return $this->belongsTo(Article::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleText() {
        return $this->belongsTo(ArticleText::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function linkageTarget() {
        return $this->belongsTo(LinkageTarget::class, 'linkingTargetId', 'linkageTargetId');
    }

    public function assemblyGroupNodes() {
        return $this->belongsTo(AssemblyGroupNode::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }
    
}
