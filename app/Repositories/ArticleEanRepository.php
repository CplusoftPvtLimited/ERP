<?php

namespace App\Repositories;

use App\Models\ArticleCross;
use App\Models\ArticleEAN;
use App\Repositories\Interfaces\ArticleEanInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleEanRepository implements ArticleEanInterface
{
    public function store($data)
    {
        $validator = Validator::make($data->all(), [
            'eancode' => 'required|max:255|unique:articleean,eancode',
            'legacyArticleId' => 'required'
        ]);
        if ($data->has('ajax')) {
            if ($validator->fails()) {
                return response()->json(
                    [
                        'message' => 'Something Went Wrong',
                    ]
                );
            }
            $input = $data->except('_token', 'ajax');
        } else {
            if ($validator->fails()) {
                // dd($validator);
                return redirect('article.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            $input = $data->except('_token');
        }
        try {
            DB::beginTransaction();
            $item = ArticleEAN::create($input);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function update($request, $id)
    {
        // dump($request->all());
        // dd($id);
        $validator = Validator::make($request->all(), [
            'eancode' => 'required|max:255|unique:articleean,eancode,' . $id,
        ]);
        if ($request->has('ajax')) {
            if ($validator->fails()) {
                return response()->json(
                    [
                        'message' => 'Something Went Wrong',
                    ]
                );
            }
            $data = $request->except('_token', '_method', 'ajax');
        } else {
            if ($validator->fails()) {
                // dd('klkk');
                return redirect('article.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            $data = $request->except('_token', '_method');
        }
        try {
            $item = ArticleEAN::find($id);
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
