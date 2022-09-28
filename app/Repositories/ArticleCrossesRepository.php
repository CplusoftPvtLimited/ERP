<?php
    namespace App\Repositories;

use App\Models\ArticleCross;
use App\Repositories\Interfaces\ArticleCrossesInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleCrossesRepository implements ArticleCrossesInterface
{
    public function store($data)
    {
        $validator = Validator::make($data->all(),[
            'oemNumber' => 'required|unique:articlecrosses|max:255',
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
                $item = ArticleCross::create($input);
                DB::commit();
                return $item;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            }
    }
}
