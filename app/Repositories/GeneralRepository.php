<?php

namespace App\Repositories;

use App\Repositories\Interfaces\GeneralInterface;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use App\Models\ModelSeriesOld;


class GeneralRepository implements GeneralInterface
{
    public function getManufacturer()
    {
        $manufacturers = Manufacturer::all();
        return $manufacturers;
    }
    public function getModel($id)
    {
        $models = ModelSeries::where('manuId',$id)->get()->toArray();
        $oldmodels = ModelSeriesOld::where('manuId',$id)->get()->toArray();
        $models = array_merge($models, $oldmodels);
        return $models;
    }
}
