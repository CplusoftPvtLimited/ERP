<?php
    namespace App\Repositories;

use App\Models\ArticleCriteria;
use App\Repositories\Interfaces\ArticleCriteriaInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleCriteriaRepository implements ArticleCriteriaInterface
{
    public function store($data)
    {
        // dd($data->all());
        $validator = Validator::make($data->all(),[
            'criteriaType' => 'max:5',
            'criteriaId' => 'unique:articlecriteria',
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
            $max_crId = ArticleCriteria::max('criteriaId');
            if(!empty($max_crId))
            {
                $input['criteriaId'] = $max_crId +1;
            }else{
                $input['criteriaId'] = 1;
            }
            if(! $data->has('isMandatory'))
            {
                $input['isMandatory'] = 0;
            }
            if(! $data->has('isInterval'))
            {
                $input['isInterval'] = 0;
            }
            if(! $data->has('immediateDisplay'))
            {
                $input['immediateDisplay'] = 0;
            }
            $input['successorCriteriaId'] = $input['criteriaId'];
            $item = ArticleCriteria::create($input);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function update($request, $id)
    {
        if ($request->has('ajax')) {
            $data = $request->except('_token', '_method', 'ajax');
        } else {
            $data = $request->except('_token', '_method');
        }
        try {
            if(! $request->has('isMandatory'))
            {
                $data['isMandatory'] = 0;
            }
            if(! $request->has('isInterval'))
            {
                $data['isInterval'] = 0;
            }
            if(! $request->has('immediateDisplay'))
            {
                $data['immediateDisplay'] = 0;
            }
            $item = ArticleCriteria::find($id);
            $item->update($data);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
