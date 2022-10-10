<?php
namespace App\Repositories\Interfaces;

interface SaleInterface{

    public function store($data); // for purchase create
    public function update($data,$id); // for purchase update
    

}