<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Interfaces\ArticleInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleRepository implements ArticleInterface
{
    public function store($request){
        
        try {
            // dd($request->all());
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'mfrId' => 'required',
                'assemblyGroupNodeId' => 'required',
                'dataSupplierId' => 'required',
                'articleNumber' => 'required|unique:articles',
                
            ]);
            if($request->has('ajax'))
            {
                if ($validator->fails()) {
                    return response()->json(
                        [
                            'message' => 'Article Number is Already Taken',
                        ]
                    );
                }
                $data = $request->except('_token','ajax');
                // dd(1);
            }else{
                if ($validator->fails()) {
                    return redirect('article.index')
                                ->withErrors($validator)
                                ->withInput();
                }
                $data = $request->except('_token');
                // dd($data);
            }
            $max_article_id = Article::max('legacyArticleId');
            // dd($max_engine_id);
            if (!empty($max_article_id)) {
                $data['legacyArticleId'] = $max_article_id + 1;
            } else {
                $data['legacyArticleId'] = 1;
            }
            // dd($data);
            $item = Article::create($data);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $e->getMessage();
        }
    }

    public function update($request,$id){
        
        try {
            DB::beginTransaction();
            $article = Article::find($id);
            // dd($data);
            $article->update($request->all());

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }




    public function delete($request){
        
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
