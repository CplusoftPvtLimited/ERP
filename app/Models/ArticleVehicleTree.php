<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleVehicleTree extends Model
{
    use HasFactory;

    protected $table = "articlesvehicletrees";

    public function articles(){
        return $this->belongsTo(Article::class);
    }

    public function vehicles(){
        return $this->belongsTo(VehicleTree::class);
    }

}
