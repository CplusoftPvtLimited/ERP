<?php

namespace App\Repositories\Interfaces;

interface ArticleLinksInterface
{
    public function store($data);
    public function update($request, $id);
}
