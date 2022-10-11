<?php

namespace App\Http\Controllers;

use App\Models\AfterMarkitSupplier;
use App\Models\BalanceCategory;
use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Models\BankList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CashManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $val = 0;
    public function index()
    {
        $primary_revenue = 000;
        $secondary_revenue = 000;
        $auth_id = Auth::id();
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
        return view('accounting.cash_management.index', compact('secondary_revenue', 'primary_revenue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cheque(Request $request)
    {
        // dd($request->all());
        $auth_id = Auth::id();
        $banks = BankList::all();
        $suppliers = AfterMarkitSupplier::where('retailer_id', $auth_id)->get();
        $source_accounts = BankAccount::where('retailer_id', $auth_id)->get();
        $bal_categories = BalanceCategory::where('type', 'debit')->get();
        if ($request->radiodueDate == "settelement_date") {
            // dd($request->all());
            $regulations = BalanceSheet::where('retailer_id', $auth_id)->where('mode_payment', $request->payment_method)->Where('transaction_type', $request->transaction_type)->Where('balance_type', $request->balance_type)->whereBetween('settlement_date', array($request->start_date, $request->end_date))->orderBy('settlement_date', 'desc')->with('balanceCategory')->with('afterMarketSupplier')->with('bankList')->with('bankAccount')->get();
            // dd($regulations);
            if (count($regulations) > 0) {
                return view('accounting.check_drafts.cheque', compact('banks', 'suppliers', 'source_accounts', 'bal_categories', 'regulations'));
            } else {
                return redirect()->route('cash.management.cheque')->with('error', 'No Record Found');
            }
        } elseif ($request->radiodueDate == "due_date") {
            $regulations = BalanceSheet::where('retailer_id', $auth_id)->where('mode_payment', $request->payment_method)->Where('transaction_type', $request->transaction_type)->Where('balance_type', $request->balance_type)->whereBetween('due_date', array($request->start_date, $request->end_date))->orderBy('due_date', 'desc')->with('balanceCategory')->with('afterMarketSupplier')->with('bankList')->with('bankAccount')->get();
            // dd($regulations);
            if (count($regulations) > 0) {
                return view('accounting.check_drafts.cheque', compact('banks', 'suppliers', 'source_accounts', 'bal_categories', 'regulations'));
            } else {
                return redirect()->back()->with('error', 'No Record Found');
            }
        } else {
            $regulations = BalanceSheet::where('retailer_id', $auth_id)->where('mode_payment', 'cheque')->orWhere('mode_payment', 'draft')->orderBy('settlement_date', 'desc')->with('balanceCategory')->with('afterMarketSupplier')->with('bankList')->with('bankAccount')->get();
            // dd($regulations);
            return view('accounting.check_drafts.cheque', compact('banks', 'suppliers', 'source_accounts', 'bal_categories', 'regulations'));
        }
    }
    public function balance(Request $request)
    {
        $operation = $request->operation;
        $auth_id = Auth::id();
        $cash_revenue = 0;
        $cheque_revenue = 0;
        $draft_revenue = 0;
        $withholding_revenue = 0;
        $balance_type = $request->balance_type;
        if ($request->ajax()) {
            $regulation_data = BalanceSheet::where('retailer_id', $auth_id)->where('balance_type', $balance_type)->orderBy('settlement_date', 'desc')->with('balanceCategory')->get();
            return DataTables::of($regulation_data)
                ->addIndexColumn()
                ->addColumn('index', function ($row) {
                    $value = ++$this->val;
                    return $value;
                })
                ->rawColumns(['index'])
                ->make(true);
        }
        $regulations = BalanceSheet::where('retailer_id', $auth_id)->where('balance_type', $balance_type)->orderBy('settlement_date', 'desc')->with('balanceCategory')->get();
        $bal_categories = BalanceCategory::where('type', 'debit')->get();
        $banks = BankList::all();
        $suppliers = AfterMarkitSupplier::where('retailer_id', $auth_id)->get();
        $source_accounts = BankAccount::where('retailer_id', $auth_id)->get();
        foreach ($regulations as $item) {
            if ($item->mode_payment == 'cash') {
                if ($item->transaction_type == 'debit') {
                    $cash_revenue = $cash_revenue - $item->amount;
                } else {
                    $cash_revenue = $cash_revenue + $item->amount;
                }
            } elseif ($item->mode_payment == 'cheque') {
                if ($item->transaction_type == 'debit') {
                    $cheque_revenue = $cheque_revenue - $item->amount;
                } else {
                    $cheque_revenue = $cheque_revenue + $item->amount;
                }
            } elseif ($item->mode_payment == 'draft') {
                if ($item->transaction_type == 'debit') {
                    $draft_revenue = $draft_revenue - $item->amount;
                } else {
                    $draft_revenue = $draft_revenue + $item->amount;
                }
            } else {
                if ($item->transaction_type == 'debit') {
                    $withholding_revenue = $withholding_revenue - $item->amount;
                } else {
                    $withholding_revenue = $withholding_revenue + $item->amount;
                }
            }
        }
        return view('accounting.cash_management.balance', compact('bal_categories', 'balance_type', 'regulations', 'banks', 'cash_revenue', 'cheque_revenue', 'draft_revenue', 'withholding_revenue', 'suppliers', 'operation', 'source_accounts'));
    }
    public function regulation($id)
    {
        $regulation = BalanceSheet::where('id', $id)->with('balanceCategory')->with('afterMarketSupplier')->with('bankList')->with('bankAccount')->first();
        // dd($regulation);
        if ($regulation) {
            return view('accounting.cash_management.regulation_view', compact('regulation'));
        }else{
            return redirect(url()->previous())->with('error', 'No Record Found');
        }
    }
}
