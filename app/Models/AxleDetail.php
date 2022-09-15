<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AxleDetail extends Model
{
    use HasFactory;

    protected $table = "axledetails";

    public function axleBodyType(){
        return $this->belongsTo(AxleBodyType::class);
    }
}
