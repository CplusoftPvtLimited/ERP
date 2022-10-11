<?php

namespace App\Repositories\Interfaces;

interface BankAccountInterface
{
    public function store($data);
    public function update($data, $bankAccount);

}
