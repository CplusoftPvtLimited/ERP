<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankList;
use App\Repositories\Interfaces\BankAccountInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $val = 0;
    private $bankAccount;

    public function __construct(BankAccountInterface $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $accounts = BankAccount::where('retailer_id', Auth::id())->with('bankList')->get();
            return DataTables::of($accounts)
                ->addIndexColumn()
                ->addcolumn('bank',function($row){
                    $bank = $row->bankList->title;
                    return $bank;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                            <div class="col-md-2 mr-1">
                                <a href="bank_account/' . $row["id"] . '/edit"> <button
                                class="btn btn-primary btn-sm " type="button"
                                data-original-title="btn btn-danger btn-xs"
                                title=""><i class="fa fa-edit"></i></button></a>
                            </div>
                        </div>
                     ';
                    return $btn;
                })
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['action', 'index','bank'])
                ->make(true);
        }
        return view('accounting.bank_account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting.bank_account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBankAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankAccountRequest $request)
    {
        $item = $this->bankAccount->store($request);
        // dd($item);
        if ($item == true) {
            return redirect()->route('bank_account.index')->withSuccess(__('Bank Account has been Added Successfully !'));
        } else {
            return redirect()->back()->with('error', $item);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        // dd($bankAccount);
        $banks = BankList::all();
        return view('accounting.bank_account.edit', compact('bankAccount','banks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBankAccountRequest  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        // dd($bankAccount);
        $validated = $request->validate([
            'account_number' => 'required|max:191|unique:bank_accounts,account_number,' . $bankAccount->id,
            'account_title' => 'required|max:191'
        ]);
        $item = $this->bankAccount->update($request, $bankAccount);
        if ($item == true) {
            return redirect()->route('bank_account.index')->withSuccess(__('Bank Account has been Updated Successfully !'));
        } else {
            return redirect()->back()->with('error', $item);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        //
    }
}
