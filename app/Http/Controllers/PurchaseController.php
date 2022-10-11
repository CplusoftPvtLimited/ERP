<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Supplier;
use App\Product;
use App\Unit;
use App\Tax;
use App\Account;
use App\Models\Manufacturer;
use App\Models\ModelSeries;
use App\Models\LinkageTarget;
use App\Purchase;
use App\ProductPurchase;
use App\Product_Warehouse;
use App\Payment;
use App\PaymentWithCheque;
use App\PaymentWithCreditCard;
use App\PosSetting;
use App\Models\AssemblyGroupNode;
use App\Models\Article;
use App\Models\Ambrand;
use DB;
use App\GeneralSetting;
use App\Models\AfterMarkitSupplier;
use Stripe\Stripe;
use Auth;
use App\User;
use App\ProductVariant;
use App\ProductBatch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\PurchaseInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use PDF;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PurchaseController extends Controller
{
    private $purchaseRepository;

    public function __construct(PurchaseInterface $purchaseInterface)
    {
        $this->purchaseRepository = $purchaseInterface;
        // $this->auth_user = auth()->guard('api')->user();
    }


    // public function index(Request $request)
    // {
    //     // dd($request->all());
    //     $role = Role::find(Auth::user()->role_id);
    //     if ($role->hasPermissionTo('purchases-index')) {
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
    //         $permissions = Role::findByName($role->name)->permissions;
    //         foreach ($permissions as $permission)
    //             $all_permission[] = $permission->name;
    //         if (empty($all_permission))
    //             $all_permission[] = 'dummy text';
    //         $lims_pos_setting_data = PosSetting::latest()->first();
    //         $lims_warehouse_list = Warehouse::where('is_active', true)->get();
    //         $lims_account_list = Account::where('is_active', true)->get();
    //         return view('purchase.index', compact('lims_account_list', 'lims_warehouse_list', 'all_permission', 'lims_pos_setting_data', 'warehouse_id', 'starting_date', 'ending_date'));
    //     } else
    //         return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $all_purchase = ->get();
            return Datatables::of(Purchase::where('user_id', Auth::user()->id)->orderBy('id', 'DESC'))
                ->addIndexColumn('id')
                ->addColumn('supplier', function ($row) {
                    return isset($row->afterMarkitSupplier->name) ? $row->afterMarkitSupplier->name : null;
                })
                ->addColumn('due_amount', function ($row) {
                    $due_amount = $row->grand_total - $row->paid_amount;
                    return $due_amount;
                })
                ->addColumn('purchase_status', function ($row) {
                    $check_odd_one = [];
                    foreach ($row->productPurchases as $product) {
                        if ($product->status == "ordered") {
                            array_push($check_odd_one, $product->status);
                        }
                    }
                    $purchase_status = "";
                    if (count($check_odd_one) > 0) {
                        $purchase_status = "Pending";
                    } else {
                        $purchase_status = "Completed";
                    }
                    return $purchase_status;
                })->editColumn('grand_total', function ($row) {
                    $grand_total = $row['grand_total']. " TND";
                    return $grand_total;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row">
                         <div class="col-sm-3">
                         <a> <button
                         class="btn btn-danger btn-sm" onclick = "deletePurchase(' . $row["id"] . ')" style="" type="button"
                         data-original-title="btn btn-danger btn-sm"
                         title="Delete"><i class="fa fa-trash"></i></button></a>
                         </div>
                         
                         <div class="col-sm-3">
                         <a href="editPurchase/' . $row["id"] . '"> <button
                                     class="btn btn-primary btn-sm " type="button"
                                     data-original-title="btn btn-danger btn-xs"
                                     title=""><i class="fa fa-edit"></i></button></a>
                         </div>

                         <div class="col-sm-3">
                         <a href="viewPurchase/' . $row["id"] . '"> <button
                                     class="btn btn-success btn-sm " type="button"
                                     data-original-title="btn btn-success btn-xs"
                                     title=""><i class="fa fa-eye"></i></button></a>
                         </div>
                         
                     </div>
                     ';

                    return $btn;
                })
                ->rawColumns(['supplier', 'due_amount', 'purchase_status', 'action'])->make(true);
        }

        return view('purchase.purchases_product_index');
    }

    public function purchaseData(Request $request)
    {
        // dd($request->all());
        $columns = array(
            1 => 'created_at',
            2 => 'reference_no',
            5 => 'grand_total',
            6 => 'paid_amount',
        );

        $warehouse_id = $request->input('warehouse_id');
        if (Auth::user()->role_id > 2 && config('staff_access') == 'own')
            $totalData = Purchase::where('user_id', Auth::id())
                ->whereDate('created_at', '>=', $request->input('starting_date'))
                ->whereDate('created_at', '<=', $request->input('ending_date'))
                ->count();
        elseif ($warehouse_id != 0)
            $totalData = Purchase::where('warehouse_id', $warehouse_id)->whereDate('created_at', '>=', $request->input('starting_date'))->whereDate('created_at', '<=', $request->input('ending_date'))->count();
        else
            $totalData = Purchase::whereDate('created_at', '>=', $request->input('starting_date'))->whereDate('created_at', '<=', $request->input('ending_date'))->count();

        $totalFiltered = $totalData;

        if ($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if (empty($request->input('search.value'))) {
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $purchases = Purchase::with('brand', 'warehouse')->offset($start)
                    ->where('user_id', Auth::id())
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                dd("fdgdfg", $purchases);
            } elseif ($warehouse_id != 0)
                $purchases = Purchase::with('supplier', 'warehouse')->offset($start)
                    ->where('warehouse_id', $warehouse_id)
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            else
                $purchases = Purchase::with('supplier', 'warehouse')->offset($start)
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
        } else {
            $search = $request->input('search.value');
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $purchases =  Purchase::select('purchases.*')
                    ->with('supplier', 'warehouse')
                    ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                    ->whereDate('purchases.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('purchases.user_id', Auth::id())
                    ->orwhere([
                        ['purchases.reference_no', 'LIKE', "%{$search}%"],
                        ['purchases.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['suppliers.name', 'LIKE', "%{$search}%"],
                        ['purchases.user_id', Auth::id()]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)->get();

                $totalFiltered = Purchase::leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                    ->whereDate('purchases.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->where('purchases.user_id', Auth::id())
                    ->orwhere([
                        ['purchases.reference_no', 'LIKE', "%{$search}%"],
                        ['purchases.user_id', Auth::id()]
                    ])
                    ->orwhere([
                        ['suppliers.name', 'LIKE', "%{$search}%"],
                        ['purchases.user_id', Auth::id()]
                    ])
                    ->count();
            } else {
                $purchases =  Purchase::select('purchases.*')
                    ->with('supplier', 'warehouse')
                    ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                    ->whereDate('purchases.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->orwhere('purchases.reference_no', 'LIKE', "%{$search}%")
                    ->orwhere('suppliers.name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Purchase::leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                    ->whereDate('purchases.created_at', '=', date('Y-m-d', strtotime(str_replace('/', '-', $search))))
                    ->orwhere('purchases.reference_no', 'LIKE', "%{$search}%")
                    ->orwhere('suppliers.name', 'LIKE', "%{$search}%")
                    ->count();
            }
        }
        $data = array();
        if (!empty($purchases)) {
            foreach ($purchases as $key => $purchase) {
                $nestedData['id'] = $purchase->id;
                $nestedData['key'] = $key;
                $nestedData['date'] = date(config('date_format'), strtotime($purchase->created_at->toDateString()));
                $nestedData['reference_no'] = $purchase->reference_no;

                if ($purchase->supplier_id) {
                    $supplier = $purchase->supplier;
                } else {
                    $supplier = new Supplier();
                }
                $nestedData['supplier'] = $supplier->name;
                if ($purchase->status == 1) {
                    $nestedData['purchase_status'] = '<div class="badge badge-success">' . trans('file.Recieved') . '</div>';
                    $purchase_status = trans('file.Recieved');
                } elseif ($purchase->status == 2) {
                    $nestedData['purchase_status'] = '<div class="badge badge-success">' . trans('file.Partial') . '</div>';
                    $purchase_status = trans('file.Partial');
                } elseif ($purchase->status == 3) {
                    $nestedData['purchase_status'] = '<div class="badge badge-danger">' . trans('file.Pending') . '</div>';
                    $purchase_status = trans('file.Pending');
                } else {
                    $nestedData['purchase_status'] = '<div class="badge badge-danger">' . trans('file.Ordered') . '</div>';
                    $purchase_status = trans('file.Ordered');
                }

                if ($purchase->payment_status == 1)
                    $nestedData['payment_status'] = '<div class="badge badge-danger">' . trans('file.Due') . '</div>';
                else
                    $nestedData['payment_status'] = '<div class="badge badge-success">' . trans('file.Paid') . '</div>';

                $nestedData['grand_total'] = number_format($purchase->grand_total, 2);
                $nestedData['paid_amount'] = number_format($purchase->paid_amount, 2);
                $nestedData['due'] = number_format($purchase->grand_total - $purchase->paid_amount, 2);
                $nestedData['options'] = '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . trans("file.action") . '
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> ' . trans('file.View') . '</button>
                                </li>';
                if (in_array("purchases-edit", $request['all_permission']))
                    $nestedData['options'] .= '<li>
                        <a href="' . route('purchases.edit', $purchase->id) . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans('file.edit') . '</a>
                        </li>';
                $nestedData['options'] .=
                    '<li>
                        <button type="button" class="add-payment btn btn-link" data-id = "' . $purchase->id . '" data-toggle="modal" data-target="#add-payment"><i class="fa fa-plus"></i> ' . trans('file.Add Payment') . '</button>
                    </li>
                    <li>
                        <button type="button" class="get-payment btn btn-link" data-id = "' . $purchase->id . '"><i class="fa fa-money"></i> ' . trans('file.View Payment') . '</button>
                    </li>';
                if (in_array("purchases-delete", $request['all_permission']))
                    $nestedData['options'] .= \Form::open(["route" => ["purchases.destroy", $purchase->id], "method" => "DELETE"]) . '
                            <li>
                              <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> ' . trans("file.delete") . '</button> 
                            </li>' . \Form::close() . '
                        </ul>
                    </div>';

                // data for purchase details by one click
                $user = User::find($purchase->user_id);

                $nestedData['purchase'] = array(
                    '[ "' . date(config('date_format'), strtotime($purchase->created_at->toDateString())) . '"', ' "' . $purchase->reference_no . '"', ' "' . $purchase_status . '"',  ' "' . $purchase->id . '"', ' "' . $supplier->name . '"', ' "' . $supplier->company_name . '"', ' "' . $supplier->email . '"', ' "' . $supplier->phone_number . '"', ' "' . $supplier->address . '"', ' "' . $supplier->city . '"', ' "' . $purchase->total_tax . '"', ' "' . $purchase->total_discount . '"', ' "' . $purchase->total_cost . '"', ' "' . $purchase->order_tax . '"', ' "' . $purchase->order_tax_rate . '"', ' "' . $purchase->order_discount . '"', ' "' . $purchase->shipping_cost . '"', ' "' . $purchase->grand_total . '"', ' "' . $purchase->paid_amount . '"', ' "' . preg_replace('/\s+/S', " ", $purchase->note) . '"', ' "' . $user->name . '"', ' "' . $user->email . '"]'
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
        // dd($json_data);
        echo json_encode($json_data);
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('purchases-add')) {
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_tax_list = Tax::get();
            $lims_product_list_without_variant = $this->productWithoutVariant();
            $lims_product_list_with_variant = $this->productWithVariant();
            $manufacturers = Manufacturer::all();
            $articles = Article::select('articleNumber')->get();
            $article_array = [];
            if(count($articles) > 0){
                foreach ($articles as $key => $value) {
                    array_push($article_array,$value->articleNumber);
                }
            }
            
            $suppliers = AfterMarkitSupplier::select('id', 'name')->where('retailer_id', auth()->user()->id)->get();
            return view('purchase.create', compact('lims_supplier_list', 'lims_warehouse_list', 'lims_tax_list', 'lims_product_list_without_variant', 'lims_product_list_with_variant', 'manufacturers', 'suppliers','article_array'));
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

    public function limsProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $lims_product_data = Product::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if (!$lims_product_data) {
            $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.*', 'product_variants.item_code')
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    ['products.is_active', true]
                ])->first();
        }

        $product[] = $lims_product_data->name;
        if ($lims_product_data->is_variant)
            $product[] = $lims_product_data->item_code;
        else
            $product[] = $lims_product_data->code;
        $product[] = $lims_product_data->cost;

        if ($lims_product_data->tax_id) {
            $lims_tax_data = Tax::find($lims_product_data->tax_id);
            $product[] = $lims_tax_data->rate;
            $product[] = $lims_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $lims_product_data->tax_method;

        $units = Unit::where("base_unit", $lims_product_data->unit_id)
            ->orWhere('id', $lims_product_data->unit_id)
            ->get();
        $unit_name = array();
        $unit_operator = array();
        $unit_operation_value = array();
        foreach ($units as $unit) {
            if ($lims_product_data->purchase_unit_id == $unit->id) {
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
        $product[] = $lims_product_data->id;
        $product[] = $lims_product_data->is_batch;
        $product[] = $lims_product_data->is_imei;
        return $product;
    }

    public function store(Request $request)
    {
        Log::debug($request->all());
        // dd($request->all());

        $this->validate($request, [
            'item_qty.*' => 'required',
            'sale_price.*' => 'required',
            'purchase_price.*' => 'required',
            'modell_id.*' => 'required',
            'enginee_id.*' => 'required',
            'sectionn_id.*' => 'required',
            'sectionn_part_id.*' => 'required',
            'manufacturer_id.*' => 'required',
            'statuss.*' => 'required',
            'datee.*' => 'required',
            'brand_id.*' => 'required',
            'purchase_additional_cost' => 'nullable|min:0',
            'additional_cost_without_vat.*' => 'required|min:0',
            'additional_cost_with_vat.*' => 'nullable|min:0',
            'vat.*' => 'nullable|min:0',
            'profit_margin.*' => 'nullable|min:0',
            'total_excluding_vat.*' => 'required|min:0',
            'actual_cost_per_product.*' => 'required|min:0',
        ]);

        $purchase = $this->purchaseRepository->store($request);
        if ($purchase == "submit_purchase_not_allowed") {
            toastr()->info('Please enter the item quantity');
            return redirect('purchases');
        }
        if ($purchase == "true") {
            toastr()->success('Purchase created successfully');
            return redirect()->route('purchases.index')->with('message', 'Purchase created successfully');
        } else {
            // dd($purchase);
            toastr()->error($purchase);
            return redirect()->back()->withErrors($purchase);
        }
    }

    public function viewPurchase($id)
    {
        $get_purchase = $this->purchaseRepository->view($id);
        if ($get_purchase != "null") {
            $purchase = $get_purchase['purchase'];
            $purchase_products = $get_purchase['purchase_products'];
            // dd($purchase_products);
            return view('purchase.view', compact('purchase', 'purchase_products'));
        } else {
            toastr()->info('product not found');
            return redirect()->back();
        }
    }

    public function editPurchase($id)
    {
        $get_purchase = $this->purchaseRepository->edit($id);
        if ($get_purchase != "null") {
            $purchase = $get_purchase['purchase'];
            $purchase_products = $get_purchase['purchase_products'];
            // dd($purchase_products);
            return view('purchase.edit_purchase', compact('purchase', 'purchase_products'));
        } else {
            toastr()->info('product not found');
            return redirect()->back();
        }
    }

    public function updatePurchase(Request $request)
    {
        Log::debug($request->all());
        try {
            $update_purchase = $this->purchaseRepository->updatePurchase($request);
            if ($update_purchase) {
                toastr()->success('product status updated and stock updated successfully');
                return redirect()->back();
            } else {
                toastr()->info('product not found');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $e->getMessage();
        }
    }

    public function updatePurchaseProductQuantity(Request $request){
        Log::debug($request->all());
        try {
            $update_purchase_product_quantity = $this->purchaseRepository->updatePurchaseProductQuantity($request);
            if ($update_purchase_product_quantity == "update") {
                toastr()->success('Purchase Product Quantity Changed');
                return response("true");
            } else if($update_purchase_product_quantity == "purchase_no_found"){
                toastr()->info('product not found');
                return response('false');
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            toastr()->error("Something went wrong");
                return response("false");
        }
    }

    public function deletePurchaseProduct($purchase_id, $id)
    {
        // dd($id);
        $get_purchase_product = $this->purchaseRepository->deletePurchaseProduct($purchase_id, $id);
        if ($get_purchase_product == "true") {
            toastr()->success('Product Deleted Successfully');
            return redirect()->route('purchases.index');
        } else {
            toastr()->info('Product not found');
            return redirect()->back();
        }
    }

    public function deleteParentPurchase($purchase_id)
    {
        // dd($id);
        $get_purchase = $this->purchaseRepository->deleteParentPurchase($purchase_id);
        if ($get_purchase == "true") {
            toastr()->success('Purchase and related products has been deleted successfully!');
            return redirect()->route('purchases.index');
        } else {
            toastr()->info('Purchase not found!');
            return redirect()->back();
        }
    }

    public function exportPurchases()
    {
        $headers = [
            'Cache-Control'        => 'must-revalidate, post-check=0, pre-check=0', 'Content-type'        => 'text/csv', 'Content-Disposition' => 'attachment; filename=purchases_csv_export.csv', 'Expires'             => '0', 'Pragma'              => 'public',
        ];

        $get_purchase = $this->purchaseRepository->exportPurchases();

        if ($get_purchase != "false") {
            array_unshift($get_purchase, array_keys($get_purchase[0]));
            // dd($new_list);

            $callback = function () use ($get_purchase) {
                $FH = fopen('php://output', 'w');
                foreach ($get_purchase as $row) {
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream($callback, 200, $headers);
        } else {
            toastr()->info('Purchases not found');
            return back();
        }
    }

    public function pdfDownload()
    {

        $purchases = Purchase::where('user_id', Auth::user()->id)->get();
        $all_data = [];
        if (count($purchases) > 0) {
            foreach ($purchases as $purchase_get) {
                $purchase_products = [];
                $purchases_products = ProductPurchase::where('purchase_id', $purchase_get->id)->get();
                foreach ($purchases_products as $lims_purchase_data) {
                    $manufacturer = Manufacturer::where('manuId', $lims_purchase_data->manufacture_id)->first();
                    $model = ModelSeries::where('modelId', $lims_purchase_data->model_id)->first();
                    $engine = LinkageTarget::where('linkageTargetId', $lims_purchase_data->eng_linkage_target_id)->first();
                    $section = AssemblyGroupNode::where('assemblyGroupNodeId', $lims_purchase_data->assembly_group_node_id)->first();
                    $section_part = Article::where('legacyArticleId', $lims_purchase_data->legacy_article_id)->first();
                    // dd($lims_purchase_data->legacy_article_id);
                    $supplier = Ambrand::where('BrandId', $lims_purchase_data->supplier_id)->first();

                    $lims_purchase_data['manufacturer'] = isset($manufacturer) ? $manufacturer->manuName : '';
                    $lims_purchase_data['model'] = isset($model) ? $model->modelname : '';
                    $lims_purchase_data['engine'] = isset($engine) ? $engine->description : '';
                    $lims_purchase_data['section'] = isset($section) ? $section->assemblyGroupName : '';
                    $lims_purchase_data['section_part'] = isset($section_part) ? $section_part->articleNumber : '';
                    $lims_purchase_data['supplier'] = isset($supplier) ? $supplier->brandName : '';

                    array_push($purchase_products, $lims_purchase_data);
                }
                $purchase = [
                    'purchase' => $purchase_get,
                    'purchase_products' => $purchase_products
                ];
                array_push($all_data, $purchase);
            }

            $pdf = PDF::loadView('purchase.purchase_pdf', compact('all_data'));
            // dd($pdf);
            return $pdf->download('product_purchases.pdf');
        } else {
            toastr()->info('Data not found');
            return back();
        }
    }



    // ========================= Above code is ours code ====================
    public function productPurchaseData($id)
    {
        $lims_product_purchase_data = ProductPurchase::where('purchase_id', $id)->get();
        foreach ($lims_product_purchase_data as $key => $product_purchase_data) {
            $product = Product::find($product_purchase_data->product_id);
            $unit = Unit::find($product_purchase_data->purchase_unit_id);
            if ($product_purchase_data->variant_id) {
                $lims_product_variant_data = ProductVariant::FindExactProduct($product->id, $product_purchase_data->variant_id)->select('item_code')->first();
                $product->code = $lims_product_variant_data->item_code;
            }
            if ($product_purchase_data->product_batch_id) {
                $product_batch_data = ProductBatch::select('batch_no')->find($product_purchase_data->product_batch_id);
                $product_purchase[7][$key] = $product_batch_data->batch_no;
            } else
                $product_purchase[7][$key] = 'N/A';
            $product_purchase[0][$key] = $product->name . ' [' . $product->code . ']';
            if ($product_purchase_data->imei_number) {
                $product_purchase[0][$key] .= '<br>IMEI or Serial Number: ' . $product_purchase_data->imei_number;
            }
            $product_purchase[1][$key] = $product_purchase_data->qty;
            $product_purchase[2][$key] = $unit->unit_code;
            $product_purchase[3][$key] = $product_purchase_data->tax;
            $product_purchase[4][$key] = $product_purchase_data->tax_rate;
            $product_purchase[5][$key] = $product_purchase_data->discount;
            $product_purchase[6][$key] = $product_purchase_data->total;
        }
        return $product_purchase;
    }

    public function purchaseByCsv()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('purchases-add')) {
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();

            return view('purchase.import', compact('lims_supplier_list', 'lims_warehouse_list', 'lims_tax_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function importPurchase(Request $request)
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
                    return redirect()->back()->with('message', 'Product with this code ' . $current_line[0] . ' does not exist!');
                $unit[] = Unit::where('unit_code', $current_line[2])->first();
                if (!$unit[$i - 1])
                    return redirect()->back()->with('message', 'Purchase unit does not exist!');
                if (strtolower($current_line[5]) != "no tax") {
                    $tax[] = Tax::where('name', $current_line[5])->first();
                    if (!$tax[$i - 1])
                        return redirect()->back()->with('message', 'Tax name does not exist!');
                } else
                    $tax[$i - 1]['rate'] = 0;

                $qty[] = $current_line[1];
                $cost[] = $current_line[3];
                $discount[] = $current_line[4];
            }
            $i++;
        }

        $data = $request->except('file');
        $data['reference_no'] = 'pr-' . date("Ymd") . '-' . date("his");
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
            $document->move('public/documents/purchase', $documentName);
            $data['document'] = $documentName;
        }
        $item = 0;
        $grand_total = $data['shipping_cost'];
        $data['user_id'] = Auth::id();
        Purchase::create($data);
        $lims_purchase_data = Purchase::latest()->first();

        foreach ($product_data as $key => $product) {
            if ($product['tax_method'] == 1) {
                $net_unit_cost = $cost[$key] - $discount[$key];
                $product_tax = $net_unit_cost * ($tax[$key]['rate'] / 100) * $qty[$key];
                $total = ($net_unit_cost * $qty[$key]) + $product_tax;
            } elseif ($product['tax_method'] == 2) {
                $net_unit_cost = (100 / (100 + $tax[$key]['rate'])) * ($cost[$key] - $discount[$key]);
                $product_tax = ($cost[$key] - $discount[$key] - $net_unit_cost) * $qty[$key];
                $total = ($cost[$key] - $discount[$key]) * $qty[$key];
            }
            if ($data['status'] == 1) {
                if ($unit[$key]['operator'] == '*')
                    $quantity = $qty[$key] * $unit[$key]['operation_value'];
                elseif ($unit[$key]['operator'] == '/')
                    $quantity = $qty[$key] / $unit[$key]['operation_value'];
                $product['qty'] += $quantity;
                $product_warehouse = Product_Warehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                if ($product_warehouse) {
                    $product_warehouse->qty += $quantity;
                    $product_warehouse->save();
                } else {
                    $lims_product_warehouse_data = new Product_Warehouse();
                    $lims_product_warehouse_data->product_id = $product['id'];
                    $lims_product_warehouse_data->warehouse_id = $data['warehouse_id'];
                    $lims_product_warehouse_data->qty = $quantity;
                    $lims_product_warehouse_data->save();
                }
                $product->save();
            }

            $product_purchase = new ProductPurchase();
            $product_purchase->purchase_id = $lims_purchase_data->id;
            $product_purchase->product_id = $product['id'];
            $product_purchase->qty = $qty[$key];
            if ($data['status'] == 1)
                $product_purchase->recieved = $qty[$key];
            else
                $product_purchase->recieved = 0;
            $product_purchase->purchase_unit_id = $unit[$key]['id'];
            $product_purchase->net_unit_cost = number_format((float)$net_unit_cost, 2, '.', '');
            $product_purchase->discount = $discount[$key] * $qty[$key];
            $product_purchase->tax_rate = $tax[$key]['rate'];
            $product_purchase->tax = number_format((float)$product_tax, 2, '.', '');
            $product_purchase->total = number_format((float)$total, 2, '.', '');
            $product_purchase->save();
            $lims_purchase_data->total_qty += $qty[$key];
            $lims_purchase_data->total_discount += $discount[$key] * $qty[$key];
            $lims_purchase_data->total_tax += number_format((float)$product_tax, 2, '.', '');
            $lims_purchase_data->total_cost += number_format((float)$total, 2, '.', '');
        }
        $lims_purchase_data->item = $key + 1;
        $lims_purchase_data->order_tax = ($lims_purchase_data->total_cost - $lims_purchase_data->order_discount) * ($data['order_tax_rate'] / 100);
        $lims_purchase_data->grand_total = ($lims_purchase_data->total_cost + $lims_purchase_data->order_tax + $lims_purchase_data->shipping_cost) - $lims_purchase_data->order_discount;
        $lims_purchase_data->save();
        return redirect('purchases');
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('purchases-edit')) {
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_product_list_without_variant = $this->productWithoutVariant();
            $lims_product_list_with_variant = $this->productWithVariant();
            $lims_purchase_data = Purchase::find($id);
            if (!empty($lims_purchase_data)) {
                // $manufacturer = Manufacturer::where('manuId',$lims_purchase_data->manufacture_id)->first();
                // // dd($manufacturer);
                // $model = ModelSeries::where('modelId',$lims_purchase_data->model_id)->first();
                // $engine = LinkageTarget::where('linkageTargetId',$lims_purchase_data->engine_id)->first();
                // $section = AssemblyGroupNode::where('assemblyGroupNodeId',$lims_purchase_data->section_id)->first();
                // $section_part = Article::where('legacyArticleId',$lims_purchase_data->section_part_id)->first();
                // $supplier = Ambrand::where('BrandId',$lims_purchase_data->supplier_id)->first();

                // dd($lims_purchase_data);
                $lims_product_purchase_data = ProductPurchase::where('purchase_id', $id)->get();

                return view('purchase.edit', compact('lims_warehouse_list', 'lims_supplier_list', 'lims_product_list_without_variant', 'lims_product_list_with_variant', 'lims_tax_list', 'lims_purchase_data', 'lims_product_purchase_data'));
            } else {
                return back();
            }
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
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
            $document->move('public/purchase/documents', $documentName);
            $data['document'] = $documentName;
        }
        //return dd($data);
        $balance = $data['grand_total'] - $data['paid_amount'];
        if ($balance < 0 || $balance > 0) {
            $data['payment_status'] = 1;
        } else {
            $data['payment_status'] = 2;
        }
        $lims_purchase_data = Purchase::find($id);
        $lims_product_purchase_data = ProductPurchase::where('purchase_id', $id)->get();

        $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $recieved = $data['recieved'];
        $batch_no = $data['batch_no'];
        $expired_date = $data['expired_date'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $imei_number = $new_imei_number = $data['imei_number'];
        $product_purchase = [];

        foreach ($lims_product_purchase_data as $product_purchase_data) {

            $old_recieved_value = $product_purchase_data->recieved;
            $lims_purchase_unit_data = Unit::find($product_purchase_data->purchase_unit_id);

            if ($lims_purchase_unit_data->operator == '*') {
                $old_recieved_value = $old_recieved_value * $lims_purchase_unit_data->operation_value;
            } else {
                $old_recieved_value = $old_recieved_value / $lims_purchase_unit_data->operation_value;
            }
            $lims_product_data = Product::find($product_purchase_data->product_id);
            if ($lims_product_data->is_variant) {
                $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProduct($lims_product_data->id, $product_purchase_data->variant_id)->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $lims_product_data->id],
                    ['variant_id', $product_purchase_data->variant_id],
                    ['warehouse_id', $lims_purchase_data->warehouse_id]
                ])->first();
                $lims_product_variant_data->qty -= $old_recieved_value;
                $lims_product_variant_data->save();
            } elseif ($product_purchase_data->product_batch_id) {
                $product_batch_data = ProductBatch::find($product_purchase_data->product_batch_id);
                $product_batch_data->qty -= $old_recieved_value;
                $product_batch_data->save();

                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $product_purchase_data->product_id],
                    ['product_batch_id', $product_purchase_data->product_batch_id],
                    ['warehouse_id', $lims_purchase_data->warehouse_id],
                ])->first();
            } else {
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $product_purchase_data->product_id],
                    ['warehouse_id', $lims_purchase_data->warehouse_id],
                ])->first();
            }
            if ($product_purchase_data->imei_number) {
                $position = array_search($lims_product_data->id, $product_id);
                if ($imei_number[$position]) {
                    $prev_imei_numbers = explode(",", $product_purchase_data->imei_number);
                    $new_imei_numbers = explode(",", $imei_number[$position]);
                    foreach ($prev_imei_numbers as $prev_imei_number) {
                        if (($pos = array_search($prev_imei_number, $new_imei_numbers)) !== false) {
                            unset($new_imei_numbers[$pos]);
                        }
                    }
                    $new_imei_number[$position] = implode(",", $new_imei_numbers);
                }
            }
            $lims_product_data->qty -= $old_recieved_value;
            $lims_product_warehouse_data->qty -= $old_recieved_value;
            $lims_product_warehouse_data->save();
            $lims_product_data->save();
            $product_purchase_data->delete();
        }

        foreach ($product_id as $key => $pro_id) {

            $lims_purchase_unit_data = Unit::where('unit_name', $purchase_unit[$key])->first();
            if ($lims_purchase_unit_data->operator == '*') {
                $new_recieved_value = $recieved[$key] * $lims_purchase_unit_data->operation_value;
            } else {
                $new_recieved_value = $recieved[$key] / $lims_purchase_unit_data->operation_value;
            }

            $lims_product_data = Product::find($pro_id);
            //dealing with product barch
            if ($batch_no[$key]) {
                $product_batch_data = ProductBatch::where([
                    ['product_id', $lims_product_data->id],
                    ['batch_no', $batch_no[$key]]
                ])->first();
                if ($product_batch_data) {
                    $product_batch_data->qty += $new_recieved_value;
                    $product_batch_data->expired_date = $expired_date[$key];
                    $product_batch_data->save();
                } else {
                    $product_batch_data = ProductBatch::create([
                        'product_id' => $lims_product_data->id,
                        'batch_no' => $batch_no[$key],
                        'expired_date' => $expired_date[$key],
                        'qty' => $new_recieved_value
                    ]);
                }
                $product_purchase['product_batch_id'] = $product_batch_data->id;
            } else
                $product_purchase['product_batch_id'] = null;

            if ($lims_product_data->is_variant) {
                $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $lims_product_variant_data->variant_id],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                $product_purchase['variant_id'] = $lims_product_variant_data->variant_id;
                //add quantity to product variant table
                $lims_product_variant_data->qty += $new_recieved_value;
                $lims_product_variant_data->save();
            } else {
                $product_purchase['variant_id'] = null;
                if ($product_purchase['product_batch_id']) {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $pro_id],
                        ['product_batch_id', $product_purchase['product_batch_id']],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $pro_id],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();
                }
            }

            $lims_product_data->qty += $new_recieved_value;
            if ($lims_product_warehouse_data) {
                $lims_product_warehouse_data->qty += $new_recieved_value;
                $lims_product_warehouse_data->save();
            } else {
                $lims_product_warehouse_data = new Product_Warehouse();
                $lims_product_warehouse_data->product_id = $pro_id;
                $lims_product_warehouse_data->product_batch_id = $product_purchase['product_batch_id'];
                if ($lims_product_data->is_variant)
                    $lims_product_warehouse_data->variant_id = $lims_product_variant_data->variant_id;
                $lims_product_warehouse_data->warehouse_id = $data['warehouse_id'];
                $lims_product_warehouse_data->qty = $new_recieved_value;
            }
            //dealing with imei numbers
            if ($imei_number[$key]) {
                if ($lims_product_warehouse_data->imei_number) {
                    $lims_product_warehouse_data->imei_number .= ',' . $new_imei_number[$key];
                } else {
                    $lims_product_warehouse_data->imei_number = $new_imei_number[$key];
                }
            }

            $lims_product_data->save();
            $lims_product_warehouse_data->save();

            $product_purchase['purchase_id'] = $id;
            $product_purchase['product_id'] = $pro_id;
            $product_purchase['qty'] = $qty[$key];
            $product_purchase['recieved'] = $recieved[$key];
            $product_purchase['purchase_unit_id'] = $lims_purchase_unit_data->id;
            $product_purchase['net_unit_cost'] = $net_unit_cost[$key];
            $product_purchase['discount'] = $discount[$key];
            $product_purchase['tax_rate'] = $tax_rate[$key];
            $product_purchase['tax'] = $tax[$key];
            $product_purchase['total'] = $total[$key];
            $product_purchase['imei_number'] = $imei_number[$key];
            ProductPurchase::create($product_purchase);
        }

        $lims_purchase_data->update($data);
        return redirect('purchases')->with('message', 'Purchase updated successfully');
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        $lims_purchase_data = Purchase::find($data['purchase_id']);
        $lims_purchase_data->paid_amount += $data['amount'];
        $balance = $lims_purchase_data->grand_total - $lims_purchase_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_purchase_data->payment_status = 2;
        $lims_purchase_data->save();

        if ($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        elseif ($data['paid_by_id'] == 2)
            $paying_method = 'Gift Card';
        elseif ($data['paid_by_id'] == 3)
            $paying_method = 'Credit Card';
        else
            $paying_method = 'Cheque';

        $lims_payment_data = new Payment();
        $lims_payment_data->user_id = Auth::id();
        $lims_payment_data->purchase_id = $lims_purchase_data->id;
        $lims_payment_data->account_id = $data['account_id'];
        $lims_payment_data->payment_reference = 'ppr-' . date("Ymd") . '-' . date("his");
        $lims_payment_data->amount = $data['amount'];
        $lims_payment_data->change = $data['paying_amount'] - $data['amount'];
        $lims_payment_data->paying_method = $paying_method;
        $lims_payment_data->payment_note = $data['payment_note'];
        $lims_payment_data->save();

        $lims_payment_data = Payment::latest()->first();
        $data['payment_id'] = $lims_payment_data->id;

        if ($paying_method == 'Credit Card') {
            $lims_pos_setting_data = PosSetting::latest()->first();
            Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            // Charge the Customer
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $token,
            ]);

            $data['charge_id'] = $charge->id;
            PaymentWithCreditCard::create($data);
        } elseif ($paying_method == 'Cheque') {
            PaymentWithCheque::create($data);
        }
        return redirect('purchases')->with('message', 'Payment created successfully');
    }

    public function getPayment($id)
    {
        $lims_payment_list = Payment::where('purchase_id', $id)->get();
        $date = [];
        $payment_reference = [];
        $paid_amount = [];
        $paying_method = [];
        $payment_id = [];
        $payment_note = [];
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
            if ($payment->paying_method == 'Cheque') {
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                $cheque_no[] = $lims_payment_cheque_data->cheque_no;
            } else {
                $cheque_no[] = null;
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
        $payments[] = $change;
        $payments[] = $paying_amount;
        $payments[] = $account_name;
        $payments[] = $account_id;

        return $payments;
    }

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        $lims_payment_data = Payment::find($data['payment_id']);
        $lims_purchase_data = Purchase::find($lims_payment_data->purchase_id);
        //updating purchase table
        $amount_dif = $lims_payment_data->amount - $data['edit_amount'];
        $lims_purchase_data->paid_amount = $lims_purchase_data->paid_amount - $amount_dif;
        $balance = $lims_purchase_data->grand_total - $lims_purchase_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_purchase_data->payment_status = 2;
        $lims_purchase_data->save();

        //updating payment data
        $lims_payment_data->account_id = $data['account_id'];
        $lims_payment_data->amount = $data['edit_amount'];
        $lims_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $lims_payment_data->payment_note = $data['edit_payment_note'];
        if ($data['edit_paid_by_id'] == 1)
            $lims_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2)
            $lims_payment_data->paying_method = 'Gift Card';
        elseif ($data['edit_paid_by_id'] == 3) {
            $lims_pos_setting_data = PosSetting::latest()->first();
            \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['edit_amount'];
            if ($lims_payment_data->paying_method == 'Credit Card') {
                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $lims_payment_data->id)->first();

                \Stripe\Refund::create(array(
                    "charge" => $lims_payment_with_credit_card_data->charge_id,
                ));

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $lims_payment_with_credit_card_data->charge_id = $charge->id;
                $lims_payment_with_credit_card_data->save();
            } else {
                // Charge the Customer
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $lims_payment_data->paying_method = 'Credit Card';
        } else {
            if ($lims_payment_data->paying_method == 'Cheque') {
                $lims_payment_data->paying_method = 'Cheque';
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $lims_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $lims_payment_cheque_data->save();
            } else {
                $lims_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                PaymentWithCheque::create($data);
            }
        }
        $lims_payment_data->save();
        return redirect('purchases')->with('message', 'Payment updated successfully');
    }

    public function deletePayment(Request $request)
    {
        $lims_payment_data = Payment::find($request['id']);
        $lims_purchase_data = Purchase::where('id', $lims_payment_data->purchase_id)->first();
        $lims_purchase_data->paid_amount -= $lims_payment_data->amount;
        $balance = $lims_purchase_data->grand_total - $lims_purchase_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $lims_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_purchase_data->payment_status = 2;
        $lims_purchase_data->save();

        if ($lims_payment_data->paying_method == 'Credit Card') {
            $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            $lims_pos_setting_data = PosSetting::latest()->first();
            \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
                "charge" => $lims_payment_with_credit_card_data->charge_id,
            ));

            $lims_payment_with_credit_card_data->delete();
        } elseif ($lims_payment_data->paying_method == 'Cheque') {
            $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $request['id'])->first();
            $lims_payment_cheque_data->delete();
        }
        $lims_payment_data->delete();
        return redirect('purchases')->with('not_permitted', 'Payment deleted successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $purchase_id = $request['purchaseIdArray'];
        foreach ($purchase_id as $id) {
            $lims_purchase_data = Purchase::find($id);
            $lims_product_purchase_data = ProductPurchase::where('purchase_id', $id)->get();
            $lims_payment_data = Payment::where('purchase_id', $id)->get();
            foreach ($lims_product_purchase_data as $product_purchase_data) {
                $lims_purchase_unit_data = Unit::find($product_purchase_data->purchase_unit_id);
                if ($lims_purchase_unit_data->operator == '*')
                    $recieved_qty = $product_purchase_data->recieved * $lims_purchase_unit_data->operation_value;
                else
                    $recieved_qty = $product_purchase_data->recieved / $lims_purchase_unit_data->operation_value;

                $lims_product_data = Product::find($product_purchase_data->product_id);
                if ($product_purchase_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_purchase_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($product_purchase_data->product_id, $product_purchase_data->variant_id, $lims_purchase_data->warehouse_id)
                        ->first();
                    $lims_product_variant_data->qty -= $recieved_qty;
                    $lims_product_variant_data->save();
                } elseif ($product_purchase_data->product_batch_id) {
                    $lims_product_batch_data = ProductBatch::find($product_purchase_data->product_batch_id);
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_batch_id', $product_purchase_data->product_batch_id],
                        ['warehouse_id', $lims_purchase_data->warehouse_id]
                    ])->first();

                    $lims_product_batch_data->qty -= $recieved_qty;
                    $lims_product_batch_data->save();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($product_purchase_data->product_id, $lims_purchase_data->warehouse_id)
                        ->first();
                }

                $lims_product_data->qty -= $recieved_qty;
                $lims_product_warehouse_data->qty -= $recieved_qty;

                $lims_product_warehouse_data->save();
                $lims_product_data->save();
                $product_purchase_data->delete();
            }
            foreach ($lims_payment_data as $payment_data) {
                if ($payment_data->paying_method == "Cheque") {
                    $payment_with_cheque_data = PaymentWithCheque::where('payment_id', $payment_data->id)->first();
                    $payment_with_cheque_data->delete();
                } elseif ($payment_data->paying_method == "Credit Card") {
                    $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                    $lims_pos_setting_data = PosSetting::latest()->first();
                    \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
                    \Stripe\Refund::create(array(
                        "charge" => $payment_with_credit_card_data->charge_id,
                    ));

                    $payment_with_credit_card_data->delete();
                }
                $payment_data->delete();
            }

            $lims_purchase_data->delete();
        }
        return 'Purchase deleted successfully!';
    }

    public function destroy($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('purchases-delete')) {
            $lims_purchase_data = Purchase::find($id);
            $lims_product_purchase_data = ProductPurchase::where('purchase_id', $id)->get();
            $lims_payment_data = Payment::where('purchase_id', $id)->get();
            foreach ($lims_product_purchase_data as $product_purchase_data) {
                $lims_purchase_unit_data = Unit::find($product_purchase_data->purchase_unit_id);
                if ($lims_purchase_unit_data->operator == '*')
                    $recieved_qty = $product_purchase_data->recieved * $lims_purchase_unit_data->operation_value;
                else
                    $recieved_qty = $product_purchase_data->recieved / $lims_purchase_unit_data->operation_value;

                $lims_product_data = Product::find($product_purchase_data->product_id);
                if ($product_purchase_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_purchase_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithVariant($product_purchase_data->product_id, $product_purchase_data->variant_id, $lims_purchase_data->warehouse_id)
                        ->first();
                    $lims_product_variant_data->qty -= $recieved_qty;
                    $lims_product_variant_data->save();
                } elseif ($product_purchase_data->product_batch_id) {
                    $lims_product_batch_data = ProductBatch::find($product_purchase_data->product_batch_id);
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_batch_id', $product_purchase_data->product_batch_id],
                        ['warehouse_id', $lims_purchase_data->warehouse_id]
                    ])->first();

                    $lims_product_batch_data->qty -= $recieved_qty;
                    $lims_product_batch_data->save();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::FindProductWithoutVariant($product_purchase_data->product_id, $lims_purchase_data->warehouse_id)
                        ->first();
                }
                //deduct imei number if available
                if ($product_purchase_data->imei_number) {
                    $imei_numbers = explode(",", $product_purchase_data->imei_number);
                    $all_imei_numbers = explode(",", $lims_product_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $lims_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }

                $lims_product_data->qty -= $recieved_qty;
                $lims_product_warehouse_data->qty -= $recieved_qty;

                $lims_product_warehouse_data->save();
                $lims_product_data->save();
                $product_purchase_data->delete();
            }
            foreach ($lims_payment_data as $payment_data) {
                if ($payment_data->paying_method == "Cheque") {
                    $payment_with_cheque_data = PaymentWithCheque::where('payment_id', $payment_data->id)->first();
                    $payment_with_cheque_data->delete();
                } elseif ($payment_data->paying_method == "Credit Card") {
                    $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                    $lims_pos_setting_data = PosSetting::latest()->first();
                    \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
                    \Stripe\Refund::create(array(
                        "charge" => $payment_with_credit_card_data->charge_id,
                    ));

                    $payment_with_credit_card_data->delete();
                }
                $payment_data->delete();
            }

            $lims_purchase_data->delete();
            return redirect('purchases')->with('not_permitted', 'Purchase deleted successfully');;
        }
    }

    public function getManufacturersByEngineType(Request $request)
    {
        try {
            $manufacturers = Manufacturer::where('linkingTargetType', $request->engine_sub_type)->get();
            // dd($manufacturers);
            return response()->json([
                'data' => $manufacturers
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getModelsByManufacturer(Request $request)
    {
        try {

            $models = ModelSeries::select('modelId', 'modelname')->where('manuId', $request->manufacturer_id)
                ->where('linkingTargetType', $request->engine_sub_type)->get();
            // dd($models);
            return response()->json([
                'data' => $models
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEnginesByModel(Request $request)
    {
        try {
            $engines = LinkageTarget::select('linkageTargetId', 'description', 'beginYearMonth', 'endYearMonth')
                ->where('vehicleModelSeriesId', $request->model_id)
                ->where('linkageTargetType', $request->engine_sub_type)->get();
            // dd($models);
            return response()->json([
                'data' => $engines
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSectionsByEngine(Request $request)
    {
        try {
            $sections = AssemblyGroupNode::groupBy('assemblyGroupNodeId')->whereHas('articleVehicleTree', function($query) use ($request){
                    $query->where('linkingTargetId', $request->engine_id)
                    ->where('linkingTargetType', $request->engine_sub_type);
                })
               ->groupBy('assemblyGroupNodeId')
               ->limit(100)
                ->get();
            return response()->json([
                'data' => $sections
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSectionParts(Request $request)
    {
        try {
            $section_parts = Article::select('legacyArticleId', 'dataSupplierId', 'genericArticleDescription', 'articleNumber')->whereHas('articleVehicleTree', function ($query) use ($request) {
                $query->where('linkingTargetType', $request->engine_sub_type)->where('assemblyGroupNodeId', $request->section_id);
            })
            ->limit(100)
            ->get();

            // dd($section_parts);
            return response()->json([
                'data' => $section_parts
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function articlesByReferenceNo(Request $request)
    {
        try { 
            
            $articles = Article::where('articleNumber','LIKE' , '%' . $request->name . '%')->paginate(10);
            // dd($articles);
            return response()->json([
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    

    public function getBrandsBySectionPart(Request $request)
    {
        try {
            $id = explode('-', $request->section_part_id);
            $suppliers = Ambrand::select('brandId', 'brandName')
                ->where('brandId', $id[0])->get();
            return response()->json([
                'data' => $suppliers
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function showSectionParts(Request $request)
    {
        // dd($request->all());
        $id = explode('-', $request->id);
        $section_part_id = explode('-', $request->section_part_id);

        // dd($id);
        $product = Article::where('dataSupplierId', $id[0])->where('legacyArticleId', $id[1])->first();
        // dd($product);
        return response()->json([
            'data' => $product,
            'supplier' => $request->supplier_id,
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
            'brand_id' => $request->brand_id,
        ]);
    }

    public function getArticleInfo(Request $request)
    {
        try {
            
            $section_part = Article::select('mfrId', 'assemblyGroupNodeId','legacyArticleId', 'dataSupplierId', 'genericArticleDescription', 'articleNumber')->whereHas('articleVehicleTree', function ($query) use ($request) {
                $query->where('articleNumber', 'LIKE' , '%' . $request->name . '%');
            })->with(['section' => function($query) {
                $query->whereNotNull('request__linkingTargetType');
                $query->select(['assemblyGroupNodeId', 'assemblyGroupName', 'request__linkingTargetId', 'request__linkingTargetType']);
                $query->whereHas('linkageTarget');
            }])->first();
            
            if(empty($section_part->section)){
                return response()->json([
                    'data' => 0,
                    "message" => "section not available for this product"
                ]);
            }

            $model = ModelSeries::where('manuId',$section_part->mfrId)->first();
            $p_type = ['V','L','B'];
            $o_type = ['M','A','K','C','T'];
            $type = "";
            if(in_array($section_part->section->request__linkingTargetType,$p_type)){
                $type = "P";
            }else{
                $type = "O";
            }
            
            return response()->json([
                'data' => $section_part,
                'supplier' => $request->supplier_id,
                'linkage_target_type' => $type, // engine_type
                'linkage_target_sub_type' => $section_part->section->request__linkingTargetType, //
                'manufacturer_id' => $section_part->mfrId,
                'model_id' => $model->modelId,
                'engine_id' => $section_part->section->request__linkingTargetId,
                'section_id' => $request->section_id,
                'status' => $request->status,
                'date' => $request->date,
                'cash_type' => $request->cash_type,
                'brand_id' => $section_part->dataSupplierId,
            ], 200);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
