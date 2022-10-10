<?php

namespace App\Repositories;

use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Repositories\Interfaces\BalanceSheetInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceSheetRepository implements BalanceSheetInterface
{
    public function store($data)
    {
        // dd($data->all());
        DB::beginTransaction();
        if ($data->has('ajax')) {
            $input = $data->except('_token', 'ajax');
        } else {
            $input = $data->except('_token');
        }
        $auth_id = Auth::id();
        $input['retailer_id'] = $auth_id;
        $primary_revenue = 000;
        $secondary_revenue = 000;
        $revenue = [];
        try {
            if ($input['transaction_type'] == 'debit' && $input['mode_payment'] == 'cash') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $input['category_id'],
                    'amount' => $input['amount'],
                    'supplier_id' => $data->has('supplier_id') ? $input['supplier_id'] : null,
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'retailer_id' => $input['retailer_id']
                ]);
            } elseif ($input['transaction_type'] == 'debit' && $input['mode_payment'] == 'cheque') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $data->has('category_id') ? $input['category_id'] : null,
                    'amount' => $input['amount'],
                    'supplier_id' => $input['supplier_id'],
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'account_source' => $input['account_source'],
                    'cheque_number' => $input['cheque_number'],
                    'due_date' => $input['due_date'],
                    'retailer_id' => $input['retailer_id']
                ]);
            } elseif ($input['transaction_type'] == 'debit' && $input['mode_payment'] == 'draft') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $data->has('category_id') ? $input['category_id'] : null,
                    'amount' => $input['amount'],
                    'supplier_id' => $input['supplier_id'],
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'account_source' => $input['account_source'],
                    'draft_number' => $input['draft_number'],
                    'due_date' => $input['due_date'],
                    'retailer_id' => $input['retailer_id']
                ]);
            } elseif ($input['transaction_type'] == 'credit' && $input['mode_payment'] == 'cash') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $input['category_id'],
                    'amount' => $input['amount'],
                    'supplier_id' => $data->has('supplier_id') ? $input['supplier_id'] : null,
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'retailer_id' => $input['retailer_id']
                ]);
            } elseif ($input['transaction_type'] == 'credit' && $input['mode_payment'] == 'cheque') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $data->has('category_id') ? $input['category_id'] : null,
                    'amount' => $input['amount'],
                    'customer_id' => $data->has('customer_id') ? $input['customer_id'] : null,
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'carrier' => $input['carrier'],
                    'bank_id' => $input['bank_id'],
                    'cheque_number' => $input['cheque_number'],
                    'due_date' => $input['due_date'],
                    'retailer_id' => $input['retailer_id']
                ]);
            } elseif ($input['transaction_type'] == 'credit' && $input['mode_payment'] == 'draft') {
                $item = BalanceSheet::create([
                    'mode_payment' => $input['mode_payment'],
                    'category_id' => $data->has('category_id') ? $input['category_id'] : null,
                    'amount' => $input['amount'],
                    'customer_id' => $data->has('customer_id') ? $input['customer_id'] : null,
                    'settlement_date' => $input['settlement_date'],
                    'note' => $input['note'],
                    'transaction_type' => $input['transaction_type'],
                    'balance_type' => $input['balance_type'],
                    'carrier' => $input['carrier'],
                    'bank_id' => $input['bank_id'],
                    'draft_number' => $input['draft_number'],
                    'due_date' => $input['due_date'],
                    'retailer_id' => $input['retailer_id']
                ]);
            }
            // $item = BalanceSheet::create($input);
            $regulations = BalanceSheet::where('retailer_id', $auth_id)->get();
            foreach ($regulations as $item) {
                if ($item->balance_type == "primary") {
                    if ($item->transaction_type == "debit") {
                        $primary_revenue = $primary_revenue - $item->amount;
                    } else {
                        $primary_revenue = $primary_revenue + $item->amount;
                    }
                } else {
                    if ($item->transaction_type == "debit") {
                        $secondary_revenue = $secondary_revenue - $item->amount;
                    } else {
                        $secondary_revenue = $secondary_revenue + $item->amount;
                    }
                }
            }
            $revenue['primary_balance'] = $primary_revenue;
            $revenue['secondary_balance'] = $secondary_revenue;
            $bank_account = BankAccount::where('retailer_id', $auth_id)->first();
            $bank_account->update($revenue);
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $e;
        }
    }
}
