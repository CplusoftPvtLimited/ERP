<?php
namespace App\Repositories\Interfaces;

interface AmBrandInterface{
    public function store($data);
    public function update($data,$item);
    public function delete($item);

}