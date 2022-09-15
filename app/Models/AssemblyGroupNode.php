<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyGroupNode extends Model
{
    use HasFactory;

    protected $table = "assemblygroupnodes";

    protected $guarded = [];

    public function genericArticle()
    {
        return $this->hasMany(GenericArticle::class);
    }

}
