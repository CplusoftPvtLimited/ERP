<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Customer;
use App\CustomerGroup;
use App\Warehouse;
use App\Biller;
use App\Brand;
use App\Category;
use App\Product;
use App\Unit;
use App\Tax;
use App\Sale;
use App\Delivery;
use App\PosSetting;
use App\Product_Sale;
use App\Product_Warehouse;
use App\Payment;
use App\Account;
use App\Coupon;
use App\GiftCard;
use App\PaymentWithCheque;
use App\PaymentWithGiftCard;
use App\PaymentWithCreditCard;
use App\PaymentWithPaypal;
use App\User;
use App\Variant;
use App\ProductVariant;
use App\CashRegister;
use App\Returns;
use App\Expense;
use App\ProductPurchase;
use App\ProductBatch;
use App\Purchase;
use App\RewardPointSetting;
use App\SalesInvoice;
use App\InvoiceProduct;

use PDF;

use DB;
use App\GeneralSetting;
use Stripe\Stripe;
use NumberToWords\NumberToWords;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use App\Models\AfterMarkitSupplier;
use App\Models\Article;
use App\Models\ERPInvoice;
use App\Models\ERPInvoiceProduct;
use App\Models\Manufacturer;
use App\Models\NewSale;
use App\Models\NewSaleProduct;
use App\Models\StockManagement;
use App\Repositories\Interfaces\SaleInterface;
use App\Supplier;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;
use GeniusTS\HijriDate\Date;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Invoice;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    private $saleRepository;

    public function __construct(SaleInterface $saleInterface)
    {
        $this->saleRepository = $saleInterface;
        // $this->auth_user = auth()->guard('api')->user();
    }
    public function show()
    {
    }

    // public function index(Request $request)
    // {
    //     // dd($request->all());
    //     $sales = Sale::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
    //     // dd($sales);
    //     $role = Role::find(Auth::user()->role_id);
    //     if ($role->hasPermissionTo('sales-index')) {
    //         $permissions = Role::findByName($role->name)->permissions;
    //         foreach ($permissions as $permission)
    //             $all_permission[] = $permission->name;
    //         if (empty($all_permission))
    //             $all_permission[] = 'dummy text';

    //         if ($request->input('warehouse_id'))
    //             $warehouse_id = $request->input('warehouse_id');
    //         else
    //             $warehouse_id = 0;

    //         if ($request->input('starting_date')) {
    //             $starting_date = $request->input('starting_date');
    //             $ending_date = $request->input('ending_date');
    //         } else {
    //             $starting_date = date("Y-m-d", strtotime(date('Y-m-d', strtotime('-1 year', strtotime(date('Y-m-d'))))));
    //             $ending_date = date("Y-m-d");
    //         }

    //         $lims_gift_card_list = GiftCard::where("is_active", true)->get();
    //         $lims_pos_setting_data = PosSetting::latest()->first();
    //         $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
    //         $lims_warehouse_list = Warehouse::where('is_active', true)->get();
    //         $lims_account_list = Account::where('is_active', true)->get();

    //         return view('sale.index', compact('starting_date', 'ending_date', 'warehouse_id', 'lims_gift_card_list', 'lims_pos_setting_data', 'lims_reward_point_setting_data', 'lims_account_list', 'lims_warehouse_list', 'all_permission', 'sales'));
    //     } else
    //         return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(NewSale::where('retailer_id', Auth::user()->id)->orderBy('id', 'DESC'))
                ->addIndexColumn('id')
                // ->addColumn('supplier', function ($row) {
                //     return isset($row->afterMarkitSupplier->name) ? $row->afterMarkitSupplier->name : null;
                // })
                // ->addColumn('due_amount', function ($row) {
                //     $due_amount = $row->grand_total - $row->paid_amount;
                //     return $due_amount;
                // })
                // ->addColumn('purchase_status', function ($row) {
                //     $check_odd_one = [];
                //     foreach ($row->productPurchases as $product) {
                //         if ($product->status == "ordered") {
                //             array_push($check_odd_one, $product->status);
                //         }
                //     }
                //     $purchase_status = "";
                //     if (count($check_odd_one) > 0) {
                //         $purchase_status = "Pending";
                //     } else {
                //         $purchase_status = "Completed";
                //     }
                //     return $purchase_status;
                // })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                     
                     <div class="col-sm-3">
                     <a href="/sales/' . $row["id"] . '/edit"> <button
                                 class="btn btn-primary btn-sm " type="button"
                                 data-original-title="btn btn-danger btn-xs"
                                 title=""><i class="fa fa-edit"></i></button></a>
                     </div>

                     <div class="col-sm-3">
                     <a href="/view_sale/' . $row["id"] . '"> <button
                                 class="btn btn-info btn-sm " type="button"
                                 data-original-title="btn btn-success btn-xs"
                                 title=""><i class="fa fa-eye"></i></button></a>
                     </div>';
                    if ($row['status'] == "accepted") {
                        $btn .= '<div class="col-sm-3">
                        <a href="/createInvoice/' . $row["id"] . '"> <button
                                    class="btn btn-success btn-sm " style="background: grey;
                                    border: 1px solid grey;" type="button"
                                    data-original-title="btn btn-success btn-xs"
                                    title=""><i class="fa fa-file"></i></button></a>
                        </div>';
                    }
                    $btn .= '</div>
                 ';

                    return $btn;
                })->addColumn('sale_status', function ($row) {
                    $status = "";
                    if ($row['status'] == "accepted") {
                        $status = '<select name="sale_status" onchange="changeSaleStatus(' . $row["id"] . ')" id="sale_status" class="form-control">
                        <option class="negotiation" value="#" selected disabled>Accepted</option>
                        <option class="cancel" value="cancelled">Cancel</option>
                        </select>';
                    } else if ($row['status'] == "negotiation") {
                        $status = '<select name="sale_status" onchange="changeSaleStatus(' . $row["id"] . ')" id="sale_status" class="form-control">
                        <option class="negotiation" value="#" selected disabled>Negotiation</option>
                        <option class="accept" value="accepted">Accept</option>
                        <option class="cancel" value="cancelled">Cancel</option>
                        </select>';
                    } else if ($row['status'] == "cancelled") {
                        $status = '<select name="sale_status" onchange="changeSaleStatus(' . $row["id"] . ')" id="sale_status" class="form-control">
                        <option class="negotiation" value="#" selected disabled>Cancelled</option>
                        <option class="accept" value="reactivated">Reactivate</option>
                       
                        </select>';
                    } else if ($row['status'] == "created") {
                        $status = '<select name="sale_status" onchange="changeSaleStatus(' . $row["id"] . ')" id="sale_status" class="form-control">
                        <option class="negotiation" value="#" selected disabled>Created</option>
                        <option class="negotiation" value="negotiation">Negotiation</option>
                        <option class="accept" value="accepted" >Accept</option>
                        <option class="cancel" value="cancelled">Cancel</option>
                        </select>';
                    } else if ($row['status'] == "reactivated") {
                        $status = '<select name="sale_status" onchange="changeSaleStatus(' . $row["id"] . ')" id="sale_status"  class="form-control">
                        <option class="negotiation" value="#" selected disabled>Reactivated</option>
                        <option class="accept" value="created">Created</option>
                        </select>
                        
                        ';
                    }

                    return $status;
                })
                ->rawColumns(['action', 'sale_status'])->make(true);
        }
        return view('sale.index');
        // return view('sale.sale_index');
    }
    public function saleData(Request $request)
    {
        $columns = array(
            1 => 'created_at',
            2 => 'reference_no',
            7 => 'grand_total',
            8 => 'paid_amount',
        );

        $warehouse_id = $request->input('warehouse_id');

        if (Auth::user()->role_id > 2 && config('staff_access') == 'own')
            $totalData = Sale::where('user_id', Auth::id())
                ->whereDate('created_at', '>=', $request->input('starting_date'))
                ->whereDate('created_at', '<=', $request->input('ending_date'))
                ->count();
        elseif ($warehouse_id != 0)
            $totalData = Sale::where('warehouse_id', $warehouse_id)->whereDate('created_at', '>=', $request->input('starting_date'))->whereDate('created_at', '<=', $request->input('ending_date'))->count();
        else
            $totalData = Sale::whereDate('created_at', '>=', $request->input('starting_date'))->whereDate('created_at', '<=', $request->input('ending_date'))->count();
        $totalFiltered = $totalData;
        if ($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        $order = 'sales.' . $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if (empty($request->input('search.value'))) {
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own')
                $sales = Sale::with('biller', 'customer', 'warehouse', 'user')
                    ->where('user_id', Auth::id())
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            elseif ($warehouse_id != 0)
                $sales = Sale::with('biller', 'customer', 'warehouse', 'user')
                    ->where('warehouse_id', $warehouse_id)
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            else
                $sales = Sale::with('biller', 'customer', 'warehouse', 'user')
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
        } else {
            $search = $request->input('search.value');
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $sales =  Sale::select('sales.*')
                    ->with('biller', 'customer', 'warehouse', 'user')
                    ->join('customers', 'sales.customer_id', '=', 'customers.id')
                    ->join('billers', 'sales.biller_id', '=', 'billers.id')
                    ->whereDate('sales.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('sales.user_id', Auth::id())
                    ->orwhere([
                        ['sales.reference_no', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['customers.name', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['customers.phone_number', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['billers.name', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)->get();

                $totalFiltered = Sale::join('customers', 'sales.customer_id', '=', 'customers.id')
                    ->join('billers', 'sales.biller_id', '=', 'billers.id')
                    ->whereDate('sales.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('sales.user_id', Auth::id())
                    ->orwhere([
                        ['sales.reference_no', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['customers.name', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['customers.phone_number', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['billers.name', 'LIKE', "%{$search}%"],
                        ['sales.user_id', Auth::id()]
                    ])
                    ->count();
            } else {
                $sales =  Sale::select('sales.*')
                    ->with('biller', 'customer', 'warehouse', 'user')
                    ->join('customers', 'sales.customer_id', '=', 'customers.id')
                    ->join('billers', 'sales.biller_id', '=', 'billers.id')
                    ->whereDate('sales.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('sales.user_id', Auth::id())
                    ->orwhere('sales.reference_no', 'LIKE', "%{$search}%")
                    ->orwhere('customers.name', 'LIKE', "%{$search}%")
                    ->orwhere('customers.phone_number', 'LIKE', "%{$search}%")
                    ->orwhere('billers.name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)->get();

                $totalFiltered = Sale::join('customers', 'sales.customer_id', '=', 'customers.id')
                    ->join('billers', 'sales.biller_id', '=', 'billers.id')
                    ->whereDate('sales.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('sales.user_id', Auth::id())
                    ->orwhere('sales.reference_no', 'LIKE', "%{$search}%")
                    ->orwhere('customers.name', 'LIKE', "%{$search}%")
                    ->orwhere('customers.phone_number', 'LIKE', "%{$search}%")
                    ->orwhere('billers.name', 'LIKE', "%{$search}%")
                    ->count();
            }
        }
        $data = array();
        if (!empty($sales)) {
            foreach ($sales as $key => $sale) {
                $nestedData['id'] = $sale->id;
                $nestedData['key'] = $key;
                $nestedData['date'] = date(config('date_format'), strtotime($sale->created_at->toDateString()));
                $nestedData['reference_no'] = $sale->reference_no;
                $nestedData['biller'] = $sale->biller->name;
                $nestedData['customer'] = $sale->customer->name . '<input type="hidden" class="deposit" value="' . ($sale->customer->deposit - $sale->customer->expense) . '" />' . '<input type="hidden" class="points" value="' . $sale->customer->points . '" />';

                if ($sale->sale_status == 1) {
                    $nestedData['sale_status'] = '<div class="badge badge-success">' . trans('file.Completed') . '</div>';
                    $sale_status = trans('file.Completed');
                } elseif ($sale->sale_status == 2) {
                    $nestedData['sale_status'] = '<div class="badge badge-danger">' . trans('file.Pending') . '</div>';
                    $sale_status = trans('file.Pending');
                } else {
                    $nestedData['sale_status'] = '<div class="badge badge-warning">' . trans('file.Draft') . '</div>';
                    $sale_status = trans('file.Draft');
                }

                if ($sale->payment_status == 1)
                    $nestedData['payment_status'] = '<div class="badge badge-danger">' . trans('file.Pending') . '</div>';
                elseif ($sale->payment_status == 2)
                    $nestedData['payment_status'] = '<div class="badge badge-danger">' . trans('file.Due') . '</div>';
                elseif ($sale->payment_status == 3)
                    $nestedData['payment_status'] = '<div class="badge badge-warning">' . trans('file.Partial') . '</div>';
                else
                    $nestedData['payment_status'] = '<div class="badge badge-success">' . trans('file.Paid') . '</div>';

                $nestedData['grand_total'] = number_format($sale->grand_total, 2);
                $nestedData['paid_amount'] = number_format($sale->paid_amount, 2);
                $nestedData['due'] = number_format($sale->grand_total - $sale->paid_amount, 2);
                $nestedData['options'] = '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . trans("file.action") . '
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li><a href="' . route('sale.invoice', $sale->id) . '" class="btn btn-link"><i class="fa fa-copy"></i> ' . trans('file.Generate Invoice') . '</a></li>
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> ' . trans('file.View') . '</button>
                                </li>';
                if (in_array("sales-edit", $request['all_permission'])) {
                    if ($sale->sale_status != 3)
                        $nestedData['options'] .= '<li>
                            <a href="' . route('sales.edit', $sale->id) . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans('file.edit') . '</a>
                            </li>';
                    else
                        $nestedData['options'] .= '<li>
                            <a href="' . url('sales/' . $sale->id . '/create') . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans('file.edit') . '</a>
                        </li>';
                }
                $nestedData['options'] .=
                    '<li>
                        <button type="button" class="add-payment btn btn-link" data-id = "' . $sale->id . '" data-toggle="modal" data-target="#add-payment"><i class="fa fa-plus"></i> ' . trans('file.Add Payment') . '</button>
                    </li>
                    <li>
                        <button type="button" class="get-payment btn btn-link" data-id = "' . $sale->id . '"><i class="fa fa-money"></i> ' . trans('file.View Payment') . '</button>
                    </li>
                    <li>
                        <button type="button" class="add-delivery btn btn-link" data-id = "' . $sale->id . '"><i class="fa fa-truck"></i> ' . trans('file.Add Delivery') . '</button>
                    </li>';
                if (in_array("sales-delete", $request['all_permission']))
                    $nestedData['options'] .= \Form::open(["route" => ["sales.destroy", $sale->id], "method" => "DELETE"]) . '
                            <li>
                              <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> ' . trans("file.delete") . '</button> 
                            </li>' . \Form::close() . '
                        </ul>
                    </div>';
                // data for sale details by one click
                $coupon = Coupon::find($sale->coupon_id);
                if ($coupon)
                    $coupon_code = $coupon->code;
                else
                    $coupon_code = null;

                $nestedData['sale'] = array(
                    '[ "' . date(config('date_format'), strtotime($sale->created_at->toDateString())) . '"', ' "' . $sale->reference_no . '"', ' "' . $sale_status . '"', ' "' . $sale->biller->name . '"', ' "' . $sale->biller->company_name . '"', ' "' . $sale->biller->email . '"', ' "' . $sale->biller->phone_number . '"', ' "' . $sale->biller->address . '"', ' "' . $sale->biller->city . '"', ' "' . $sale->customer->name . '"', ' "' . $sale->customer->phone_number . '"', ' "' . $sale->customer->address . '"', ' "' . $sale->customer->city . '"', ' "' . $sale->id . '"', ' "' . $sale->total_tax . '"', ' "' . $sale->total_discount . '"', ' "' . $sale->total_price . '"', ' "' . $sale->order_tax . '"', ' "' . $sale->order_tax_rate . '"', ' "' . $sale->order_discount . '"', ' "' . $sale->shipping_cost . '"', ' "' . $sale->grand_total . '"', ' "' . $sale->paid_amount . '"', ' "' . preg_replace('/[\n\r]/', "<br>", $sale->sale_note) . '"', ' "' . preg_replace('/[\n\r]/', "<br>", $sale->staff_note) . '"', ' "' . $sale->user->name . '"', ' "' . $sale->user->email . '"', ' "' . $sale->warehouse->name . '"', ' "' . $coupon_code . '"', ' "' . $sale->coupon_discount . '"]'
                );
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function create()
    {
        $role = Role::find(FacadesAuth::user()->role_id);
        if ($role->hasPermissionTo('purchases-add')) {
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_tax_list = Tax::where('type', 1)->first();
            $lims_product_list_without_variant = $this->productWithoutVariant();
            $lims_product_list_with_variant = $this->productWithVariant();
            $manufacturers = Manufacturer::all();
            // dd($manufacturers);
            $suppliers = AfterMarkitSupplier::select('id', 'name')->where('retailer_id', auth()->user()->id)->get();
            return view('sale.create', compact('lims_supplier_list', 'lims_tax_list', 'lims_warehouse_list', 'lims_product_list_without_variant', 'lims_product_list_with_variant', 'manufacturers', 'suppliers'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function productWithoutVariant()
    {
        return Product::ActiveStandard()->select('id', 'name', 'code')
            ->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return Product::join('product_variants', 'products.id', 'product_variants.product_id')
            ->ActiveStandard()
            ->whereNotNull('is_variant')
            ->select('products.id', 'products.name', 'product_variants.item_code')
            ->orderBy('position')->get();
    }



    // public function create()
    // {
    //     $role = Role::find(Auth::user()->role_id);
    //     if($role->hasPermissionTo('sales-add')) {
    //         $lims_customer_list = Customer::where('is_active', true)->get();
    //         if(Auth::user()->role_id > 2 && Auth::user()->role_id != 10 && Auth::user()->role_id != 11) {
    //             // dd('ithy');
    //             $lims_warehouse_list = Warehouse::where([
    //                 ['is_active', true],
    //                 ['id', Auth::user()->warehouse_id]
    //             ])->get();
    //             $lims_biller_list = Biller::where([
    //                 ['is_active', true],
    //                 ['id', Auth::user()->biller_id]
    //             ])->get();
    //         }
    //         else {
    //             // dd('uthy ni ithy');

    //             $lims_warehouse_list = Warehouse::where('is_active', true)->get();
    //             $lims_biller_list = Biller::where('is_active', true)->get();
    //         }

    //         $lims_tax_list = Tax::where('is_active', true)->get();
    //         $lims_pos_setting_data = PosSetting::latest()->first();
    //         $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
    //         // dump($lims_biller_list);
    //         // dd($lims_warehouse_list);
    //         return view('sale.old_create',compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_pos_setting_data', 'lims_tax_list', 'lims_reward_point_setting_data'));
    //     }
    //     else
    //         return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    // }

    public function store(Request $request)
    {
        Log::debug($request->all());

        $rules = [
            'date' => 'required',
            'customer_id' => 'required|integer',
            'cash_type' => 'required|in:white,black', // white or black values
            'entire_vat' => 'required|min:1',
            'shipping_cost' => 'nullable|min:0',
            'sale_discount' => 'nullable|min:0|max:100',
            'tax_stamp' => 'nullable|min:0|max:100',
            'sale_note' => 'nullable',
            'staff_note' => 'nullable',
            'item_qty' => 'required|array',
            'item_qty.*' => 'required|numeric',
            // 'sale_price' => 'required|array',
            // 'sale_price.*' => 'required|numeric',
            // 'discount' => 'nullable|array',
            // 'discount.*' => 'nullable|numeric',
            // 'sale_total_without_discount' => 'nullable|array',
            // 'sale_total_without_discount.*' => 'nullable|numeric',
            // 'sale_total_with_discount.*' => 'nullable|array',
            // 'sale_total_with_discount.*' => 'nullable|numeric',
            // 'article_number' => 'required|array',
            // 'total_to_be_paid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator->errors());
        // }
        try {
            $data = $request->all();
            FacadesDB::beginTransaction();
            $sale = $this->saleRepository->store($data);
            // dd($sale);
            FacadesDB::commit();
            if ($sale && !isset($sale['message'])) {
                toastr()->success('Sale created successfully');
                return redirect()->route('sales.index')->with('message', 'Sales created successfully');
            }
            if ($sale && isset($sale['message']) && $sale['message'] == "quantity-exceeded") {
                FacadesDB::rollBack();
                toastr()->info('Your Requested Quantity is not available for Reference No. ' . $sale['reference_no']);
                return redirect()->back();
            }
            toastr()->error($sale);
            return redirect()->back()->withErrors($sale);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            FacadesDB::rollBack();
            dd($e->getMessage());
            return $e->getMessage();
        }
    }

    // Our own code

    public function viewSale($id)
    {
        $sale = NewSale::find($id);
        $sale_products = NewSaleProduct::where('sale_id', $id)->get();
        // dd($lims_quotation_data);
        return view('sale.view_sale', compact('sale', 'sale_products'));
    }

    public function getSectionPartsForSale(Request $request)
    {
        $stocks = StockManagement::where('retailer_id', auth()->user()->id)->get();
        $articles = [];

        if (count($stocks) > 0) {
            foreach ($stocks as $stock) {
                $article = Article::select('legacyArticleId', 'dataSupplierId', 'genericArticleDescription', 'articleNumber')->where('legacyArticleId', $stock->product_id)->whereHas('articleVehicleTree', function ($query) use ($request) {
                    $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
                })->first();
                if (!empty($article)) {

                    array_push($articles, $article);
                }
            }

            return response()->json([
                'data' => $articles,
                'message' => 1
            ]);
        } else {
            return response()->json([

                'message' => 0
            ], 200);
        }
    }

    public function checkProductStock(Request $request)
    {
        $id = explode('-', $request->section_part_id);
        $stock = StockManagement::where('product_id', $id[1])
            ->where('retailer_id', auth()->user()->id)->first();
        // dd($stock);
        if ($request->cash_type == "white") {
            if (empty($stock->white_items) || $stock->white_items <= 0) {
                return response()->json([

                    'message' => "no_white_items"
                ]);
            } else {
                return response()->json([

                    'message' => "1"
                ]);
            }
        } else if ($request->cash_type == "black") {

            if (empty($stock->black_items) || $stock->black_items <= 0) {
                return response()->json([

                    'message' => "no_black_items"
                ]);
            } else {
                return response()->json([

                    'message' => "1"
                ]);
            }
        }
    }
    public function showSectionParts(Request $request)
    {
        // dd($request->all());
        $id = explode('-', $request->id);
        $section_part_id = explode('-', $request->section_part_id);

        // dd($id);
        $product = Article::where('dataSupplierId', $id[0])->where('legacyArticleId', $id[1])->first();
        $stock = StockManagement::where('retailer_id', auth()->user()->id)
            ->where('product_id', $id[1])->first();

        // dd($product);
        return response()->json([
            'data' => $product,
            // 'supplier' => $request->supplier_id,
            'linkage_target_type' => $request->engine_type, // engine_type
            'linkage_target_sub_type' => $request->engine_sub_type, //
            'manufacturer_id' => $request->manufacturer_id,
            'model_id' => $request->model_id,
            'engine_id' => $request->engine_id,
            'section_id' => $request->section_id,
            'section_part_id' => $section_part_id[1],
            'status' => $request->status,
            'date' => $request->date,
            'cash_type' => $request->cash_type,
            'stock' => $stock,
            // 'brand_id' => $request->brand_id,
        ]);
    }

    public function changeSaleStatus(Request $request)
    {
        // dd($request->all());
        $sale = NewSale::find($request->id);
        $sale->status = $request->status;
        $sale->save();

        return true;
    }
    // our code end

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $lims_sale_data = Sale::find($data['sale_id']);
        $lims_product_sale_data = Product_Sale::where('sale_id', $data['sale_id'])->get();
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        if ($lims_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['reference_no'] = $lims_sale_data->reference_no;
            $mail_data['sale_status'] = $lims_sale_data->sale_status;
            $mail_data['payment_status'] = $lims_sale_data->payment_status;
            $mail_data['total_qty'] = $lims_sale_data->total_qty;
            $mail_data['total_price'] = $lims_sale_data->total_price;
            $mail_data['order_tax'] = $lims_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $lims_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $lims_sale_data->order_discount;
            $mail_data['shipping_cost'] = $lims_sale_data->shipping_cost;
            $mail_data['grand_total'] = $lims_sale_data->grand_total;
            $mail_data['paid_amount'] = $lims_sale_data->paid_amount;

            foreach ($lims_product_sale_data as $key => $product_sale_data) {
                $lims_product_data = Product::find($product_sale_data->product_id);
                if ($product_sale_data->variant_id) {
                    $variant_data = Variant::select('name')->find($product_sale_data->variant_id);
                    $mail_data['products'][$key] = $lims_product_data->name . ' [' . $variant_data->name . ']';
                } else
                    $mail_data['products'][$key] = $lims_product_data->name;
                if ($lims_product_data->type == 'digital')
                    $mail_data['file'][$key] = url('/public/product/files') . '/' . $lims_product_data->file;
                else
                    $mail_data['file'][$key] = '';
                if ($product_sale_data->sale_unit_id) {
                    $lims_unit_data = Unit::find($product_sale_data->sale_unit_id);
                    $mail_data['unit'][$key] = $lims_unit_data->unit_code;
                } else
                    $mail_data['unit'][$key] = '';

                $mail_data['qty'][$key] = $product_sale_data->qty;
                $mail_data['total'][$key] = $product_sale_data->qty;
            }

            try {
                Mail::send('mail.sale_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Sale Details');
                });
                $message = 'Mail sent successfully';
            } catch (\Exception $e) {
                $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        } else
            $message = 'Customer doesnt have email!';

        return redirect()->back()->with('message', $message);
    }

    public function paypalSuccess(Request $request)
    {
        $lims_sale_data = Sale::latest()->first();
        $lims_payment_data = Payment::latest()->first();
        $lims_product_sale_data = Product_Sale::where('sale_id', $lims_sale_data->id)->get();
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        foreach ($lims_product_sale_data as $key => $product_sale_data) {
            $lims_product_data = Product::find($product_sale_data->product_id);
            $paypal_data['items'][] = [
                'name' => $lims_product_data->name,
                'price' => ($product_sale_data->total / $product_sale_data->qty),
                'qty' => $product_sale_data->qty
            ];
        }
        $paypal_data['items'][] = [
            'name' => 'order tax',
            'price' => $lims_sale_data->order_tax,
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'order discount',
            'price' => $lims_sale_data->order_discount * (-1),
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'shipping cost',
            'price' => $lims_sale_data->shipping_cost,
            'qty' => 1
        ];
        if ($lims_sale_data->grand_total != $lims_sale_data->paid_amount) {
            $paypal_data['items'][] = [
                'name' => 'Due',
                'price' => ($lims_sale_data->grand_total - $lims_sale_data->paid_amount) * (-1),
                'qty' => 1
            ];
        }

        $paypal_data['invoice_id'] = $lims_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalSuccess');
        $paypal_data['cancel_url'] = url('/sale/create');

        $total = 0;
        foreach ($paypal_data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $paypal_data['total'] = $lims_sale_data->paid_amount;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $lims_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        return redirect('sales')->with('message', 'Sales created successfully');
    }

    public function paypalPaymentSuccess(Request $request, $id)
    {
        $lims_payment_data = Payment::find($id);
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        $paypal_data['items'][] = [
            'name' => 'Paid Amount',
            'price' => $lims_payment_data->amount,
            'qty' => 1
        ];
        $paypal_data['invoice_id'] = $lims_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess');
        $paypal_data['cancel_url'] = url('/sale');

        $total = 0;
        foreach ($paypal_data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $paypal_data['total'] = $total;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $lims_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        return redirect('sales')->with('message', 'Payment created successfully');
    }

    public function getProduct()
    {
        $lims_product_warehouse_data = Product::all();
        // dd($lims_product_warehouse_data);
        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode

        // $lims_product_with_batch_warehouse_data = Product::join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
        // ->where([
        //     ['products.is_active', true],
        //     ['product_warehouse.warehouse_id', $id],
        //     ['product_warehouse.qty', '>', 0]
        // ])
        // ->whereNull('product_warehouse.variant_id')
        // ->whereNotNull('product_warehouse.product_batch_id')
        // ->select('product_warehouse.*')
        // ->groupBy('product_warehouse.product_id')
        // ->get();

        //now changing back the strict ON
        // config()->set('database.connections.mysql.strict', true);
        // \DB::reconnect();

        // $lims_product_with_variant_warehouse_data = Product::join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
        // ->where([
        //     ['products.is_active', true],
        //     ['product_warehouse.warehouse_id', $id],
        //     ['product_warehouse.qty', '>', 0]
        // ])->whereNotNull('product_warehouse.variant_id')->select('product_warehouse.*')->get();

        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_price = [];
        $product_data = [];
        //product without variant
        foreach ($lims_product_warehouse_data as $product_warehouse) {
            $product_qty[] = $product_warehouse->qty;
            $product_price[] = $product_warehouse->price;
            // $lims_product_data = Product::find($product_warehouse->product_id);
            $product_code[] =  $product_warehouse->code;
            $product_name[] = htmlspecialchars($product_warehouse->name);
            $product_type[] = $product_warehouse->type;
            $product_id[] = $product_warehouse->id;
            $product_list[] = $product_warehouse->product_list;
            $qty_list[] = $product_warehouse->qty_list;
            $batch_no[] = null;
            $product_batch_id[] = null;
            $expired_date[] = null;
        }
        //product with batches
        // foreach ($lims_product_with_batch_warehouse_data as $product_warehouse) 
        // {
        //     $product_qty[] = $product_warehouse->qty;
        //     $product_price[] = $product_warehouse->price;
        //     $lims_product_data = Product::find($product_warehouse->product_id);
        //     $product_code[] =  $lims_product_data->code;
        //     $product_name[] = htmlspecialchars($lims_product_data->name);
        //     $product_type[] = $lims_product_data->type;
        //     $product_id[] = $lims_product_data->id;
        //     $product_list[] = $lims_product_data->product_list;
        //     $qty_list[] = $lims_product_data->qty_list;
        //     $product_batch_data = ProductBatch::select('id', 'batch_no', 'expired_date')->find($product_warehouse->product_batch_id);
        //     $batch_no[] = $product_batch_data->batch_no;
        //     $product_batch_id[] = $product_batch_data->id;
        //     $expired_date[] = date(config('date_format'), strtotime($product_batch_data->expired_date));
        // }
        // //product with variant
        // foreach ($lims_product_with_variant_warehouse_data as $product_warehouse) 
        // {
        //     $product_qty[] = $product_warehouse->qty;
        //     $lims_product_data = Product::find($product_warehouse->product_id);
        //     $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
        //     $product_code[] =  $lims_product_variant_data->item_code;
        //     $product_name[] = htmlspecialchars($lims_product_data->name);
        //     $product_type[] = $lims_product_data->type;
        //     $product_id[] = $lims_product_data->id;
        //     $product_list[] = $lims_product_data->product_list;
        //     $qty_list[] = $lims_product_data->qty_list;
        //     $batch_no[] = null;
        //     $product_batch_id[] = null;
        //     $expired_date[] = null;
        // }
        //retrieve product with type of digital, combo and service
        $lims_product_data = Product::whereNotIn('type', ['standard'])->where('is_active', true)->get();
        foreach ($lims_product_data as $product) {
            $product_qty[] = $product->qty;
            $product_code[] =  $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
            $product_id[] = $product->id;
            $product_list[] = $product->product_list;
            $qty_list[] = $product->qty_list;
            $batch_no[] = null;
            $product_batch_id[] = null;
            $expired_date[] = null;
        }
        $product_data = [$product_code, $product_name, $product_qty, $product_type, $product_id, $product_list, $qty_list, $product_price, $batch_no, $product_batch_id, $expired_date];
        // dd($product_data);
        return $product_data;
    }

    public function posSale()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-add')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_customer_group_all = CustomerGroup::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_product_list = Product::select('id', 'name', 'code', 'image')->ActiveFeatured()->whereNull('is_variant')->get();
            foreach ($lims_product_list as $key => $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
            }
            $lims_product_list_with_variant = Product::select('id', 'name', 'code', 'image')->ActiveFeatured()->whereNotNull('is_variant')->get();

            foreach ($lims_product_list_with_variant as $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
                $lims_product_variant_data = $product->variant()->orderBy('position')->get();
                $main_name = $product->name;
                $temp_arr = [];
                foreach ($lims_product_variant_data as $key => $variant) {
                    $product->name = $main_name . ' [' . $variant->name . ']';
                    $product->code = $variant->pivot['item_code'];
                    $lims_product_list[] = clone ($product);
                }
            }

            $product_number = count($lims_product_list);
            $lims_pos_setting_data = PosSetting::latest()->first();
            $lims_brand_list = Brand::where('is_active', true)->get();
            $lims_category_list = Category::where('is_active', true)->get();

            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $recent_sale = Sale::where([
                    ['sale_status', 1],
                    ['user_id', Auth::id()]
                ])->orderBy('id', 'desc')->take(10)->get();
                $recent_draft = Sale::where([
                    ['sale_status', 3],
                    ['user_id', Auth::id()]
                ])->orderBy('id', 'desc')->take(10)->get();
            } else {
                $recent_sale = Sale::where('sale_status', 1)->orderBy('id', 'desc')->take(10)->get();
                $recent_draft = Sale::where('sale_status', 3)->orderBy('id', 'desc')->take(10)->get();
            }
            $lims_coupon_list = Coupon::where('is_active', true)->get();
            $flag = 0;

            return view('sale.pos', compact('all_permission', 'lims_customer_list', 'lims_customer_group_all', 'lims_warehouse_list', 'lims_reward_point_setting_data', 'lims_product_list', 'product_number', 'lims_tax_list', 'lims_biller_list', 'lims_pos_setting_data', 'lims_brand_list', 'lims_category_list', 'recent_sale', 'recent_draft', 'lims_coupon_list', 'flag'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function getProductByFilter($category_id, $brand_id)
    {
        $data = [];
        if (($category_id != 0) && ($brand_id != 0)) {
            $lims_product_list = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where([
                    ['products.is_active', true],
                    ['products.category_id', $category_id],
                    ['brand_id', $brand_id]
                ])->orWhere([
                    ['categories.parent_id', $category_id],
                    ['products.is_active', true],
                    ['brand_id', $brand_id]
                ])->select('products.name', 'products.code', 'products.image')->get();
        } elseif (($category_id != 0) && ($brand_id == 0)) {
            $lims_product_list = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where([
                    ['products.is_active', true],
                    ['products.category_id', $category_id],
                ])->orWhere([
                    ['categories.parent_id', $category_id],
                    ['products.is_active', true]
                ])->select('products.id', 'products.name', 'products.code', 'products.image', 'products.is_variant')->get();
        } elseif (($category_id == 0) && ($brand_id != 0)) {
            $lims_product_list = Product::where([
                ['brand_id', $brand_id],
                ['is_active', true]
            ])
                ->select('products.id', 'products.name', 'products.code', 'products.image', 'products.is_variant')
                ->get();
        } else
            $lims_product_list = Product::where('is_active', true)->get();

        $index = 0;
        foreach ($lims_product_list as $product) {
            if ($product->is_variant) {
                $lims_product_data = Product::select('id')->find($product->id);
                $lims_product_variant_data = $lims_product_data->variant()->orderBy('position')->get();
                foreach ($lims_product_variant_data as $key => $variant) {
                    $data['name'][$index] = $product->name . ' [' . $variant->name . ']';
                    $data['code'][$index] = $variant->pivot['item_code'];
                    $images = explode(",", $product->image);
                    $data['image'][$index] = $images[0];
                    $index++;
                }
            } else {
                $data['name'][$index] = $product->name;
                $data['code'][$index] = $product->code;
                $images = explode(",", $product->image);
                $data['image'][$index] = $images[0];
                $index++;
            }
        }
        return $data;
    }

    public function getFeatured()
    {
        $data = [];
        $lims_product_list = Product::where([
            ['is_active', true],
            ['featured', true]
        ])->select('products.id', 'products.name', 'products.code', 'products.image', 'products.is_variant')->get();

        $index = 0;
        foreach ($lims_product_list as $product) {
            if ($product->is_variant) {
                $lims_product_data = Product::select('id')->find($product->id);
                $lims_product_variant_data = $lims_product_data->variant()->orderBy('position')->get();
                foreach ($lims_product_variant_data as $key => $variant) {
                    $data['name'][$index] = $product->name . ' [' . $variant->name . ']';
                    $data['code'][$index] = $variant->pivot['item_code'];
                    $images = explode(",", $product->image);
                    $data['image'][$index] = $images[0];
                    $index++;
                }
            } else {
                $data['name'][$index] = $product->name;
                $data['code'][$index] = $product->code;
                $images = explode(",", $product->image);
                $data['image'][$index] = $images[0];
                $index++;
            }
        }
        return $data;
    }

    public function getCustomerGroup($id)
    {
        $lims_customer_data = Customer::find($id);
        $lims_customer_group_data = CustomerGroup::find($lims_customer_data->customer_group_id);
        return $lims_customer_group_data->percentage;
    }

    public function limsProductSearch(Request $request)
    {

        $todayDate = date('Y-m-d');
        $product_code = explode("(", $request['data']);
        $product_info = explode("?", $request['data']);

        $product_code[0] = rtrim($product_code[0], " ");
        $customer_id = $product_info[1];
        $qty = $product_info[2];
        $product_variant_id = null;
        $all_discount = DB::table('discount_plan_customers')
            ->join('discount_plans', 'discount_plans.id', '=', 'discount_plan_customers.discount_plan_id')
            ->join('discount_plan_discounts', 'discount_plans.id', '=', 'discount_plan_discounts.discount_plan_id')
            ->join('discounts', 'discounts.id', '=', 'discount_plan_discounts.discount_id')
            ->where([
                ['discount_plans.is_active', true],
                ['discounts.is_active', true],
                ['discount_plan_customers.customer_id', $customer_id]
            ])
            ->select('discounts.*')
            ->get();
        $lims_product_data = Product::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if (!$lims_product_data) {
            $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.*', 'product_variants.id as product_variant_id', 'product_variants.item_code', 'product_variants.additional_price')
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    ['products.is_active', true]
                ])->first();
            $product_variant_id = $lims_product_data->product_variant_id;
        }

        $product[] = $lims_product_data->name;
        if ($lims_product_data->is_variant) {
            $product[] = $lims_product_data->item_code;
            $lims_product_data->price += $lims_product_data->additional_price;
        } else
            $product[] = $lims_product_data->code;

        $no_discount = 1;
        foreach ($all_discount as $key => $discount) {
            $product_list = explode(",", $discount->product_list);
            $days = explode(",", $discount->days);

            if (($discount->applicable_for == 'All' || in_array($lims_product_data->id, $product_list)) && ($todayDate >= $discount->valid_from && $todayDate <= $discount->valid_till && in_array(date('D'), $days) && $qty >= $discount->minimum_qty && $qty <= $discount->maximum_qty)) {
                if ($discount->type == 'flat') {
                    $product[] = $lims_product_data->price - $discount->value;
                } elseif ($discount->type == 'percentage') {
                    $product[] = $lims_product_data->price - ($lims_product_data->price * ($discount->value / 100));
                }
                $no_discount = 0;
                break;
            } else {
                continue;
            }
        }

        if ($lims_product_data->promotion && $todayDate <= $lims_product_data->last_date && $no_discount) {
            $product[] = $lims_product_data->promotion_price;
        } elseif ($no_discount)
            $product[] = $lims_product_data->price;

        if ($lims_product_data->tax_id) {
            $lims_tax_data = Tax::find($lims_product_data->tax_id);
            $product[] = $lims_tax_data->rate;
            $product[] = $lims_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $lims_product_data->tax_method;
        if ($lims_product_data->type == 'standard') {
            $units = Unit::where("base_unit", $lims_product_data->unit_id)
                ->orWhere('id', $lims_product_data->unit_id)
                ->get();
            $unit_name = array();
            $unit_operator = array();
            $unit_operation_value = array();
            foreach ($units as $unit) {
                if ($lims_product_data->sale_unit_id == $unit->id) {
                    array_unshift($unit_name, $unit->unit_name);
                    array_unshift($unit_operator, $unit->operator);
                    array_unshift($unit_operation_value, $unit->operation_value);
                } else {
                    $unit_name[]  = $unit->unit_name;
                    $unit_operator[] = $unit->operator;
                    $unit_operation_value[] = $unit->operation_value;
                }
            }
            $product[] = implode(",", $unit_name) . ',';
            $product[] = implode(",", $unit_operator) . ',';
            $product[] = implode(",", $unit_operation_value) . ',';
        } else {
            $product[] = 'n/a' . ',';
            $product[] = 'n/a' . ',';
            $product[] = 'n/a' . ',';
        }
        $product[] = $lims_product_data->id;
        $product[] = $product_variant_id;
        $product[] = $lims_product_data->promotion;
        $product[] = $lims_product_data->is_batch;
        $product[] = $lims_product_data->is_imei;
        // dd($product);
        return $product;
    }

    public function checkDiscount(Request $request)
    {
        $qty = $request->input('qty');
        $customer_id = $request->input('customer_id');
        $lims_product_data = Product::select('id', 'price', 'promotion', 'promotion_price', 'last_date')->find($request->input('product_id'));
        $todayDate = date('Y-m-d');
        $all_discount = DB::table('discount_plan_customers')
            ->join('discount_plans', 'discount_plans.id', '=', 'discount_plan_customers.discount_plan_id')
            ->join('discount_plan_discounts', 'discount_plans.id', '=', 'discount_plan_discounts.discount_plan_id')
            ->join('discounts', 'discounts.id', '=', 'discount_plan_discounts.discount_id')
            ->where([
                ['discount_plans.is_active', true],
                ['discounts.is_active', true],
                ['discount_plan_customers.customer_id', $customer_id]
            ])
            ->select('discounts.*')
            ->get();
        $no_discount = 1;
        foreach ($all_discount as $key => $discount) {
            $product_list = explode(",", $discount->product_list);
            $days = explode(",", $discount->days);

            if (($discount->applicable_for == 'All' || in_array($lims_product_data->id, $product_list)) && ($todayDate >= $discount->valid_from && $todayDate <= $discount->valid_till && in_array(date('D'), $days) && $qty >= $discount->minimum_qty && $qty <= $discount->maximum_qty)) {
                if ($discount->type == 'flat') {
                    $price = $lims_product_data->price - $discount->value;
                } elseif ($discount->type == 'percentage') {
                    $price = $lims_product_data->price - ($lims_product_data->price * ($discount->value / 100));
                }
                $no_discount = 0;
                break;
            } else {
                continue;
            }
        }

        if ($lims_product_data->promotion && $todayDate <= $lims_product_data->last_date && $no_discount) {
            $price = $lims_product_data->promotion_price;
        } elseif ($no_discount)
            $price = $lims_product_data->price;

        $data = [$price, $lims_product_data->promotion];
        return $data;
    }

    public function getGiftCard()
    {
        $gift_card = GiftCard::where("is_active", true)->whereDate('expired_date', '>=', date("Y-m-d"))->get(['id', 'card_no', 'amount', 'expense']);
        return json_encode($gift_card);
    }

    public function productSaleData($id)
    {
        $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        foreach ($lims_product_sale_data as $key => $product_sale_data) {
            $product = Product::find($product_sale_data->product_id);
            if ($product_sale_data->variant_id) {
                $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                $product->code = $lims_product_variant_data->item_code;
            }
            $unit_data = Unit::find($product_sale_data->sale_unit_id);
            if ($unit_data) {
                $unit = $unit_data->unit_code;
            } else
                $unit = '';
            if ($product_sale_data->product_batch_id) {
                $product_batch_data = ProductBatch::select('batch_no')->find($product_sale_data->product_batch_id);
                $product_sale[7][$key] = $product_batch_data->batch_no;
            } else
                $product_sale[7][$key] = 'N/A';
            $product_sale[0][$key] = $product->name . ' [' . $product->code . ']';
            if ($product_sale_data->imei_number)
                $product_sale[0][$key] .= '<br>IMEI or Serial Number: ' . $product_sale_data->imei_number;
            $product_sale[1][$key] = $product_sale_data->qty;
            $product_sale[2][$key] = $unit;
            $product_sale[3][$key] = $product_sale_data->tax;
            $product_sale[4][$key] = $product_sale_data->tax_rate;
            $product_sale[5][$key] = $product_sale_data->discount;
            $product_sale[6][$key] = $product_sale_data->total;
        }
        return $product_sale;
    }

    public function saleByCsv()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-add')) {
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();

            return view('sale.import', compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_tax_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function importSale(Request $request)
    {
        //get the file
        $upload = $request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        //checking if this is a CSV file
        if ($ext != 'csv')
            return redirect()->back()->with('message', 'Please upload a CSV file');

        $filePath = $upload->getRealPath();
        $file_handle = fopen($filePath, 'r');
        $i = 0;
        //validate the file
        while (!feof($file_handle)) {
            $current_line = fgetcsv($file_handle);
            if ($current_line && $i > 0) {
                $product_data[] = Product::where('code', $current_line[0])->first();
                if (!$product_data[$i - 1])
                    return redirect()->back()->with('message', 'Product does not exist!');
                $unit[] = Unit::where('unit_code', $current_line[2])->first();
                if (!$unit[$i - 1] && $current_line[2] == 'n/a')
                    $unit[$i - 1] = 'n/a';
                elseif (!$unit[$i - 1]) {
                    return redirect()->back()->with('message', 'Sale unit does not exist!');
                }
                if (strtolower($current_line[5]) != "no tax") {
                    $tax[] = Tax::where('name', $current_line[5])->first();
                    if (!$tax[$i - 1])
                        return redirect()->back()->with('message', 'Tax name does not exist!');
                } else
                    $tax[$i - 1]['rate'] = 0;

                $qty[] = $current_line[1];
                $price[] = $current_line[3];
                $discount[] = $current_line[4];
            }
            $i++;
        }
        //return $unit;
        $data = $request->except('document');
        $data['reference_no'] = 'sr-' . date("Ymd") . '-' . date("his");
        $data['user_id'] = Auth::user()->id;
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = $data['reference_no'] . '.' . $ext;
            $document->move('public/documents/sale', $documentName);
            $data['document'] = $documentName;
        }
        $item = 0;
        $grand_total = $data['shipping_cost'];
        Sale::create($data);
        $lims_sale_data = Sale::latest()->first();
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);

        foreach ($product_data as $key => $product) {
            if ($product['tax_method'] == 1) {
                $net_unit_price = $price[$key] - $discount[$key];
                $product_tax = $net_unit_price * ($tax[$key]['rate'] / 100) * $qty[$key];
                $total = ($net_unit_price * $qty[$key]) + $product_tax;
            } elseif ($product['tax_method'] == 2) {
                $net_unit_price = (100 / (100 + $tax[$key]['rate'])) * ($price[$key] - $discount[$key]);
                $product_tax = ($price[$key] - $discount[$key] - $net_unit_price) * $qty[$key];
                $total = ($price[$key] - $discount[$key]) * $qty[$key];
            }
            if ($data['sale_status'] == 1 && $unit[$key] != 'n/a') {
                $sale_unit_id = $unit[$key]['id'];
                if ($unit[$key]['operator'] == '*')
                    $quantity = $qty[$key] * $unit[$key]['operation_value'];
                elseif ($unit[$key]['operator'] == '/')
                    $quantity = $qty[$key] / $unit[$key]['operation_value'];
                $product['qty'] -= $quantity;
                $product_warehouse = Product_Warehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                $product_warehouse->qty -= $quantity;
                $product->save();
                $product_warehouse->save();
            } else
                $sale_unit_id = 0;
            //collecting mail data
            $mail_data['products'][$key] = $product['name'];
            if ($product['type'] == 'digital')
                $mail_data['file'][$key] = url('/public/product/files') . '/' . $product['file'];
            else
                $mail_data['file'][$key] = '';
            if ($sale_unit_id)
                $mail_data['unit'][$key] = $unit[$key]['unit_code'];
            else
                $mail_data['unit'][$key] = '';

            $product_sale = new Product_Sale();
            $product_sale->sale_id = $lims_sale_data->id;
            $product_sale->product_id = $product['id'];
            $product_sale->qty = $mail_data['qty'][$key] = $qty[$key];
            $product_sale->sale_unit_id = $sale_unit_id;
            $product_sale->net_unit_price = number_format((float)$net_unit_price, 2, '.', '');
            $product_sale->discount = $discount[$key] * $qty[$key];
            $product_sale->tax_rate = $tax[$key]['rate'];
            $product_sale->tax = number_format((float)$product_tax, 2, '.', '');
            $product_sale->total = $mail_data['total'][$key] = number_format((float)$total, 2, '.', '');
            $product_sale->save();
            $lims_sale_data->total_qty += $qty[$key];
            $lims_sale_data->total_discount += $discount[$key] * $qty[$key];
            $lims_sale_data->total_tax += number_format((float)$product_tax, 2, '.', '');
            $lims_sale_data->total_price += number_format((float)$total, 2, '.', '');
        }
        $lims_sale_data->item = $key + 1;
        $lims_sale_data->order_tax = ($lims_sale_data->total_price - $lims_sale_data->order_discount) * ($data['order_tax_rate'] / 100);
        $lims_sale_data->grand_total = ($lims_sale_data->total_price + $lims_sale_data->order_tax + $lims_sale_data->shipping_cost) - $lims_sale_data->order_discount;
        $lims_sale_data->save();
        $message = 'Sale imported successfully';
        if ($lims_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['reference_no'] = $lims_sale_data->reference_no;
            $mail_data['sale_status'] = $lims_sale_data->sale_status;
            $mail_data['payment_status'] = $lims_sale_data->payment_status;
            $mail_data['total_qty'] = $lims_sale_data->total_qty;
            $mail_data['total_price'] = $lims_sale_data->total_price;
            $mail_data['order_tax'] = $lims_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $lims_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $lims_sale_data->order_discount;
            $mail_data['shipping_cost'] = $lims_sale_data->shipping_cost;
            $mail_data['grand_total'] = $lims_sale_data->grand_total;
            $mail_data['paid_amount'] = $lims_sale_data->paid_amount;
            if ($mail_data['email']) {
                try {
                    Mail::send('mail.sale_details', $mail_data, function ($message) use ($mail_data) {
                        $message->to($mail_data['email'])->subject('Sale Details');
                    });
                } catch (\Exception $e) {
                    $message = 'Sale imported successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }
        return redirect('sales')->with('message', $message);
    }

    public function createSale($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-edit')) {
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_customer_group_all = CustomerGroup::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_sale_data = Sale::find($id);
            $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
            $lims_product_list = Product::where([
                ['featured', 1],
                ['is_active', true]
            ])->get();
            foreach ($lims_product_list as $key => $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
            }
            $product_number = count($lims_product_list);
            $lims_pos_setting_data = PosSetting::latest()->first();
            $lims_brand_list = Brand::where('is_active', true)->get();
            $lims_category_list = Category::where('is_active', true)->get();
            $lims_coupon_list = Coupon::where('is_active', true)->get();

            return view('sale.create_sale', compact('lims_biller_list', 'lims_customer_list', 'lims_warehouse_list', 'lims_tax_list', 'lims_sale_data', 'lims_product_sale_data', 'lims_pos_setting_data', 'lims_brand_list', 'lims_category_list', 'lims_coupon_list', 'lims_product_list', 'product_number', 'lims_customer_group_all'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function edit($id)
    {
        // dd($id);
        // dd($request->all());
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-edit')) {
            $sale = NewSale::find($id);
            $sale_products = NewSaleProduct::where('sale_id', $id)->get();

            return view('sale.edit', compact('sale', 'sale_products'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        Log::debug($request->all());

        // $rules = [
        //     'date' => 'required',
        //     'customer_id' => 'required|integer',
        //     'cash_type' => 'required|in:white,black', // white or black values
        //     'entire_vat' => 'required|min:1',
        //     'shipping_cost' => 'nullable|min:0',
        //     'sale_discount' => 'nullable|min:0|max:100',
        //     'tax_stamp' => 'nullable|min:0|max:100',
        //     'sale_note' => 'nullable',
        //     'staff_note' => 'nullable',
        //     'item_qty' => 'required|array',
        //     'item_qty.*' => 'required|numeric',
        //     'sale_price' => 'required|array',
        //     'sale_price.*' => 'required|numeric',
        //     'discount' => 'nullable|array',
        //     'discount.*' => 'nullable|numeric',
        //     'sale_total_without_discount' => 'nullable|array',
        //     'sale_total_without_discount.*' => 'nullable|numeric',
        //     'sale_total_with_discount.*' => 'nullable|array',
        //     'sale_total_with_discount.*' => 'nullable|numeric',
        //     'article_number' => 'required|array',
        //     'total_to_be_paid' => 'required',
        // ];
        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator->errors());
        // }
        try {
            $data = $request->all();
            FacadesDB::beginTransaction();
            $sale = $this->saleRepository->update($data, $id);
            FacadesDB::commit();
            if ($sale && !isset($sale['message'])) {
                toastr()->success('Sale updated successfully');
                return redirect()->route('sales.index')->with('message', 'Sales Updated successfully');
            }
            if ($sale && isset($sale['message']) && $sale['message'] == "quantity-exceeded") {
                FacadesDB::rollBack();
                toastr()->info('Your Requested Quantity is not available for Reference No. ' . $sale['reference_no']);
                return redirect()->back();
            }
            // dd($sale);
            toastr()->error($sale);
            return redirect()->back()->withErrors($sale);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            FacadesDB::rollBack();
            return $e->getMessage();
        }
    }

    public function saleProductDelete(Request $request)
    {
        $sale_product = NewSaleProduct::find($request->id);
        if ($sale_product) {
            $sale = NewSale::find($sale_product->sale_id);
            $sale_product->delete();
            $products = NewSaleProduct::where('sale_id', $sale->id)->get();


            if (count($products) <= 0) {
                $sale->delete();
                return response(1);
            }

            return response(2);
        } else {
            return response(3);
        }
    }
    public function editInvoice($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-edit')) {
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_sale_data = SalesInvoice::find($id);
            $lims_product_sale_data = InvoiceProduct::where('invoice_id', $id)->get();
            return view('sale.invoice_edit', compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_tax_list', 'lims_sale_data', 'lims_product_sale_data'));
        }
    }
    public function updateInvoice(Request $request, $id)
    {
        // dump($id);
        // dd($request->all());
        $data = $request->except('document');
        //return dd($data);
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $documentName = $document->getClientOriginalName();
            $document->move('public/sale/documents', $documentName);
            $data['document'] = $documentName;
        }
        $balance = $data['grand_total'] - $data['paid_amount'];
        if ($balance < 0 || $balance > 0)
            $data['payment_status'] = 2;
        else
            $data['payment_status'] = 4;
        $lims_sale_data = SalesInvoice::find($id);
        $lims_product_sale_data = InvoiceProduct::where('invoice_id', $id)->get();
        $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
        $product_id = $data['product_id'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $product_variant_id = $data['product_variant_id'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $old_product_id = [];
        $product_sale = [];
        foreach ($lims_product_sale_data as  $key => $product_sale_data) {
            $old_product_id[] = $product_sale_data->product_id;
            $old_product_variant_id[] = null;
            $lims_product_data = Product::find($product_sale_data->product_id);

            if (($lims_sale_data->sale_status == 1) && ($lims_product_data->type == 'combo')) {
                $product_list = explode(",", $lims_product_data->product_list);
                $variant_list = explode(",", $lims_product_data->variant_list);
                if ($lims_product_data->variant_list)
                    $variant_list = explode(",", $lims_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $lims_product_data->qty_list);

                foreach ($product_list as $index => $child_id) {
                    $child_data = Product::find($child_id);
                    if (count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = ProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]]
                        ])->first();

                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]],
                            ['warehouse_id', $lims_sale_data->warehouse_id],
                        ])->first();

                        $child_product_variant_data->qty += $product_sale_data->qty * $qty_list[$index];
                        $child_product_variant_data->save();
                    } else {
                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $lims_sale_data->warehouse_id],
                        ])->first();
                    }

                    $child_data->qty += $product_sale_data->qty * $qty_list[$index];
                    $child_warehouse_data->qty += $product_sale_data->qty * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            } elseif (($lims_sale_data->sale_status == 1) && ($product_sale_data->sale_unit_id != 0)) {
                $old_product_qty = $product_sale_data->qty;
                $lims_sale_unit_data = Unit::find($product_sale_data->sale_unit_id);
                if ($lims_sale_unit_data->operator == '*')
                    $old_product_qty = $old_product_qty * $lims_sale_unit_data->operation_value;
                else
                    $old_product_qty = $old_product_qty / $lims_sale_unit_data->operation_value;
                if ($product_sale_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($product_sale_data->product_id, $product_sale_data->variant_id, $lims_sale_data->warehouse_id)
                        ->first();
                    $old_product_variant_id[$key] = $lims_product_variant_data->id;
                    $lims_product_variant_data->qty += $old_product_qty;
                    $lims_product_variant_data->save();
                } elseif ($product_sale_data->product_batch_id) {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_sale_data->product_id],
                        ['product_batch_id', $product_sale_data->product_batch_id],
                        ['warehouse_id', $lims_sale_data->warehouse_id]
                    ])->first();

                    $product_batch_data = ProductBatch::find($product_sale_data->product_batch_id);
                    $product_batch_data->qty += $old_product_qty;
                    $product_batch_data->save();
                } else
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($product_sale_data->product_id, $lims_sale_data->warehouse_id)
                        ->first();
                $lims_product_data->qty += $old_product_qty;
                $lims_product_warehouse_data->qty += $old_product_qty;
                $lims_product_data->save();
                $lims_product_warehouse_data->save();
            }

            if ($product_sale_data->imei_number) {
                if ($lims_product_warehouse_data->imei_number)
                    $lims_product_warehouse_data->imei_number .= ',' . $product_sale_data->imei_number;
                else
                    $lims_product_warehouse_data->imei_number = $product_sale_data->imei_number;
                $lims_product_warehouse_data->save();
            }

            if ($product_sale_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id))) {
                $product_sale_data->delete();
            } elseif (!(in_array($old_product_id[$key], $product_id)))
                $product_sale_data->delete();
        }
        foreach ($product_id as $key => $pro_id) {
            $lims_product_data = Product::find($pro_id);
            $product_sale['variant_id'] = null;
            if ($lims_product_data->type == 'combo' && $data['sale_status'] == 1) {
                $product_list = explode(",", $lims_product_data->product_list);
                $variant_list = explode(",", $lims_product_data->variant_list);
                if ($lims_product_data->variant_list)
                    $variant_list = explode(",", $lims_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $lims_product_data->qty_list);

                foreach ($product_list as $index => $child_id) {
                    $child_data = Product::find($child_id);
                    if (count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = ProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]],
                        ])->first();

                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]],
                            ['warehouse_id', $data['warehouse_id']],
                        ])->first();

                        $child_product_variant_data->qty -= $qty[$key] * $qty_list[$index];
                        $child_product_variant_data->save();
                    } else {
                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $data['warehouse_id']],
                        ])->first();
                    }


                    $child_data->qty -= $qty[$key] * $qty_list[$index];
                    $child_warehouse_data->qty -= $qty[$key] * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }
            if ($sale_unit[$key] != 'n/a') {
                $lims_sale_unit_data = Unit::where('unit_name', $sale_unit[$key])->first();
                $sale_unit_id = $lims_sale_unit_data->id;
                if ($data['sale_status'] == 1) {
                    $new_product_qty = $qty[$key];
                    if ($lims_sale_unit_data->operator == '*') {
                        $new_product_qty = $new_product_qty * $lims_sale_unit_data->operation_value;
                    } else {
                        $new_product_qty = $new_product_qty / $lims_sale_unit_data->operation_value;
                    }
                    if ($lims_product_data->is_variant) {
                        $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                        $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($pro_id, $lims_product_variant_data->variant_id, $data['warehouse_id'])
                            ->first();

                        $product_sale['variant_id'] = $lims_product_variant_data->variant_id;
                        $lims_product_variant_data->qty -= $new_product_qty;
                        $lims_product_variant_data->save();
                    } elseif ($product_batch_id[$key]) {
                        $lims_product_warehouse_data = Product_Warehouse::where([
                            ['product_id', $pro_id],
                            ['product_batch_id', $product_batch_id[$key]],
                            ['warehouse_id', $data['warehouse_id']]
                        ])->first();

                        $product_batch_data = ProductBatch::find($product_batch_id[$key]);
                        $product_batch_data->qty -= $new_product_qty;
                        $product_batch_data->save();
                    } else {
                        $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($pro_id, $data['warehouse_id'])
                            ->first();
                    }
                    $lims_product_data->qty -= $new_product_qty;
                    $lims_product_warehouse_data->qty -= $new_product_qty;
                    $lims_product_data->save();
                    $lims_product_warehouse_data->save();
                }
            } else
                $sale_unit_id = 0;

            //deduct imei number if available
            if ($imei_number[$key]) {
                $imei_numbers = explode(",", $imei_number[$key]);
                $all_imei_numbers = explode(",", $lims_product_warehouse_data->imei_number);
                foreach ($imei_numbers as $number) {
                    if (($j = array_search($number, $all_imei_numbers)) !== false) {
                        unset($all_imei_numbers[$j]);
                    }
                }
                $lims_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                $lims_product_warehouse_data->save();
            }

            //collecting mail data
            if ($product_sale['variant_id']) {
                $variant_data = Variant::select('name')->find($product_sale['variant_id']);
                $mail_data['products'][$key] = $lims_product_data->name . ' [' . $variant_data->name . ']';
            } else
                $mail_data['products'][$key] = $lims_product_data->name;

            if ($lims_product_data->type == 'digital')
                $mail_data['file'][$key] = url('/public/product/files') . '/' . $lims_product_data->file;
            else
                $mail_data['file'][$key] = '';
            if ($sale_unit_id)
                $mail_data['unit'][$key] = $lims_sale_unit_data->unit_code;
            else
                $mail_data['unit'][$key] = '';

            $product_sale['invoice_id'] = $id;
            $product_sale['product_id'] = $pro_id;
            $product_sale['imei_number'] = $imei_number[$key];
            $product_sale['product_batch_id'] = $product_batch_id[$key];
            $product_sale['qty'] = $mail_data['qty'][$key] = $qty[$key];
            $product_sale['sale_unit_id'] = $sale_unit_id;
            $product_sale['net_unit_price'] = $net_unit_price[$key];
            $product_sale['discount'] = $discount[$key];
            $product_sale['tax_rate'] = $tax_rate[$key];
            $product_sale['tax'] = $tax[$key];
            $product_sale['total'] = $mail_data['total'][$key] = $total[$key];

            if ($product_sale['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                InvoiceProduct::where([
                    ['product_id', $pro_id],
                    ['variant_id', $product_sale['variant_id']],
                    ['invoice_id', $id]
                ])->update($product_sale);
            } elseif ($product_sale['variant_id'] === null && (in_array($pro_id, $old_product_id))) {
                InvoiceProduct::where([
                    ['invoice_id', $id],
                    ['product_id', $pro_id]
                ])->update($product_sale);
            } else
                InvoiceProduct::create($product_sale);
        }
        $lims_sale_data->estimate_type = 0;
        $lims_sale_data->update($data);
        $lims_customer_data = Customer::find($data['customer_id']);
        $message = 'Sale updated successfully';
        //collecting mail data
        if ($lims_customer_data->email) {
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['reference_no'] = $lims_sale_data->reference_no;
            $mail_data['sale_status'] = $lims_sale_data->sale_status;
            $mail_data['payment_status'] = $lims_sale_data->payment_status;
            $mail_data['total_qty'] = $lims_sale_data->total_qty;
            $mail_data['total_price'] = $lims_sale_data->total_price;
            $mail_data['order_tax'] = $lims_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $lims_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $lims_sale_data->order_discount;
            $mail_data['shipping_cost'] = $lims_sale_data->shipping_cost;
            $mail_data['grand_total'] = $lims_sale_data->grand_total;
            $mail_data['paid_amount'] = $lims_sale_data->paid_amount;
            if ($mail_data['email']) {
                try {
                    Mail::send('mail.sale_details', $mail_data, function ($message) use ($mail_data) {
                        $message->to($mail_data['email'])->subject('Sale Details');
                    });
                } catch (\Exception $e) {
                    $message = 'Sale updated successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }
        // $invoices = SalesInvoice::all();
        return redirect('sales/invoices')->with('message', $message);
    }

    public function printLastReciept()
    {
        $sale = Sale::where('sale_status', 1)->latest()->first();
        return redirect()->route('sale.invoice', $sale->id);
    }

    public function genInvoice($id)
    {
        $lims_sale_data = Sale::find($id);
        $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        $lims_biller_data = Biller::find($lims_sale_data->biller_id);
        $lims_warehouse_data = Warehouse::find($lims_sale_data->warehouse_id);
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        $lims_payment_data = Payment::where('sale_id', $id)->get();

        $numberToWords = new NumberToWords();
        if (\App::getLocale() == 'ar' || \App::getLocale() == 'hi' || \App::getLocale() == 'vi' || \App::getLocale() == 'en-gb')
            $numberTransformer = $numberToWords->getNumberTransformer('en');
        else
            $numberTransformer = $numberToWords->getNumberTransformer(\App::getLocale());
        $numberInWords = $numberTransformer->toWords($lims_sale_data->grand_total);

        return view('sale.invoice', compact('lims_sale_data', 'lims_product_sale_data', 'lims_biller_data', 'lims_warehouse_data', 'lims_customer_data', 'lims_payment_data', 'numberInWords'));
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        if (!$data['amount'])
            $data['amount'] = 0.00;

        $lims_sale_data = Sale::find($data['sale_id']);
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        $lims_sale_data->paid_amount += $data['amount'];
        $balance = $lims_sale_data->grand_total - $lims_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $lims_sale_data->payment_status = 4;

        if ($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        elseif ($data['paid_by_id'] == 2)
            $paying_method = 'Gift Card';
        elseif ($data['paid_by_id'] == 3)
            $paying_method = 'Credit Card';
        elseif ($data['paid_by_id'] == 4)
            $paying_method = 'Cheque';
        elseif ($data['paid_by_id'] == 5)
            $paying_method = 'Paypal';
        elseif ($data['paid_by_id'] == 6)
            $paying_method = 'Deposit';
        elseif ($data['paid_by_id'] == 7)
            $paying_method = 'Points';


        $cash_register_data = CashRegister::where([
            ['user_id', Auth::id()],
            ['warehouse_id', $lims_sale_data->warehouse_id],
            ['status', true]
        ])->first();

        $lims_payment_data = new Payment();
        $lims_payment_data->user_id = Auth::id();
        $lims_payment_data->sale_id = $lims_sale_data->id;
        if ($cash_register_data)
            $lims_payment_data->cash_register_id = $cash_register_data->id;
        $lims_payment_data->account_id = $data['account_id'];
        $data['payment_reference'] = 'spr-' . date("Ymd") . '-' . date("his");
        $lims_payment_data->payment_reference = $data['payment_reference'];
        $lims_payment_data->amount = $data['amount'];
        $lims_payment_data->change = $data['paying_amount'] - $data['amount'];
        $lims_payment_data->paying_method = $paying_method;
        $lims_payment_data->payment_note = $data['payment_note'];
        $lims_payment_data->save();
        $lims_sale_data->save();

        $lims_payment_data = Payment::latest()->first();
        $data['payment_id'] = $lims_payment_data->id;

        if ($paying_method == 'Gift Card') {
            $lims_gift_card_data = GiftCard::find($data['gift_card_id']);
            $lims_gift_card_data->expense += $data['amount'];
            $lims_gift_card_data->save();
            PaymentWithGiftCard::create($data);
        } elseif ($paying_method == 'Credit Card') {
            $lims_pos_setting_data = PosSetting::latest()->first();
            Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $lims_sale_data->customer_id)->first();

            if (!$lims_payment_with_credit_card_data) {
                // Create a Customer:
                $customer = \Stripe\Customer::create([
                    'source' => $token
                ]);

                // Charge the Customer instead of the card:
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer->id,
                ]);
                $data['customer_stripe_id'] = $customer->id;
            } else {
                $customer_id =
                    $lims_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id, // Previously stored, then retrieved
                ]);
                $data['customer_stripe_id'] = $customer_id;
            }
            $data['customer_id'] = $lims_sale_data->customer_id;
            $data['charge_id'] = $charge->id;
            PaymentWithCreditCard::create($data);
        } elseif ($paying_method == 'Cheque') {
            PaymentWithCheque::create($data);
        } elseif ($paying_method == 'Paypal') {
            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $lims_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/' . $lims_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach ($paypal_data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        } elseif ($paying_method == 'Deposit') {
            $lims_customer_data->expense += $data['amount'];
            $lims_customer_data->save();
        } elseif ($paying_method == 'Points') {
            $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
            $used_points = ceil($data['amount'] / $lims_reward_point_setting_data->per_point_amount);

            $lims_payment_data->used_points = $used_points;
            $lims_payment_data->save();

            $lims_customer_data->points -= $used_points;
            $lims_customer_data->save();
        }
        $message = 'Payment created successfully';
        if ($lims_customer_data->email) {
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['sale_reference'] = $lims_sale_data->reference_no;
            $mail_data['payment_reference'] = $lims_payment_data->payment_reference;
            $mail_data['payment_method'] = $lims_payment_data->paying_method;
            $mail_data['grand_total'] = $lims_sale_data->grand_total;
            $mail_data['paid_amount'] = $lims_payment_data->amount;
            try {
                Mail::send('mail.payment_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Payment Details');
                });
            } catch (\Exception $e) {
                $message = 'Payment created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('sales')->with('message', $message);
    }

    public function getPayment($id)
    {
        $lims_payment_list = Payment::where('sale_id', $id)->get();
        $date = [];
        $payment_reference = [];
        $paid_amount = [];
        $paying_method = [];
        $payment_id = [];
        $payment_note = [];
        $gift_card_id = [];
        $cheque_no = [];
        $change = [];
        $paying_amount = [];
        $account_name = [];
        $account_id = [];

        foreach ($lims_payment_list as $payment) {
            $date[] = date(config('date_format'), strtotime($payment->created_at->toDateString())) . ' ' . $payment->created_at->toTimeString();
            $payment_reference[] = $payment->payment_reference;
            $paid_amount[] = $payment->amount;
            $change[] = $payment->change;
            $paying_method[] = $payment->paying_method;
            $paying_amount[] = $payment->amount + $payment->change;
            if ($payment->paying_method == 'Gift Card') {
                $lims_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                $gift_card_id[] = $lims_payment_gift_card_data->gift_card_id;
            } elseif ($payment->paying_method == 'Cheque') {
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                $cheque_no[] = $lims_payment_cheque_data->cheque_no;
            } else {
                $cheque_no[] = $gift_card_id[] = null;
            }
            $payment_id[] = $payment->id;
            $payment_note[] = $payment->payment_note;
            $lims_account_data = Account::find($payment->account_id);
            $account_name[] = $lims_account_data->name;
            $account_id[] = $lims_account_data->id;
        }
        $payments[] = $date;
        $payments[] = $payment_reference;
        $payments[] = $paid_amount;
        $payments[] = $paying_method;
        $payments[] = $payment_id;
        $payments[] = $payment_note;
        $payments[] = $cheque_no;
        $payments[] = $gift_card_id;
        $payments[] = $change;
        $payments[] = $paying_amount;
        $payments[] = $account_name;
        $payments[] = $account_id;

        return $payments;
    }

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        //return $data;
        $lims_payment_data = Payment::find($data['payment_id']);
        $lims_sale_data = Sale::find($lims_payment_data->sale_id);
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        //updating sale table
        $amount_dif = $lims_payment_data->amount - $data['edit_amount'];
        $lims_sale_data->paid_amount = $lims_sale_data->paid_amount - $amount_dif;
        $balance = $lims_sale_data->grand_total - $lims_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $lims_sale_data->payment_status = 4;
        $lims_sale_data->save();

        if ($lims_payment_data->paying_method == 'Deposit') {
            $lims_customer_data->expense -= $lims_payment_data->amount;
            $lims_customer_data->save();
        } elseif ($lims_payment_data->paying_method == 'Points') {
            $lims_customer_data->points += $lims_payment_data->used_points;
            $lims_customer_data->save();
            $lims_payment_data->used_points = 0;
        }
        if ($data['edit_paid_by_id'] == 1)
            $lims_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2) {
            if ($lims_payment_data->paying_method == 'Gift Card') {
                $lims_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $data['payment_id'])->first();

                $lims_gift_card_data = GiftCard::find($lims_payment_gift_card_data->gift_card_id);
                $lims_gift_card_data->expense -= $lims_payment_data->amount;
                $lims_gift_card_data->save();

                $lims_gift_card_data = GiftCard::find($data['gift_card_id']);
                $lims_gift_card_data->expense += $data['edit_amount'];
                $lims_gift_card_data->save();

                $lims_payment_gift_card_data->gift_card_id = $data['gift_card_id'];
                $lims_payment_gift_card_data->save();
            } else {
                $lims_payment_data->paying_method = 'Gift Card';
                $lims_gift_card_data = GiftCard::find($data['gift_card_id']);
                $lims_gift_card_data->expense += $data['edit_amount'];
                $lims_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            }
        } elseif ($data['edit_paid_by_id'] == 3) {
            $lims_pos_setting_data = PosSetting::latest()->first();
            Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            if ($lims_payment_data->paying_method == 'Credit Card') {
                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $lims_payment_data->id)->first();

                \Stripe\Refund::create(array(
                    "charge" => $lims_payment_with_credit_card_data->charge_id,
                ));

                $customer_id =
                    $lims_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $data['edit_amount'] * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id
                ]);
                $lims_payment_with_credit_card_data->charge_id = $charge->id;
                $lims_payment_with_credit_card_data->save();
            } else {
                $token = $data['stripeToken'];
                $amount = $data['edit_amount'];
                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $lims_sale_data->customer_id)->first();

                if (!$lims_payment_with_credit_card_data) {
                    $customer = \Stripe\Customer::create([
                        'source' => $token
                    ]);

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id,
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                } else {
                    $customer_id =
                        $lims_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['customer_id'] = $lims_sale_data->customer_id;
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $lims_payment_data->paying_method = 'Credit Card';
        } elseif ($data['edit_paid_by_id'] == 4) {
            if ($lims_payment_data->paying_method == 'Cheque') {
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $lims_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $lims_payment_cheque_data->save();
            } else {
                $lims_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                PaymentWithCheque::create($data);
            }
        } elseif ($data['edit_paid_by_id'] == 5) {
            //updating payment data
            $lims_payment_data->amount = $data['edit_amount'];
            $lims_payment_data->paying_method = 'Paypal';
            $lims_payment_data->payment_note = $data['edit_payment_note'];
            $lims_payment_data->save();

            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['edit_amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $lims_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/' . $lims_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach ($paypal_data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        } elseif ($data['edit_paid_by_id'] == 6) {
            $lims_payment_data->paying_method = 'Deposit';
            $lims_customer_data->expense += $data['edit_amount'];
            $lims_customer_data->save();
        } elseif ($data['edit_paid_by_id'] == 7) {
            $lims_payment_data->paying_method = 'Points';
            $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
            $used_points = ceil($data['edit_amount'] / $lims_reward_point_setting_data->per_point_amount);
            $lims_payment_data->used_points = $used_points;
            $lims_customer_data->points -= $used_points;
            $lims_customer_data->save();
        }
        //updating payment data
        $lims_payment_data->account_id = $data['account_id'];
        $lims_payment_data->amount = $data['edit_amount'];
        $lims_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $lims_payment_data->payment_note = $data['edit_payment_note'];
        $lims_payment_data->save();
        $message = 'Payment updated successfully';
        //collecting male data
        if ($lims_customer_data->email) {
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['sale_reference'] = $lims_sale_data->reference_no;
            $mail_data['payment_reference'] = $lims_payment_data->payment_reference;
            $mail_data['payment_method'] = $lims_payment_data->paying_method;
            $mail_data['grand_total'] = $lims_sale_data->grand_total;
            $mail_data['paid_amount'] = $lims_payment_data->amount;
            try {
                Mail::send('mail.payment_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Payment Details');
                });
            } catch (\Exception $e) {
                $message = 'Payment updated successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('sales')->with('message', $message);
    }

    public function deletePayment(Request $request)
    {
        $lims_payment_data = Payment::find($request['id']);
        $lims_sale_data = Sale::where('id', $lims_payment_data->sale_id)->first();
        $lims_sale_data->paid_amount -= $lims_payment_data->amount;
        $balance = $lims_sale_data->grand_total - $lims_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $lims_sale_data->payment_status = 4;
        $lims_sale_data->save();

        if ($lims_payment_data->paying_method == 'Gift Card') {
            $lims_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $request['id'])->first();
            $lims_gift_card_data = GiftCard::find($lims_payment_gift_card_data->gift_card_id);
            $lims_gift_card_data->expense -= $lims_payment_data->amount;
            $lims_gift_card_data->save();
            $lims_payment_gift_card_data->delete();
        } elseif ($lims_payment_data->paying_method == 'Credit Card') {
            $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            $lims_pos_setting_data = PosSetting::latest()->first();
            Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
                "charge" => $lims_payment_with_credit_card_data->charge_id,
            ));

            $lims_payment_with_credit_card_data->delete();
        } elseif ($lims_payment_data->paying_method == 'Cheque') {
            $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $request['id'])->first();
            $lims_payment_cheque_data->delete();
        } elseif ($lims_payment_data->paying_method == 'Paypal') {
            $lims_payment_paypal_data = PaymentWithPaypal::where('payment_id', $request['id'])->first();
            if ($lims_payment_paypal_data) {
                $provider = new ExpressCheckout;
                $response = $provider->refundTransaction($lims_payment_paypal_data->transaction_id);
                $lims_payment_paypal_data->delete();
            }
        } elseif ($lims_payment_data->paying_method == 'Deposit') {
            $lims_customer_data = Customer::find($lims_sale_data->customer_id);
            $lims_customer_data->expense -= $lims_payment_data->amount;
            $lims_customer_data->save();
        } elseif ($lims_payment_data->paying_method == 'Points') {
            $lims_customer_data = Customer::find($lims_sale_data->customer_id);
            $lims_customer_data->points += $lims_payment_data->used_points;
            $lims_customer_data->save();
        }
        $lims_payment_data->delete();
        return redirect('sales')->with('not_permitted', 'Payment deleted successfully');
    }

    public function todaySale()
    {
        $data['total_sale_amount'] = Sale::whereDate('created_at', date("Y-m-d"))->sum('grand_total');
        $data['total_payment'] = Payment::whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['cash_payment'] = Payment::where([
            ['paying_method', 'Cash']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['credit_card_payment'] = Payment::where([
            ['paying_method', 'Credit Card']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['gift_card_payment'] = Payment::where([
            ['paying_method', 'Gift Card']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['deposit_payment'] = Payment::where([
            ['paying_method', 'Deposit']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['cheque_payment'] = Payment::where([
            ['paying_method', 'Cheque']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['paypal_payment'] = Payment::where([
            ['paying_method', 'Paypal']
        ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['total_sale_return'] = Returns::whereDate('created_at', date("Y-m-d"))->sum('grand_total');
        $data['total_expense'] = Expense::whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['total_cash'] = $data['total_payment'] - ($data['total_sale_return'] + $data['total_expense']);
        return $data;
    }

    public function todayProfit($warehouse_id)
    {
        if ($warehouse_id == 0)
            $product_sale_data = Product_Sale::select(DB::raw('product_id, product_batch_id, sum(qty) as sold_qty, sum(total) as sold_amount'))->whereDate('created_at', date("Y-m-d"))->groupBy('product_id', 'product_batch_id')->get();
        else
            $product_sale_data = Sale::join('product_sales', 'sales.id', '=', 'product_sales.sale_id')
                ->select(DB::raw('product_sales.product_id, product_sales.product_batch_id, sum(product_sales.qty) as sold_qty, sum(product_sales.total) as sold_amount'))
                ->where('sales.warehouse_id', $warehouse_id)->whereDate('sales.created_at', date("Y-m-d"))
                ->groupBy('product_sales.product_id', 'product_sales.product_batch_id')->get();

        $product_revenue = 0;
        $product_cost = 0;
        $profit = 0;
        foreach ($product_sale_data as $key => $product_sale) {
            if ($warehouse_id == 0) {
                if ($product_sale->product_batch_id)
                    $product_purchase_data = ProductPurchase::where([
                        ['product_id', $product_sale->product_id],
                        ['product_batch_id', $product_sale->product_batch_id]
                    ])->get();
                else
                    $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();
            } else {
                if ($product_sale->product_batch_id) {
                    $product_purchase_data = Purchase::join('product_purchases', 'purchases.id', '=', 'product_purchases.purchase_id')
                        ->where([
                            ['product_purchases.product_id', $product_sale->product_id],
                            ['product_purchases.product_batch_id', $product_sale->product_batch_id],
                            ['purchases.warehouse_id', $warehouse_id]
                        ])->select('product_purchases.*')->get();
                } else
                    $product_purchase_data = Purchase::join('product_purchases', 'purchases.id', '=', 'product_purchases.purchase_id')
                        ->where([
                            ['product_purchases.product_id', $product_sale->product_id],
                            ['purchases.warehouse_id', $warehouse_id]
                        ])->select('product_purchases.*')->get();
            }

            $purchased_qty = 0;
            $purchased_amount = 0;
            $sold_qty = $product_sale->sold_qty;
            $product_revenue += $product_sale->sold_amount;
            foreach ($product_purchase_data as $key => $product_purchase) {
                $purchased_qty += $product_purchase->qty;
                $purchased_amount += $product_purchase->total;
                if ($purchased_qty >= $sold_qty) {
                    $qty_diff = $purchased_qty - $sold_qty;
                    $unit_cost = $product_purchase->total / $product_purchase->qty;
                    $purchased_amount -= ($qty_diff * $unit_cost);
                    break;
                }
            }

            $product_cost += $purchased_amount;
            $profit += $product_sale->sold_amount - $purchased_amount;
        }

        $data['product_revenue'] = $product_revenue;
        $data['product_cost'] = $product_cost;
        if ($warehouse_id == 0)
            $data['expense_amount'] = Expense::whereDate('created_at', date("Y-m-d"))->sum('amount');
        else
            $data['expense_amount'] = Expense::where('warehouse_id', $warehouse_id)->whereDate('created_at', date("Y-m-d"))->sum('amount');

        $data['profit'] = $profit - $data['expense_amount'];
        return $data;
    }

    public function deleteBySelection(Request $request)
    {
        $sale_id = $request['saleIdArray'];
        foreach ($sale_id as $id) {
            $lims_sale_data = Sale::find($id);
            $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
            $lims_delivery_data = Delivery::where('sale_id', $id)->first();
            if ($lims_sale_data->sale_status == 3)
                $message = 'Draft deleted successfully';
            else
                $message = 'Sale deleted successfully';
            foreach ($lims_product_sale_data as $product_sale) {
                $lims_product_data = Product::find($product_sale->product_id);
                //adjust product quantity
                if (($lims_sale_data->sale_status == 1) && ($lims_product_data->type == 'combo')) {
                    $product_list = explode(",", $lims_product_data->product_list);
                    if ($lims_product_data->variant_list)
                        $variant_list = explode(",", $lims_product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $lims_product_data->qty_list);

                    foreach ($product_list as $index => $child_id) {
                        $child_data = Product::find($child_id);
                        if (count($variant_list) && $variant_list[$index]) {
                            $child_product_variant_data = ProductVariant::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index]]
                            ])->first();

                            $child_warehouse_data = Product_Warehouse::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index]],
                                ['warehouse_id', $lims_sale_data->warehouse_id],
                            ])->first();

                            $child_product_variant_data->qty += $product_sale->qty * $qty_list[$index];
                            $child_product_variant_data->save();
                        } else {
                            $child_warehouse_data = Product_Warehouse::where([
                                ['product_id', $child_id],
                                ['warehouse_id', $lims_sale_data->warehouse_id],
                            ])->first();
                        }

                        $child_data->qty += $product_sale->qty * $qty_list[$index];
                        $child_warehouse_data->qty += $product_sale->qty * $qty_list[$index];

                        $child_data->save();
                        $child_warehouse_data->save();
                    }
                } elseif (($lims_sale_data->sale_status == 1) && ($product_sale->sale_unit_id != 0)) {
                    $lims_sale_unit_data = Unit::find($product_sale->sale_unit_id);
                    if ($lims_sale_unit_data->operator == '*')
                        $product_sale->qty = $product_sale->qty * $lims_sale_unit_data->operation_value;
                    else
                        $product_sale->qty = $product_sale->qty / $lims_sale_unit_data->operation_value;
                    if ($product_sale->variant_id) {
                        $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_sale->variant_id)->first();
                        $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($lims_product_data->id, $product_sale->variant_id, $lims_sale_data->warehouse_id)->first();
                        $lims_product_variant_data->qty += $product_sale->qty;
                        $lims_product_variant_data->save();
                    } elseif ($product_sale->product_batch_id) {
                        $lims_product_batch_data = ProductBatch::find($product_sale->product_batch_id);
                        $lims_product_warehouse_data = Product_Warehouse::where([
                            ['product_batch_id', $product_sale->product_batch_id],
                            ['warehouse_id', $lims_sale_data->warehouse_id]
                        ])->first();

                        $lims_product_batch_data->qty -= $product_sale->qty;
                        $lims_product_batch_data->save();
                    } else {
                        $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($lims_product_data->id, $lims_sale_data->warehouse_id)->first();
                    }

                    $lims_product_data->qty += $product_sale->qty;
                    $lims_product_warehouse_data->qty += $product_sale->qty;
                    $lims_product_data->save();
                    $lims_product_warehouse_data->save();
                }
                $product_sale->delete();
            }
            $lims_payment_data = Payment::where('sale_id', $id)->get();
            foreach ($lims_payment_data as $payment) {
                if ($payment->paying_method == 'Gift Card') {
                    $lims_payment_with_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                    $lims_gift_card_data = GiftCard::find($lims_payment_with_gift_card_data->gift_card_id);
                    $lims_gift_card_data->expense -= $payment->amount;
                    $lims_gift_card_data->save();
                    $lims_payment_with_gift_card_data->delete();
                } elseif ($payment->paying_method == 'Cheque') {
                    $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                    $lims_payment_cheque_data->delete();
                } elseif ($payment->paying_method == 'Credit Card') {
                    $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment->id)->first();
                    $lims_payment_with_credit_card_data->delete();
                } elseif ($payment->paying_method == 'Paypal') {
                    $lims_payment_paypal_data = PaymentWithPaypal::where('payment_id', $payment->id)->first();
                    if ($lims_payment_paypal_data)
                        $lims_payment_paypal_data->delete();
                } elseif ($payment->paying_method == 'Deposit') {
                    $lims_customer_data = Customer::find($lims_sale_data->customer_id);
                    $lims_customer_data->expense -= $payment->amount;
                    $lims_customer_data->save();
                }
                $payment->delete();
            }
            if ($lims_delivery_data)
                $lims_delivery_data->delete();
            if ($lims_sale_data->coupon_id) {
                $lims_coupon_data = Coupon::find($lims_sale_data->coupon_id);
                $lims_coupon_data->used -= 1;
                $lims_coupon_data->save();
            }
            $lims_sale_data->delete();
        }
        return 'Sale deleted successfully!';
    }

    public function destroy($id)
    {
        $url = url()->previous();
        $lims_sale_data = Sale::find($id);
        $lims_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        $lims_delivery_data = Delivery::where('sale_id', $id)->first();
        if ($lims_sale_data->sale_status == 3)
            $message = 'Draft deleted successfully';
        else
            $message = 'Sale deleted successfully';

        foreach ($lims_product_sale_data as $product_sale) {
            $lims_product_data = Product::find($product_sale->product_id);
            //adjust product quantity
            if (($lims_sale_data->sale_status == 1) && ($lims_product_data->type == 'combo')) {
                $product_list = explode(",", $lims_product_data->product_list);
                $variant_list = explode(",", $lims_product_data->variant_list);
                $qty_list = explode(",", $lims_product_data->qty_list);
                if ($lims_product_data->variant_list)
                    $variant_list = explode(",", $lims_product_data->variant_list);
                else
                    $variant_list = [];
                foreach ($product_list as $index => $child_id) {
                    $child_data = Product::find($child_id);
                    if (count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = ProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]]
                        ])->first();

                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]],
                            ['warehouse_id', $lims_sale_data->warehouse_id],
                        ])->first();

                        $child_product_variant_data->qty += $product_sale->qty * $qty_list[$index];
                        $child_product_variant_data->save();
                    } else {
                        $child_warehouse_data = Product_Warehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $lims_sale_data->warehouse_id],
                        ])->first();
                    }

                    $child_data->qty += $product_sale->qty * $qty_list[$index];
                    $child_warehouse_data->qty += $product_sale->qty * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            } elseif (($lims_sale_data->sale_status == 1) && ($product_sale->sale_unit_id != 0)) {
                $lims_sale_unit_data = Unit::find($product_sale->sale_unit_id);
                if ($lims_sale_unit_data->operator == '*')
                    $product_sale->qty = $product_sale->qty * $lims_sale_unit_data->operation_value;
                else
                    $product_sale->qty = $product_sale->qty / $lims_sale_unit_data->operation_value;
                if ($product_sale->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_sale->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($lims_product_data->id, $product_sale->variant_id, $lims_sale_data->warehouse_id)->first();
                    $lims_product_variant_data->qty += $product_sale->qty;
                    $lims_product_variant_data->save();
                } elseif ($product_sale->product_batch_id) {
                    $lims_product_batch_data = ProductBatch::find($product_sale->product_batch_id);
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_batch_id', $product_sale->product_batch_id],
                        ['warehouse_id', $lims_sale_data->warehouse_id]
                    ])->first();

                    $lims_product_batch_data->qty -= $product_sale->qty;
                    $lims_product_batch_data->save();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($lims_product_data->id, $lims_sale_data->warehouse_id)->first();
                }

                $lims_product_data->qty += $product_sale->qty;
                $lims_product_warehouse_data->qty += $product_sale->qty;
                $lims_product_data->save();
                $lims_product_warehouse_data->save();
            }
            if ($product_sale->imei_number) {
                if ($lims_product_warehouse_data->imei_number)
                    $lims_product_warehouse_data->imei_number .= ',' . $product_sale->imei_number;
                else
                    $lims_product_warehouse_data->imei_number = $product_sale->imei_number;
                $lims_product_warehouse_data->save();
            }
            $product_sale->delete();
        }

        $lims_payment_data = Payment::where('sale_id', $id)->get();
        foreach ($lims_payment_data as $payment) {
            if ($payment->paying_method == 'Gift Card') {
                $lims_payment_with_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                $lims_gift_card_data = GiftCard::find($lims_payment_with_gift_card_data->gift_card_id);
                $lims_gift_card_data->expense -= $payment->amount;
                $lims_gift_card_data->save();
                $lims_payment_with_gift_card_data->delete();
            } elseif ($payment->paying_method == 'Cheque') {
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                $lims_payment_cheque_data->delete();
            } elseif ($payment->paying_method == 'Credit Card') {
                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment->id)->first();
                $lims_payment_with_credit_card_data->delete();
            } elseif ($payment->paying_method == 'Paypal') {
                $lims_payment_paypal_data = PaymentWithPaypal::where('payment_id', $payment->id)->first();
                if ($lims_payment_paypal_data)
                    $lims_payment_paypal_data->delete();
            } elseif ($payment->paying_method == 'Deposit') {
                $lims_customer_data = Customer::find($lims_sale_data->customer_id);
                $lims_customer_data->expense -= $payment->amount;
                $lims_customer_data->save();
            }
            $payment->delete();
        }
        if ($lims_delivery_data)
            $lims_delivery_data->delete();
        if ($lims_sale_data->coupon_id) {
            $lims_coupon_data = Coupon::find($lims_sale_data->coupon_id);
            $lims_coupon_data->used -= 1;
            $lims_coupon_data->save();
        }
        $lims_sale_data->delete();
        return Redirect::to($url)->with('not_permitted', $message);
    }
    public function approveEstimate($id)
    {
        $sale = Sale::Find($id);
        // dump($sale);
        $customer = Customer::find($sale->customer_id);
        // dd($customer);
        $products = Product_Sale::where('sale_id', $id)->get();
        // dd($products);
        // $sale->estimate_type = 1;
        // $sale->update();
        // dd($sale);
        return view('sale.approved_estimate', compact('sale', 'customer', 'products'));
    }
    public function acceptEstimate($id)
    {
        $sale = Sale::Find($id);
        $sale->estimate_type = 3;
        $sale->update();
        return redirect()->route('sales.index');
    }
    public function cancelEstimate($id)
    {
        $sale = Sale::Find($id);
        $sale->estimate_type = 2;
        $sale->update();
        return redirect()->route('sales.index');
    }
    public function negotiateEstimate($id)
    {
        $sale = Sale::Find($id);
        $sale->estimate_type = 1;
        $sale->update();
        return redirect()->route('sales.index');
    }
    public function reactivateEstimate($id)
    {
        $sale = Sale::Find($id);
        $sale->estimate_type = 0;
        $sale->update();
        return redirect()->route('sales.index');
    }
    public function salesInvoices()
    {
        $invoices = SalesInvoice::orderBy('id', 'desc')->get();
        return view('sale.invoices_index', compact('invoices'));
    }
    public function createSaleInvoice($id)
    {
        $invoice = SalesInvoice::latest()->first();
        $sale_invoice = new SalesInvoice;
        $sale = Sale::Find($id);
        $sale_invoice->user_id = $sale->user_id;
        $sale_invoice->customer_id = $sale->customer_id;
        $sale_invoice->item = $sale->item;
        $sale_invoice->total_qty = $sale->total_qty;
        $sale_invoice->total_discount = $sale->total_discount;
        $sale_invoice->total_tax = $sale->total_tax;
        $sale_invoice->total_price = $sale->total_price;
        $sale_invoice->grand_total = $sale->grand_total;
        $sale_invoice->order_tax_rate = $sale->order_tax_rate;
        $sale_invoice->order_tax = $sale->order_tax;
        $sale_invoice->order_discount_type = $sale->order_discount_type;
        $sale_invoice->order_discount_value = $sale->order_discount_value;
        $sale_invoice->order_discount = $sale->order_discount;
        $sale_invoice->coupon_id = $sale->coupon_id;
        $sale_invoice->coupon_discount = $sale->coupon_discount;
        $sale_invoice->shipping_cost = $sale->shipping_cost;
        $sale_invoice->sale_status = $sale->sale_status;
        $sale_invoice->payment_status = $sale->payment_status;
        $sale_invoice->estimate_type = $sale->estimate_type;
        $sale_invoice->payment_method = $sale->payment_method;
        $sale_invoice->document = $sale->document;
        $sale_invoice->paid_amount = $sale->paid_amount;
        $sale_invoice->sale_note = $sale->sale_note;
        $sale_invoice->staff_note = $sale->staff_note;
        // dd($sale);
        $sale_invoice->status = 1;
        if (!$invoice)
            $sale_invoice->invoice_number = 1;
        else
            $sale_invoice->invoice_number = $invoice->invoice_number + 1;
        $sale_invoice->save();
        $product_sales = Product_Sale::where('sale_id', $id)->get();
        foreach ($product_sales as $product_sale) {
            $invoice_product = new InvoiceProduct;
            $invoice_product->invoice_id = $sale_invoice->id;
            $invoice_product->product_id = $product_sale->product_id;
            $invoice_product->product_batch_id = $product_sale->product_batch_id;
            $invoice_product->variant_id = $product_sale->variant_id;
            $invoice_product->imei_number = $product_sale->imei_number;
            $invoice_product->qty = $product_sale->qty;
            $invoice_product->sale_unit_id = $product_sale->sale_unit_id;
            $invoice_product->net_unit_price = $product_sale->net_unit_price;
            $invoice_product->discount = $product_sale->discount;
            $invoice_product->tax_rate = $product_sale->tax_rate;
            $invoice_product->tax = $product_sale->tax;
            $invoice_product->total = $product_sale->total;
            $invoice_product->save();
        }




        // dd($product_sale);
        return redirect('sales/invoices');
    }

    public function changeInvoiceStatus($id, $val)
    {
        $invoice = SalesInvoice::find($id);
        $invoice->status = $val;
        $invoice->update();
        return redirect('sales/invoices');
    }
    public function PreInvoicePDF($id)
    {

        $sale = ERPInvoice::Find($id);
        // dd($customer);
        $products = ERPInvoiceProduct::where('invoice_id', $id)->get();
        // dd($products);
        $data = [
            'sale' => $sale,
            'products' => $products
        ];

        // $pdf = PDF::loadView('sale.preinvoice_view', $data);

        return view('sale.preinvoice_view', $data);
    }
    public function generatePreInvoicePDF($id)
    {

        $sale = ERPInvoice::Find($id);
        // dd($customer);
        $products = ERPInvoiceProduct::where('invoice_id', $id)->get();
        // dd($products);
        $data = [
            'sale' => $sale,
            'products' => $products
        ];

        $pdf = PDF::loadView('sale.preinvoice_view', $data);

        return $pdf->download('preinvoice.pdf');
    }
}
