<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTree extends Model
{
    use HasFactory;

    // protected $table = "vehicletrees";

    protected $table = "VehicleTrees";

    public function articleVehicleTree()
    {
        return $this->belongsTo(ArticleVehicleTree::class);
    }
}