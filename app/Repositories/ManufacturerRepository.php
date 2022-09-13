<?php

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Repositories\Interfaces\ManufacturerInterface;
use Illuminate\Support\Facades\DB;

class ManufacturerRepository implements ManufacturerInterface
{
    public function index()
    {
        $manufacturers = Manufacturer::paginate(10);
        return $manufacturers;
    }
    public function store($data)
    {
        $input= $data->except('_token');
        $manu = Manufacturer::max('manuId');
        $manuId = ++$manu;
        $input['manuId'] = $manuId;
        DB::beginTransaction();
        try {
            $manufacturer = Manufacturer::create($input);
            $message = "Manufacturer Added Successfully";
            DB::commit();
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return $message;
        }
    }
    public function update($data, $item)
    {
        $input = $data->except('_token');
        DB::beginTransaction();
        try {
            $otheritem = Manufacturer::where('manuName',$data->manuName)->first();
            if($otheritem->id != $item->id)
            {
                $message = "Manufacturer Name Already Exist";
                return $message;
            }
            else{
                $item->update($input);
                $message = "Manufacturer Updated Successfully";
                DB::commit();
                return $message;
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return $message;
        }
    }
    public function delete($item)
    {
        try {
            $item->delete();
            $message ="Manufacturer Deleted";
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return $message;
        }
    }
}