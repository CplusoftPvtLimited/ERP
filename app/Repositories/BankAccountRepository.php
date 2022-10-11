<?php

namespace App\Repositories;

use App\Models\BankAccount;
use App\Repositories\Interfaces\BankAccountInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankAccountRepository implements BankAccountInterface
{
    public function store($data)
    {
        $input = $data->except('_token');
        // dd($input);
        DB::beginTransaction();
        try {
            $item = BankAccount::create($input);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return $e;
        }
       
    }
    public function update($data, $bankAccount)
    {
        // dd($data->all());
        $input = $data->except('_token','method');
        DB::beginTransaction();
        try {
            $item = $bankAccount->update($input);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $e;
        }
    }
}
