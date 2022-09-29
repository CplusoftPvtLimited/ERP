<?php

namespace App\Repositories\Interfaces;

interface ArticleEanInterface
{
    public function store($data);
    public function update($request, $id);
}
