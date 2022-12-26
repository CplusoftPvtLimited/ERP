<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleVehicleTree extends Model
{
    use HasFactory;

    // protected $table = "articlesvehicletrees";

    protected $table = "articlesVehicleTrees2";

    public function article()
    {
        return $this->belongsTo(Article::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleText()
    {
        return $this->belongsTo(ArticleText::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function linkageTarget()
    {
        return $this->belongsTo(LinkageTarget::class, 'linkingTargetId', 'linkageTargetId');
    }

    public function assemblyGroupNodes()
    {
        return $this->belongsTo(AssemblyGroupNode::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }
}