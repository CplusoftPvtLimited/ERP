<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkageTarget extends Model
{
    use HasFactory;

    protected $table = "linkagetargets";

    public function model() {
        return $this->belongsTo(ModelSeries::class, 'vehicleModelSeriesId', 'modelId');
    }

    public function section() {
        return $this->hasMany(AssemblyGroupNode::class, 'request__linkingTargetType', 'linkageTargetId');
    }
}
