<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\GeneralController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('get_manufacturers',[GeneralController::class,'getManufacturer']);
Route::post('get_models',[GeneralController::class,'getModel']);
Route::post('get_vehicle_details',[GeneralController::class,'getVehical']);
Route::post('get_enigine_details',[GeneralController::class,'getEngineDetails']);
Route::post('get_sections',[GeneralController::class,'getSections']);
Route::post('get_section_parts',[GeneralController::class,'getSectionParts']);


