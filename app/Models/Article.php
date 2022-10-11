<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";
    protected $guarded = [];

    public function articleCriteria(){
        return $this->hasOne(ArticleCriteria::class);
    }

    public function articleDocs(){
        return $this->hasOne(ArticleDocs::class);
    }

    public function articleCrosses(){
        return $this->hasOne(ArticleDCross::class);
    }

    public function articleEAN(){
        return $this->hasOne(ArticleEAN::class);
    }

    public function articleMain(){
        return $this->hasOne(ArticleMain::class);
    }

    public function articleVehicleTree(){
        return $this->hasOne(ArticleVehicleTree::class);
    }

    public function articleText(){
        return $this->hasOne(ArticleText::class);
    }

    public function genericArticles()
    {
        return $this->hasMany(GenericArticle::class);
    }

    public function brand() {   // usefull
        return $this->belongsTo(Ambrand::class,'dataSupplierId', 'brandId');
    }

    public function brands() : hasMany{
        return $this->hasMany(Ambrand::class,'brandId','dataSupplierId');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'mfrId', 'manuId');
    }

    public function assemblyGroup()
    {
        return $this->belongsTo(AssemblyGroupNode::class, 'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }
    public function section() { 
        return $this->belongsTo(AssemblyGroupNode::class,'assemblyGroupNodeId', 'assemblyGroupNodeId');
    }

}
