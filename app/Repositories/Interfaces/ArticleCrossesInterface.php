<?php
namespace App\Repositories\Interfaces;

interface ArticleCrossesInterface{
    public function store($data);
    public function update($request,$id);
}