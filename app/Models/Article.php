<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    // protected $table = "articles";

    protected $table = "Articles";


    public function articleCriteria()
    {
        return $this->hasOne(ArticleCriteria::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleDocs()
    {
        return $this->hasOne(ArticleDocs::class);
    }

    public function articleCrosses()
    {
        return $this->hasOne(ArticleDCross::class);
    }

    public function articleEAN()
    {
        return $this->hasOne(ArticleEAN::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleMain()
    {
        return $this->hasOne(ArticleMain::class);
    }

    public function articleVehicleTree(): HasOne
    {
        return $this->hasOne(ArticleVehicleTree::class, 'legacyArticleId', 'legacyArticleId');
    }

    public function articleText()
    {
        return $this->hasOne(ArticleText::class);
    }

    public function genericArticles()
    {
        return $this->hasMany(GenericArticle::class);
    }

    public function brand()
    {   // usefull
        return $this->belongsTo(Ambrand::class, 'dataSupplierId', 'brandId');
    }

    public function brands()
    {
        return $this->hasMany(Ambrand::class, 'brandId', 'dataSupplierId');
    }

    public function section()
    {
        return $this->belongsTo(AssemblyGroupNode::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }

    public function stock()
    {
        return $this->belongsTo(StockManagement::class, 'legacyArticleId', 'product_id');
    }
}