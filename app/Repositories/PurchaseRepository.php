<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PurchaseInterface;

class PurchaseRepository implements PurchaseInterface
{
    public function store($request){
        dd($request->all());
    }    
}
