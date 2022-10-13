<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\ReturnPurchase;
use App\ProductPurchase;
use App\Purchase;
use App\Expense;
use App\Payroll;
use App\Quotation;
use App\Payment;
use App\Account;
use App\Form;
use App\Product_Sale;
use App\Customer;
use App\Product;
use App\RewardPointSetting;
use DB;
use Auth;
use Printing;
use Rawilk\Printing\Contracts\Printer;
use Spatie\Permission\Models\Role;
use Session;
use App\FormUser;
use App\Models\AfterMarkitSupplier;
use App\Models\NewSale;
use App\Models\StockManagement;

/*use vendor\autoload;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;*/

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function logOut()
    {
        session::flush();
        return redirect('/login');//->with('message','You are not Allowed to access the system');
    }

    public function dashboard()
    {
        // dd('dashboard');
        $user = auth()->user();
        if($user->is_active != 1){
            return redirect('logout')->with('message','You are not Allowed to access the system');
        }
        
        return view('home');
        
    }

    public function sendsms()
    {
        $apiToken = "kisob-67d9542b-3e4e-45ed-b8aa-3061cc79d9e9";
        $sid = "KISOBBRANDAPI";
        $msisdn = "01741202865";
        $messageBody = "Hello Ashfaq! This is a test sms. Do not reply.";
        $csmsId = "2934fe343";

        $params = [
            "api_token" => $apiToken,
            "sid" => $sid,
            "msisdn" => $msisdn,
            "sms" => $messageBody,
            "csms_id" => $csmsId
        ];
        
        $url = "https://smsplus.sslwireless.com/api/v3/send-sms";
        $params = json_encode($params);

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params),
            'accept:application/json'
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;

        //return 'send sms';
    }

    public function index()
    {
        
        //return phpinfo();
        //return Printing::printers();
        /*$printerId = '69993185';
        $content = 'Hello world';
        $printJob = Printing::newPrintTask()
        ->printer($printerId)
        ->content($content)
        ->send();*/
        //return 'printed successfully';
        /*$connector = new NetworkPrintConnector("192.168.1.87",9100);
        //return dd($connector);
        $printer = new Printer($connector);
        try {
            $printer -> text("Hello World");
        } finally {
            $printer -> close();
        }*/
        if(Auth::user()->role_id == 5) {
            $customer = Customer::select('id', 'points')->where('user_id', Auth::id())->first();
            $lims_sale_data = Sale::with('warehouse')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
            $lims_payment_data = DB::table('payments')
                           ->join('sales', 'payments.sale_id', '=', 'sales.id')
                           ->where('customer_id', $customer->id)
                           ->select('payments.*', 'sales.reference_no as sale_reference')
                           ->orderBy('payments.created_at', 'desc')
                           ->get();
            $lims_quotation_data = Quotation::with('biller', 'customer', 'supplier', 'user')->orderBy('id', 'desc')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();

            $lims_return_data = Returns::with('warehouse', 'customer', 'biller')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
            $lims_reward_point_setting_data = RewardPointSetting::select('per_point_amount')->latest()->first();
            return view('customer_index', compact('customer', 'lims_sale_data', 'lims_payment_data', 'lims_quotation_data', 'lims_return_data', 'lims_reward_point_setting_data'));
        }
        $role = Role::find(Auth::user()->role_id);
        $permissions = Role::findByName($role->name)->permissions;
        foreach ($permissions as $permission)
            $all_permission[] = $permission->name;
        if(empty($all_permission))
            $all_permission[] = 'dummy text';

        $start_date = date("Y").'-'.date("m").'-'.'01';
        $end_date = date("Y").'-'.date("m").'-'.date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
        $yearly_sale_amount = [];

        $general_setting = DB::table('general_settings')->latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            $product_sale_data = Sale::join('product_sales', 'sales.id','=', 'product_sales.sale_id')
                ->select(DB::raw('product_sales.product_id, product_sales.product_batch_id, sum(product_sales.qty) as sold_qty, sum(product_sales.total) as sold_amount'))
                ->where('sales.user_id', Auth::id())
                ->whereDate('product_sales.created_at', '>=' , $start_date)
                ->whereDate('product_sales.created_at', '<=' , $end_date)
                ->groupBy('product_sales.product_id', 'product_sales.product_batch_id')
                ->get();

            $product_cost = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                $product_data = Product::select('type', 'product_list', 'variant_list', 'qty_list')->find($product_sale->product_id);
                if($product_data->type == 'combo') {
                    $product_list = explode(",", $product_data->product_list);
                    if($product_data->variant_list)
                        $variant_list = explode(",", $product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $product_data->qty_list);

                    foreach ($product_list as $index => $product_id) {
                        if(count($variant_list) && $variant_list[$index]) {
                            $product_purchase_data = ProductPurchase::where([
                                ['product_id', $product_id],
                                ['variant_id', $variant_list[$index] ]
                            ])->get();
                        }
                        else
                            $product_purchase_data = ProductPurchase::where('product_id', $product_id)->get();

                        $purchased_qty = 0;
                        $purchased_amount = 0;
                        $sold_qty = $product_sale->sold_qty * $qty_list[$index];
                        
                        foreach ($product_purchase_data as $product_purchase) {
                            $purchased_qty += $product_purchase->qty;
                            $purchased_amount += $product_purchase->total;
                            if($purchased_qty >= $sold_qty) {
                                $qty_diff = $purchased_qty - $sold_qty;
                                $unit_cost = $product_purchase->total / $product_purchase->qty;
                                $purchased_amount -= ($qty_diff * $unit_cost);
                                break;
                            }
                        }
                        $product_cost += $purchased_amount;
                    }
                }
                else {
                    if($product_sale->product_batch_id)
                        $product_purchase_data = ProductPurchase::where([
                            ['product_id', $product_sale->product_id],
                            ['product_batch_id', $product_sale->product_batch_id]
                        ])->get();
                    else
                        $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();

                    $purchased_qty = 0;
                    $purchased_amount = 0;
                    $sold_qty = $product_sale->sold_qty;
                    foreach ($product_purchase_data as $key => $product_purchase) {
                        $purchased_qty += $product_purchase->qty;
                        $purchased_amount += $product_purchase->total;
                        if($purchased_qty >= $sold_qty) {
                            $qty_diff = $purchased_qty - $sold_qty;
                            $unit_cost = $product_purchase->total / $product_purchase->qty;
                            $purchased_amount -= ($qty_diff * $unit_cost);
                            break;
                        }
                    }
                    $product_cost += $purchased_amount;
                }
            }

            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue = $revenue - $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue + $purchase_return - $product_cost;
            $expense = Expense::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('amount');
            $recent_sale = Sale::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
        }
        else {
            $product_sale_data = Product_Sale::select(DB::raw('product_id, product_batch_id, sum(qty) as sold_qty, sum(total) as sold_amount'))->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->groupBy('product_id', 'product_batch_id')->get();

            $product_cost = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                $product_data = Product::select('type', 'product_list', 'variant_list', 'qty_list')->find($product_sale->product_id);
                if($product_data->type == 'combo') {
                    $product_list = explode(",", $product_data->product_list);
                    if($product_data->variant_list)
                        $variant_list = explode(",", $product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $product_data->qty_list);

                    foreach ($product_list as $index => $product_id) {
                        if(count($variant_list) && $variant_list[$index]) {
                            $product_purchase_data = ProductPurchase::where([
                                ['product_id', $product_id],
                                ['variant_id', $variant_list[$index] ]
                            ])->get();
                        }
                        else
                            $product_purchase_data = ProductPurchase::where('product_id', $product_id)->get();

                        $purchased_qty = 0;
                        $purchased_amount = 0;
                        $sold_qty = $product_sale->sold_qty * $qty_list[$index];
                        
                        foreach ($product_purchase_data as $product_purchase) {
                            $purchased_qty += $product_purchase->qty;
                            $purchased_amount += $product_purchase->total;
                            if($purchased_qty >= $sold_qty) {
                                $qty_diff = $purchased_qty - $sold_qty;
                                $unit_cost = $product_purchase->total / $product_purchase->qty;
                                $purchased_amount -= ($qty_diff * $unit_cost);
                                break;
                            }
                        }
                        $product_cost += $purchased_amount;
                    }
                }
                else {
                    if($product_sale->product_batch_id)
                        $product_purchase_data = ProductPurchase::where([
                            ['product_id', $product_sale->product_id],
                            ['product_batch_id', $product_sale->product_batch_id]
                        ])->get();
                    else
                        $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();

                    $purchased_qty = 0;
                    $purchased_amount = 0;
                    $sold_qty = $product_sale->sold_qty;
                    foreach ($product_purchase_data as $key => $product_purchase) {
                        $purchased_qty += $product_purchase->qty;
                        $purchased_amount += $product_purchase->total;
                        if($purchased_qty >= $sold_qty) {
                            $qty_diff = $purchased_qty - $sold_qty;
                            $unit_cost = $product_purchase->total / $product_purchase->qty;
                            $purchased_amount -= ($qty_diff * $unit_cost);
                            break;
                        }
                    }
                    $product_cost += $purchased_amount;
                }
            }

            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue = $revenue - $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue + $purchase_return - $product_cost;
            $expense = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
            $recent_sale = Sale::orderBy('id', 'desc')->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->take(5)->get();
        }

        $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_price = Product_Sale::select(DB::raw('product_id, sum(total) as total_price'))->whereDate('created_at', '>=' , date("Y").'-01-01')->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('total_price', 'desc')->take(5)->get();

        //cash flow of last 6 months
        $start = strtotime(date('Y-m-01', strtotime('-6 month', strtotime(date('Y-m-d') ))));
        $end = strtotime(date('Y-m-'.date('t', mktime(0, 0, 0, date("m"), 1, date("Y")))));

        while($start < $end)
        {
            $start_date = date("Y-m", $start).'-'.'01';
            $end_date = date("Y-m", $start).'-'.date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));

            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $return_amount = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
            }
            else {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $return_amount = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
            }
            $sent_amount = $sent_amount + $return_amount + $expense_amount + $payroll_amount;
            
            $payment_recieved[] = number_format((float)($recieved_amount + $purchase_return_amount), 2, '.', '');
            $payment_sent[] = number_format((float)$sent_amount, 2, '.', '');
            $month[] = date("F", strtotime($start_date));
            $start = strtotime("+1 month", $start);
        }
        // yearly report
        $start = strtotime(date("Y") .'-01-01');
        $end = strtotime(date("Y") .'-12-31');
        while($start < $end)
        {
            $start_date = date("Y").'-'.date('m', $start).'-'.'01';
            $end_date = date("Y").'-'.date('m', $start).'-'.date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $sale_amount = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            }
            else{
                $sale_amount = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            }
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
            $yearly_purchase_amount[] = number_format((float)$purchase_amount, 2, '.', '');
            $start = strtotime("+1 month", $start);
        }
        //return $month;
        $user_form_status = FormUser::where('user_id', auth()->user()->id)->latest()->first();
        
        // dd($user_form_status);
        if($user_form_status)
        {
            if($user_form_status->status != 1)
            {
                return redirect('formMessage');
            }
        }
        $supplier_count = AfterMarkitSupplier::where('retailer_id',auth()->user()->id)->count();
        $purchase_count = Purchase::where('user_id',auth()->user()->id)->whereNULL('deleted_at')->count();
        $sale_count = NewSale::where('retailer_id',auth()->user()->id)->whereNULL('deleted_at')->count();
        $stock_count = StockManagement::where('retailer_id',auth()->user()->id)->whereNULL('deleted_at')->count();

        return view('index', compact('stock_count','sale_count','purchase_count','supplier_count','revenue', 'purchase', 'expense', 'purchase_return','return', 'profit', 'payment_recieved', 'payment_sent', 'month', 'yearly_sale_amount', 'yearly_purchase_amount', 'recent_sale', 'recent_purchase', 'recent_quotation', 'recent_payment', 'best_selling_qty', 'yearly_best_selling_qty', 'yearly_best_selling_price', 'all_permission'));
    }

    public function approvedDashboard($noti_id=null)
    {
        $notis = auth()->user()->unreadNotifications;
        foreach($notis as $n){
            if($n->id == $noti_id){
                $n->markAsRead();
            }
        }
        return redirect('/');
    }

    public function dashboardFilter($start_date, $end_date)
    {
        $general_setting = DB::table('general_settings')->latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            $product_sale_data = Sale::join('product_sales', 'sales.id','=', 'product_sales.sale_id')
                ->select(DB::raw('product_sales.product_id, product_sales.product_batch_id, sum(product_sales.qty) as sold_qty, sum(product_sales.total) as sold_amount'))
                ->where('sales.user_id', Auth::id())
                ->whereDate('product_sales.created_at', '>=' , $start_date)
                ->whereDate('product_sales.created_at', '<=' , $end_date)
                ->groupBy('product_sales.product_id', 'product_sales.product_batch_id')
                ->get();

            $product_cost = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                $product_data = Product::select('type', 'product_list', 'variant_list', 'qty_list')->find($product_sale->product_id);
                if($product_data->type == 'combo') {
                    $product_list = explode(",", $product_data->product_list);
                    if($product_data->variant_list)
                        $variant_list = explode(",", $product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $product_data->qty_list);

                    foreach ($product_list as $index => $product_id) {
                        if(count($variant_list) && $variant_list[$index]) {
                            $product_purchase_data = ProductPurchase::where([
                                ['product_id', $product_id],
                                ['variant_id', $variant_list[$index] ]
                            ])->get();
                        }
                        else
                            $product_purchase_data = ProductPurchase::where('product_id', $product_id)->get();

                        $purchased_qty = 0;
                        $purchased_amount = 0;
                        $sold_qty = $product_sale->sold_qty * $qty_list[$index];
                        
                        foreach ($product_purchase_data as $product_purchase) {
                            $purchased_qty += $product_purchase->qty;
                            $purchased_amount += $product_purchase->total;
                            if($purchased_qty >= $sold_qty) {
                                $qty_diff = $purchased_qty - $sold_qty;
                                $unit_cost = $product_purchase->total / $product_purchase->qty;
                                $purchased_amount -= ($qty_diff * $unit_cost);
                                break;
                            }
                        }
                        $product_cost += $purchased_amount;
                    }
                }
                else {
                    if($product_sale->product_batch_id)
                        $product_purchase_data = ProductPurchase::where([
                            ['product_id', $product_sale->product_id],
                            ['product_batch_id', $product_sale->product_batch_id]
                        ])->get();
                    else
                        $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();

                    $purchased_qty = 0;
                    $purchased_amount = 0;
                    $sold_qty = $product_sale->sold_qty;
                    foreach ($product_purchase_data as $key => $product_purchase) {
                        $purchased_qty += $product_purchase->qty;
                        $purchased_amount += $product_purchase->total;
                        if($purchased_qty >= $sold_qty) {
                            $qty_diff = $purchased_qty - $sold_qty;
                            $unit_cost = $product_purchase->total / $product_purchase->qty;
                            $purchased_amount -= ($qty_diff * $unit_cost);
                            break;
                        }
                    }
                    $product_cost += $purchased_amount;
                }
            }

            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $revenue -= $return;
            $profit = $revenue + $purchase_return - $product_cost;

            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            $data[3] = $purchase_return;
        }
        else{
            $product_sale_data = Product_Sale::select(DB::raw('product_id, product_batch_id, sum(qty) as sold_qty, sum(total) as sold_amount'))->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->groupBy('product_id', 'product_batch_id')->get();

            $product_cost = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                $product_data = Product::select('type', 'product_list', 'variant_list', 'qty_list')->find($product_sale->product_id);
                if($product_data->type == 'combo') {
                    $product_list = explode(",", $product_data->product_list);
                    if($product_data->variant_list)
                        $variant_list = explode(",", $product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $product_data->qty_list);

                    foreach ($product_list as $index => $product_id) {
                        if(count($variant_list) && $variant_list[$index]) {
                            $product_purchase_data = ProductPurchase::where([
                                ['product_id', $product_id],
                                ['variant_id', $variant_list[$index] ]
                            ])->get();
                        }
                        else
                            $product_purchase_data = ProductPurchase::where('product_id', $product_id)->get();

                        $purchased_qty = 0;
                        $purchased_amount = 0;
                        $sold_qty = $product_sale->sold_qty * $qty_list[$index];
                        
                        foreach ($product_purchase_data as $product_purchase) {
                            $purchased_qty += $product_purchase->qty;
                            $purchased_amount += $product_purchase->total;
                            if($purchased_qty >= $sold_qty) {
                                $qty_diff = $purchased_qty - $sold_qty;
                                $unit_cost = $product_purchase->total / $product_purchase->qty;
                                $purchased_amount -= ($qty_diff * $unit_cost);
                                break;
                            }
                        }
                        $product_cost += $purchased_amount;
                    }
                }
                else {
                    if($product_sale->product_batch_id)
                        $product_purchase_data = ProductPurchase::where([
                            ['product_id', $product_sale->product_id],
                            ['product_batch_id', $product_sale->product_batch_id]
                        ])->get();
                    else
                        $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();

                    $purchased_qty = 0;
                    $purchased_amount = 0;
                    $sold_qty = $product_sale->sold_qty;
                    foreach ($product_purchase_data as $key => $product_purchase) {
                        $purchased_qty += $product_purchase->qty;
                        $purchased_amount += $product_purchase->total;
                        if($purchased_qty >= $sold_qty) {
                            $qty_diff = $purchased_qty - $sold_qty;
                            $unit_cost = $product_purchase->total / $product_purchase->qty;
                            $purchased_amount -= ($qty_diff * $unit_cost);
                            break;
                        }
                    }
                    $product_cost += $purchased_amount;
                }
            }

            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue -= $return;
            $profit = $revenue + $purchase_return - $product_cost;

            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            $data[3] = $purchase_return;
        }
        
        return $data;
    }

    public function myTransaction($year, $month)
    {
        $start = 1;
        $number_of_day = date('t', mktime(0, 0, 0, $month, 1, $year));
        while($start <= $number_of_day)
        {
            if($start < 10)
                $date = $year.'-'.$month.'-0'.$start;
            else
                $date = $year.'-'.$month.'-'.$start;
            $sale_generated[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $sale_grand_total[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_generated[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $purchase_grand_total[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $quotation_generated[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $quotation_grand_total[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $start++;
        }
        $start_day = date('w', strtotime($year.'-'.$month.'-01')) + 1;
        $prev_year = date('Y', strtotime('-1 month', strtotime($year.'-'.$month.'-01')));
        $prev_month = date('m', strtotime('-1 month', strtotime($year.'-'.$month.'-01')));
        $next_year = date('Y', strtotime('+1 month', strtotime($year.'-'.$month.'-01')));
        $next_month = date('m', strtotime('+1 month', strtotime($year.'-'.$month.'-01')));
        return view('user.my_transaction', compact('start_day', 'year', 'month', 'number_of_day', 'prev_year', 'prev_month', 'next_year', 'next_month', 'sale_generated', 'sale_grand_total','purchase_generated', 'purchase_grand_total','quotation_generated', 'quotation_grand_total'));
    }

    public function switchTheme($theme)
    {
        setcookie('theme', $theme, time() + (86400 * 365), "/");
    }
}
