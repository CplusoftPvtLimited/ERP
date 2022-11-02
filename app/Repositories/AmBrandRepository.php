<?php

namespace App\Repositories;

use App\Models\Ambrand;
use App\Repositories\Interfaces\AmBrandInterface;
use Illuminate\Support\Facades\DB;

class AmBrandRepository implements AmBrandInterface
{
    public function store($data)
    {
        $input= $data->except('_token');
        $brand = Ambrand::max('brandId');
        $brandId = ++$brand;
        $input['brandId'] = $brandId;
        $brandLogoId = rand(1,99999);
        $input['brandLogoID'] = $brandLogoId;
        DB::beginTransaction();
        try {
            $supplier = Ambrand::create($input);
            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function update($data,$item)
    {
        $input = $data->except('_token');
        DB::beginTransaction();
        try {
                $data = $item->update($input);
                DB::commit();
                return $item;
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function delete($item)
    {
        try {
            $data = $item->delete();
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}