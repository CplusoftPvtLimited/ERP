<?php
namespace App\Repositories\Interfaces;

interface LinkageTargetInterface{

    public function store($request);
    public function update($request,$id);
    public function delete($request);
    

}