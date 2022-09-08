<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\AssemblyGroupNode;
use App\Models\LinkageTarget;
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
    public function getEngineDetails($id)
    {
        $enginedetails = LinkageTarget::select('linkageTargetId','description','beginYearMonth','endYearMonth','engineTypeKey','engineType','horsePowerFrom','horsePowerTo','kiloWattsFrom','kiloWattsTo','cylinders','capacityCC','capacityLiters')->get();
        return $enginedetails;
    }
    public function getSections($id)
    {
        $sections = AssemblyGroupNode::where('request__linkingTargetId',$id)->get();
        return $sections;
    }
    public function getSectionParts($id)
    {
        $sectionparts = Article::where('assemblyGroupNodeId',$id)->get();
        return $sectionparts;
    }
}
