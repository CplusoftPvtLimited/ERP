<?php
namespace App\Repositories\Interfaces;

interface ManufacturerInterface{
    public function store($data);
    public function update($data,$item);
    public function delete($item);

}