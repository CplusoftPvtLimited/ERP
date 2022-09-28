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
        $validator = Validator::make($data->all(),[
            'url' => 'required|unique:articlelinks|max:255',
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
}
