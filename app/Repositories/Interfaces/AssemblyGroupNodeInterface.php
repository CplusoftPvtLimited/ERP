<?php
namespace App\Repositories\Interfaces;

interface AssemblyGroupNodeInterface{

    public function store($request);
    public function update($request,$id);
    public function delete($request);
    

}