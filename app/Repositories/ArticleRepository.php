<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleVehicleTree;
use App\Models\LinkageTarget;
use App\Repositories\Interfaces\ArticleInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleRepository implements ArticleInterface
{
    public function store($request)
    {

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'mfrId' => 'required',
                'assemblyGroupNodeId' => 'required',
                'dataSupplierId' => 'required',
                'articleNumber' => 'required|unique:articles',

            ]);
            if ($request->has('ajax')) {
                if ($validator->fails()) {
                    return response()->json(
                        [
                            'message' => 'Article Number is Already Taken',
                        ]
                    );
                }
                $data = $request->except('_token', 'ajax');
            } else {
                if ($validator->fails()) {
                    return redirect('article.index')
                        ->withErrors($validator)
                        ->withInput();
                }
                $data = $request->except('_token');
            }
            $max_article_id = Article::max('legacyArticleId');
            if (!empty($max_article_id)) {
                $data['legacyArticleId'] = $max_article_id + 1;
            } else {
                $data['legacyArticleId'] = 1;
            }
            $linkingTargetType = LinkageTarget::select('linkageTargetType')->where('linkageTargetId', $data['linkingTargetId'])->first();
            $data['linkingTargetType'] = $linkingTargetType->linkageTargetType;
            $articleData = Arr::except($data, ['modelSeries', 'linkingTargetId', 'linkingTargetType']);

            $item = Article::create($articleData);
            ArticleVehicleTree::create([
                'linkingTargetId' => $data['linkingTargetId'],
                'legacyArticleId' => $data['legacyArticleId'],
                'assemblyGroupNodeId' => $data['assemblyGroupNodeId'],
                'linkingTargetType' => $data['linkingTargetType'],
            ]);
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
        if ($request->has('ajax')) {
            $data = $request->except('_token', 'avt_id', '_method', 'ajax');
        } else {
            $data = $request->except('_token', 'avt_id', '_method');
        }
        $linkingTargetType = LinkageTarget::select('linkageTargetType')->where('linkageTargetId', $data['linkingTargetId'])->first();
        $data['linkingTargetType'] = $linkingTargetType->linkageTargetType;
        $articleData = Arr::except($data, ['modelSeries', 'linkingTargetId', 'linkingTargetType']);
        // dd($data);
        try {
            $avt = ArticleVehicleTree::find($request->avt_id);
            DB::beginTransaction();
            $item = $article = Article::find($id);
            $article->update($articleData);
            if ($avt) {
                $avt->update([
                    'linkingTargetId' => $data['linkingTargetId'],
                    'assemblyGroupNodeId' => $data['assemblyGroupNodeId'],
                    'linkingTargetType' => $data['linkingTargetType'],
                ]);
            }
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $e->getMessage();
        }
    }




    public function delete($request)
    {

        try {
            DB::beginTransaction();
            $article = Article::find($request->id);

            $article->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
