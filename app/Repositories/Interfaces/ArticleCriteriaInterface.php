<?php
namespace App\Repositories\Interfaces;

interface ArticleCriteriaInterface{
    public function store($data);
    public function update($request,$id);

}