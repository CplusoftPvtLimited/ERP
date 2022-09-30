<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable =[
        "meta", "rate", "type"
    ];

    public function product()
    {
    	return $this->hasMany('App/Product');
    	
    }
}
