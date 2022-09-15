<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyMarkCarID extends Model
{
    use HasFactory;

    protected $table = "bodymarkcarids";

    public function bodyMark()
    {
        return $this->belongsTo(BodyMark::class);
    }
}
