<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkageTarget extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = "linkagetargets";

    protected $guarded = [];
    protected $fillable = [
        'capacityCC', 'capacityLiters', 'code', 'kiloWattsFrom', 'kiloWattsTo', 'horsePowerTo', 'horsePowerFrom', 'engineType', 'mfrId', 'vehicleModelSeriesId', 'linkageTargetType', 'subLinkageTargetType', 'description', 'mfrShortName', 'beginYearMonth', 'endYearMonth', 'rmiTypeId', 'imageURL50', 'imageURL100', 'imageURL200', 'imageURL400', 'imageURL800', 'fuelMixtureFormationTypeKey', 'fuelMixtureFormationType', 'driveTypeKey', 'driveType', 'bodyStyleKey', 'bodyStyle', 'fuelTypeKey', 'fuelType', 'engineTypeKey', 'cylinders', 'axleStyleKey', 'axleStyle', 'axleTypeKey', 'axleType', 'axleBodyKey', 'axleBody', 'wheelMountingKey', 'wheelMounting', 'axleLoadToKg', 'brakeTypeKey', 'brakeType', 'hmdMfrModelId', 'hmdMfrModelName', 'aspirationKey', 'aspiration', 'cylinderDesignKey', 'cylinderDesign', 'coolingTypeKey', 'coolingType', 'tonnage', 'axleConfigurationKey', 'axleConfiguration', 'axleLoadFromKg', 'lang', 'valves', 'linkageTargetId', 'mfrName', 'vehicleModelSeriesName'
    ];

}
