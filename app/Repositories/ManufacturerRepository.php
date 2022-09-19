<?php

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Repositories\Interfaces\ManufacturerInterface;
use Illuminate\Support\Facades\DB;

class ManufacturerRepository implements ManufacturerInterface
{
    public function store($data)
    {
        $input= $data->except('_token');
        $manu = Manufacturer::max('manuId');
        $manuId = ++$manu;
        $input['manuId'] = $manuId;
        DB::beginTransaction();
        try {
            $manufacturer = Manufacturer::create($input);
            DB::commit();
            return $manufacturer;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function update($data, $item)
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