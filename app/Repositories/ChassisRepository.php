<?php

namespace App\Repositories;

use App\Models\Chassis;
use App\Repositories\Interfaces\ChassisInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChassisRepository implements ChassisInterface
{
    public function import($data)
    {
        try {
            set_time_limit(0);
            ini_set('max_execution_time', 36000);
            Log::debug($data->all());
            if (!isset($data->import_file)) {
                toastr()->error('Please upload a CSV File');
                return redirect()->back();
            } else {
                $path = $data->file('import_file')->getRealPath();
                $mim_type = $data->file('import_file')->getMimeType();
                if ($mim_type != "text/csv") {
                    toastr()->error('Please Upload CSV File Only');
                    return redirect()->back();
                } else {
                    $records = array_map('str_getcsv', file($path));
                    $fields = $records[0];
                    array_shift($fields);
                    if (!count($records) > 0) {
                        toastr()->error('you have no record in csv!');
                        return redirect()->back();
                    } elseif (count($records[0]) == 25) {
                        $field_check_25 = ['CHASSIS', 'DAT_V', 'DMC', 'PUISSANCE', 'ENERGIE', 'CAR', 'PTAC', 'PTRA', 'PVID', 'PLA_AS', 'CU', 'CYL', 'CD_TYP_CONS', 'DMC', 'DAT_IMMAT', 'GENRE', 'USAGE', 'TYP_COMM', 'VILLE', 'MARQUE', 'PREMIERE_OPERATION', 'GAUCHE', 'CD_SERIE', 'DROIT_MIL'];
                        foreach ($fields as $key => $record) {
                            if ($record != $field_check_25[$key]) {
                                toastr()->error('Your Column headers are not matched with the sample of given Csv!');
                                return redirect()->back();
                            }
                        }
                        try {
                            DB::beginTransaction();
                            array_shift($records);
                            foreach ($records as $key => $record) {
                                array_shift($record);
                                $exicting_chassis = Chassis::where('CHASSIS', $record[0])->first();
                                if (!$exicting_chassis) {
                                    $chassis = Chassis::create([
                                        'CHASSIS' => $record[0],
                                        'DAT_V' => $record[1],
                                        'DMC' => $record[2],
                                        'PUISSANCE' => $record[3],
                                        'ENERGIE' => $record[4],
                                        'CAR' => $record[5],
                                        'PTAC' => $record[6],
                                        'PTRA' => $record[7],
                                        'PVID' => $record[8],
                                        'PLA_AS' => $record[9],
                                        'CU' => $record[10],
                                        'CYL' => $record[11],
                                        'CD_TYP_CONS' => $record[12],
                                        'DAT_IMMAT' => $record[14],
                                        'GENRE' => $record[15],
                                        'USAGE' => $record[16],
                                        'TYP_COMM' => $record[17],
                                        'VILLE' => $record[18],
                                        'MARQUE' => $record[19],
                                        'PREMIERE_OPERATION' => $record[20],
                                        'GAUCHE' => $record[21],
                                        'CD_SERIE' => $record[22],
                                        'DROIT_MIL' => $record[23],
                                    ]);
                                } else {
                                    toastr()->error('Chassis Number ' . $record[0] . ' already exist');
                                }
                            }
                            DB::commit();
                            toastr()->success('Data Imported Successfully');
                            return redirect()->back();
                        } catch (\Exception $e) {
                            Db::rollBack();
                            return toastr()->error($e->getMessage());
                        }
                    } elseif (count($records[0]) == 23) {
                        // dd($fields);
                        $field_check_23 = ['CHASSIS', 'DAT_V', 'DMC', 'PUISSANCE', 'ENERGIE', 'CAR', 'PTAC', 'PTRA', 'PVID', 'PLA_AS', 'CU', 'CYL', 'CD_TYP_CONS', 'DMC', 'DAT_IMMAT', 'GENRE', 'TYP_COMM', 'MARQUE', 'PREMIERE_OPERATION', 'GAUCHE', 'CD_SERIE', 'DROIT_MIL'];
                        foreach ($fields as $key => $record) {
                            if ($record != $field_check_23[$key]) {
                                toastr()->error('Your Column headers are not matched with the sample of given Csv!' . $record);
                                return redirect()->back();
                            }
                        }
                        try {
                            DB::beginTransaction();
                            array_shift($records);
                            foreach ($records as $key => $record) {
                                array_shift($record);
                                $exicting_chassis = Chassis::where('CHASSIS', $record[0])->first();
                                if (!$exicting_chassis) {
                                    $chassis = Chassis::create([
                                        'CHASSIS' => $record[0],
                                        'DAT_V' => $record[1],
                                        'DMC' => $record[2],
                                        'PUISSANCE' => $record[3],
                                        'ENERGIE' => $record[4],
                                        'CAR' => $record[5],
                                        'PTAC' => $record[6],
                                        'PTRA' => $record[7],
                                        'PVID' => $record[8],
                                        'PLA_AS' => $record[9],
                                        'CU' => $record[10],
                                        'CYL' => $record[11],
                                        'CD_TYP_CONS' => $record[12],
                                        'DAT_IMMAT' => $record[14],
                                        'GENRE' => $record[15],
                                        'USAGE' => null,
                                        'TYP_COMM' => $record[16],
                                        'VILLE' => null,
                                        'MARQUE' => $record[17],
                                        'PREMIERE_OPERATION' => $record[18],
                                        'GAUCHE' => $record[19],
                                        'CD_SERIE' => $record[20],
                                        'DROIT_MIL' => $record[21]
                                    ]);
                                } else {
                                    toastr()->error('Chassis Number ' . $record[0] . ' already exist');
                                }
                            }
                            DB::commit();
                            toastr()->success('Data Imported Successfully');
                            return redirect()->back();
                        } catch (\Exception $e) {
                            Db::rollBack();
                            return toastr()->error($e->getMessage());
                        }
                    } else {
                        toastr()->error('you are not uploading the csv with Proper Columns please check the given sample CSV file!');
                        return redirect()->back();
                    }
                }
            }
        } catch (\Exception $e) {
            return toastr()->error($e->getMessage());
        }
    }
}
