<?php

namespace App\Http\Requests;

use Flasher\Laravel\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreBalanceSheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // dd($_REQUEST);
        if($_REQUEST['mode_payment'] == "cash")
        {
            return [
                'amount' => 'required',
                'settlement_date' => 'required'
            ];
        }
        elseif($_REQUEST['mode_payment'] == "cheque" && $_REQUEST['transaction_type'] == "debit")
        {
            return [
                'amount' => 'required',
                'settlement_date' => 'required',
                'account_source' => 'required',
                'cheque_number' => 'required|max:195|unique:balance_sheets,cheque_number',
                'due_date' => 'required',
            ];
        }
        elseif($_REQUEST['mode_payment'] == "cheque" && $_REQUEST['transaction_type'] == "credit")
        {
            return [
                'amount' => 'required',
                'settlement_date' => 'required',
                'cheque_number' => 'required|max:195|unique:balance_sheets,cheque_number',
                'due_date' => 'required',
                'carrier' => 'required',
                'bank_id' => 'required',
            ];

        }elseif($_REQUEST['mode_payment'] == "draft" && $_REQUEST['transaction_type'] == "debit")
        {
            return [
                'amount' => 'required',
                'settlement_date' => 'required',
                'account_source' => 'required',
                'draft_number' => 'required|max:195|unique:balance_sheets,draft_number',
                'due_date' => 'required',
            ];
        }
        elseif($_REQUEST['mode_payment'] == "draft" && $_REQUEST['transaction_type'] == "credit")
        {
            return [
                'amount' => 'required',
                'settlement_date' => 'required',
                'draft_number' => 'required|max:195|unique:balance_sheets,draft_number',
                'due_date' => 'required',
                'carrier' => 'required',
                'bank_id' => 'required',
            ];

        }
    }
}
