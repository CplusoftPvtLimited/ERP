<?php

namespace App\Repositories;

use App\Models\ArticleLinks;
use App\Repositories\Interfaces\ArticleLinksInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleLinksRepository implements ArticleLinksInterface
{
    public function store($data)
    {
        $validator = Validator::make($data->all(), [
            'url' => 'required|max:255|unique:articlelinks,url',
            'legacyArticleId' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('article.index')
                ->withErrors($validator)
                ->withInput();
        }
        $input = $data->except('_token');
        try {
            DB::beginTransaction();
            $item = ArticleLinks::create($input);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $e->getMessage();
        }
    }
    public function update($request, $id)
    {
        // dump($request->all());
        // dd($id);
        $validator = Validator::make($request->all(), [
            'url' => 'required|max:255|unique:articlelinks,url,' . $id,
        ]);
        if ($validator->fails()) {
            // dd('klkk');
            return redirect('article.index')
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->except('_token', '_method');
        try {
            $item = ArticleLinks::find($id);
            $item->update($data);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return $e->getMessage();
        }
    }
}
