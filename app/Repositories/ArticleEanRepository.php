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
        $validator = Validator::make($data->all(),[
            'eancode' => 'required|unique:articleean|max:255',
            'legacyArticleId' => 'required'
        ]);
        if($data->has('ajax'))
            {
                if ($validator->fails()) {
                    return response()->json(
                        [
                            'message' => 'Something Went Wrong',
                        ]
                    );
                }
                $input = $data->except('_token','ajax');
            }else{
                if ($validator->fails()) {
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
}
