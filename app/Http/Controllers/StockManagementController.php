<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\SaveCsv;
use App\Models\StockManagement;
use App\Notifications\SendNotification;
use App\Purchase;
use App\Repositories\Interfaces\StockManagementInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Node\FunctionNode;

class StockManagementController extends Controller
{
    private $rows = [];

    // private $stockManagementRepository;

    // public function __construct(StockManagementInterface $stockManagementInterface)
    // {
    //     $this->stockManagementRepository = $stockManagementInterface;
    //     // $this->auth_user = auth()->guard('api')->user();
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function backButtonFunction()
    {
        return view('product.product_index');
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
        Log::debug($request->all());
        $validator = Validator::make($request->all(), [
            'white_items' => 'nullable|min:1',
            'black_items' => 'nullable|min:1',
            'unit_actual_price' => 'nullable|min:1',
            'unit_sale_price' => 'nullable|min:1',
        ]);
        if ($validator->fails()) {
            toastr()->error('make sure you entered valid data!');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $stock = StockManagement::where('id', $request->id)->with(['purchaseProduct'])->first();
            if (!empty($stock)) {
                $white_items = !empty($request->white_items) ? $request->white_items : $stock->white_items;
                $black_items = !empty($request->black_items) ? $request->black_items : $stock->black_items;

                $stock->update([
                    'white_items' => isset($request->white_items) ? $request->white_items : $stock->white_items,
                    'black_items' => isset($request->black_items) ? $request->black_items : $stock->black_items,
                    'unit_actual_price' => isset($request->unit_actual_price) ? $request->unit_actual_price : $stock->unit_actual_price,
                    'unit_sale_price' => isset($request->unit_sale_price) ? $request->unit_sale_price : $stock->unit_sale_price,
                    'total_qty' => $white_items + $black_items
                ]);
                if (!empty($stock->purchaseProduct)) {
                    $stock->purchaseProduct->update([
                        'white_item_qty' => $stock->white_items,
                        'black_item_qty' => $stock->black_items,
                        'actual_price' => $stock->unit_actual_price,
                        'sell_price' => $stock->unit_sale_price,
                        'qty' => $stock->total_qty,
                    ]);
                }
            }

            DB::commit();
            toastr()->success('record updated successfully!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
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
        try {
            Log::debug($id);
            DB::beginTransaction();
            // $stock = StockManagement::where('id', $id)->with(['purchaseProduct' => function ($query) {
            //     $query->with(['purchase']);
            // }])->first();
            // if (!empty($stock)) {
            //     $stock->delete();
            //     if (!empty($stock->purchaseProduct)) {
            //         $stock->purchaseProduct->delete();
            //         if (!empty($stock->purchaseProduct->purchase)) {
            //             $is_stock_and_related_pro_deleted = Purchase::where('id', $stock->purchaseProduct->purchase->id)->with(['productPurchases' => function ($query) {
            //                 $query->where('deleted_at', null);
            //                 $query->with(['productsStock' => function ($query) {
            //                     $query->where('deleted_at', null);
            //                 }]);
            //             }])->first();
            //             if (!empty($is_stock_and_related_pro_deleted) && (empty($is_stock_and_related_pro_deleted->productPurchases) || $is_stock_and_related_pro_deleted->productPurchases->count() == 0)  && empty($is_stock_and_related_pro_deleted->productPurchase->productsStock)) {
            //                  $stock->purchaseProduct->purchase->delete();
            //             }
            //         }
            //         DB::commit();
            //         toastr()->success('stock record deleted successfully!');
            //     }
            // }
            StockManagement::find($id)->delete();
            DB::commit();
            toastr()->success('stock record deleted successfully!');

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }

    public function importCsv(Request $request)
    {
        try {
            Log::debug($request->all());
            DB::beginTransaction();
            $path = $request->file('file')->getRealPath();
            $records = array_map('str_getcsv', file($path));
            if(count($records[0]) != 9){
                toastr()->error('you are not uploading the csv with Proper Columns please check the given sample CSV file!');
                return redirect()->back();
            }
            $lower_case_check = ['reference no','cash type' ,'quantity','unit purchase price of white cash','unit sale price of white cash','unit purchase price of black cash','unit sale price of black cash','tax','margin rate'];
            // Get field names from header column
            $fields = array_map('strtolower', $records[0]);
            foreach ($fields as $key => $record) {
                if($record != $lower_case_check[$key]){
                    toastr()->error('Your Columns header is not matched with the sample of given Csv!');
                    return redirect()->back();
                }
            }
            if (!count($records) > 0) {
                toastr()->error('you have no record in csv!');
                return redirect()->back();
            }
            
            $record_header_in_underscore_case =[];
            $record_header_fields= isset($fields) ? $fields : []; 
            foreach ($record_header_fields as $key => $filed) {
                array_push($record_header_in_underscore_case,str_replace(" ","_",$filed));
            }

            $count = 0;
            $records[0] = $record_header_in_underscore_case;
            array_shift($records);
            $article_with_reference_no = Article::select('articleNumber')->get();
            foreach ($records as $record) {
                if (count($record_header_in_underscore_case) != count($record)) {
                    toastr()->error('csv upload invalid data');
                    return redirect()->back();
                }
                // Decode unwanted html entities
                $record =  array_map("html_entity_decode", $record);
                // Set the field name as key
                $record = array_combine($record_header_in_underscore_case, $record);
                // Get the clean data
                $this->rows[] = $this->clear_encoding_str($record);
            }

            $saved_stock = StockManagement::where('retailer_id', Auth::user()->id)->get();
            $record_save = false;
            $revert_data = [];
            foreach ($this->rows as $data) {
                $reference_exist = false;
                $record_repeat = false;
                if ($data['cash_type'] == "white") {   // checks on white cash
                    if ($data['reference_no'] == null || $data['reference_no'] == ""  || $data['unit_purchase_price_of_white_cash'] < 0 || $data['unit_sale_price_of_white_cash'] < 0 ||  $data['quantity'] < 0 || $data['quantity'] == null) {
                        array_push($revert_data, $data);
                        continue;
                    }
                } elseif ($data['cash_type'] == "black") { // checks on black cash
                    if ($data['reference_no'] == null || $data['reference_no'] == ""  || $data['unit_purchase_price_of_black_cash'] < 0 || $data['unit_sale_price_of_black_cash'] < 0 || $data['quantity'] < 0 || $data['quantity'] == null) {
                        array_push($revert_data, $data);
                        continue;
                    }
                } elseif ($data['cash_type'] == "" || $data['cash_type'] == null) {
                    array_push($revert_data, $data);
                    continue;
                }

                foreach ($article_with_reference_no as $key => $reference_no) {  // if reference is not exists in system then continue
                    if ($data['reference_no'] == $reference_no->articleNumber) {
                        $reference_exist = true;
                    }
                }

                foreach ($saved_stock as $key => $stock) {
                    if ($data['reference_no'] == $stock->reference_no) {
                        $count++;
                        $record_repeat = true;     /// after true add the stock in existing record 
                    }
                }
                $legacy_article_id = Article::select('legacyArticleId')->where('articleNumber', $data['reference_no'])->first();
                $white_items_qty = $black_items_qty = 0;
                    if($data['cash_type'] == "white"){
                        $white_items_qty = (int) $data['quantity'];
                    }elseif ($data['cash_type'] == "black") {
                        $black_items_qty = (int)  $data['quantity'];
                    }
                if ($record_repeat == true && $reference_exist == true) {   
                    /// incase some product (reference no is already exists in the table then we can ignore the record from the csv in storage procedure)
                    $find_stock_on_existing_reference = StockManagement::where('retailer_id', Auth::user()->id)->where('reference_no', $data['reference_no'])->with(['purchaseProduct'])->first();
                    
                    $find_stock_on_existing_reference->update([
                        'purchase_product_id' => !empty($find_stock_on_existing_reference->purchaseProduct) ? $find_stock_on_existing_reference->purchaseProduct->id : null,
                        'white_items' => (int) ($find_stock_on_existing_reference->white_items) + (($white_items_qty > 0) ? ($white_items_qty): 0),
                        'black_items' => (int) ($find_stock_on_existing_reference->black_items) + (($black_items_qty > 0) ? ($black_items_qty): 0),
                        'unit_purchase_price_of_white_cash' => (isset($data['unit_purchase_price_of_white_cash']) && !empty( (int) $data['unit_purchase_price_of_white_cash'])) ? $data['unit_purchase_price_of_white_cash'] : (!empty($find_stock_on_existing_reference->unit_purchase_price_of_white_cash) ? $find_stock_on_existing_reference->unit_purchase_price_of_white_cash : 0),
                        'unit_sale_price_of_white_cash' => (isset($data['unit_sale_price_of_white_cash']) &&  !empty((int) ($data['unit_sale_price_of_white_cash']))) ? $data['unit_sale_price_of_white_cash'] : (!empty($find_stock_on_existing_reference->unit_sale_price_of_white_cash) ? $find_stock_on_existing_reference->unit_sale_price_of_white_cash : 0),
                        'unit_purchase_price_of_black_cash' => (isset($data['unit_purchase_price_of_black_cash']) && !empty((int) $data['unit_purchase_price_of_black_cash'])) ? $data['unit_purchase_price_of_black_cash'] : (!empty($find_stock_on_existing_reference->unit_purchase_price_of_black_cash) ? $find_stock_on_existing_reference->unit_purchase_price_of_black_cash : 0),
                        'unit_sale_price_of_black_cash' => (isset($data['unit_sale_price_of_black_cash']) && !empty((int) $data['unit_sale_price_of_black_cash'])) ? $data['unit_sale_price_of_black_cash'] : (!empty($find_stock_on_existing_reference->unit_sale_price_of_black_cash) ? $find_stock_on_existing_reference->unit_sale_price_of_black_cash : 0),
                        'total_qty' =>  (int) $find_stock_on_existing_reference->total_qty + (($data['cash_type'] == "white") ? (int)$white_items_qty  : 0)  + (($data['cash_type'] == "black") ? (int) $black_items_qty : 0),
                        'vat' => isset($data['vat']) ? $data['vat'] : (!empty($find_stock_on_existing_reference->vat) ? $find_stock_on_existing_reference->vat : 0),
                        'profit_margin' => isset($data['margin_rate']) ? $data['margin_rate'] :  (!empty($find_stock_on_existing_reference->margin_rate) ? $find_stock_on_existing_reference->margin_rate : 0),
                    ]);
                    $record_save = true;
                    DB::commit();
                } elseif ($reference_exist == true) {
                    StockManagement::create([
                        'retailer_id' => Auth::user()->id,
                        'reference_no' => isset($data['reference_no']) ? $data['reference_no'] : null,
                        'white_items' =>  ($data['cash_type'] == "white") ? $white_items_qty : 0,
                        'black_items' => ($data['cash_type'] == "black") ? $black_items_qty : 0,
                        'unit_purchase_price_of_white_cash' => ($data['cash_type'] == "white") ? (isset($data['unit_purchase_price_of_white_cash']) ? $data['unit_purchase_price_of_white_cash'] : 0) : 0,
                        'unit_sale_price_of_white_cash' => ($data['cash_type'] == "white") ? (isset($data['unit_sale_price_of_white_cash']) ? $data['unit_sale_price_of_white_cash'] : 0) : 0,
                        'unit_purchase_price_of_black_cash' => ($data['cash_type'] == "black") ? (isset($data['unit_purchase_price_of_black_cash']) ? $data['unit_purchase_price_of_black_cash'] : 0) : 0,
                        'unit_sale_price_of_black_cash' => ($data['cash_type'] == "black") ? (isset($data['unit_sale_price_of_black_cash']) ? $data['unit_sale_price_of_black_cash'] : 0) : 0,
                        'total_qty' => (($data['cash_type'] == "white") ? (int)$white_items_qty  : 0)  + (($data['cash_type'] == "black") ? (int) $black_items_qty : 0),
                        'vat' => isset($data['tax']) ? $data['tax'] : 0, 
                        'profit_margin' => isset($data['margin_rate']) ? $data['margin_rate'] : 0, 
                        'product_id' => !empty($legacy_article_id->legacyArticleId) ? $legacy_article_id->legacyArticleId : null,
                    ]);
                    $record_save = true;
                    DB::commit();
                } else {
                    array_push($revert_data, $data);
                    DB::commit();
                }
            }
            
            if (!empty($revert_data)) {
                $headers = array(
                    'Content-Type' => 'text/csv'
                );
                // I am storing the csv file in public >> files folder. So that why I am creating files folder
                // if (!File::exists(public_path() . "/files")) {
                //     File::makeDirectory(public_path() . "/files");
                // }
                // creating the download file
                $filename = time() . "_download.csv";
                // $filename =  public_path("files/download.csv");
                $handle = fopen($filename, 'w');
                // adding the first row
                fputcsv($handle, [
                    "reference_no",
                    "cash_type",
                    "quantity",
                    "unit_purchase_price_of_white_cash",
                    "unit_sale_price_of_white_cash",
                    "unit_purchase_price_of_black_cash",
                    "unit_sale_price_of_black_cash",
                    "tax",
                    "margin_rate",
                ]);
                // adding the data from the array
                // dd($data);
                foreach ($revert_data as $key => $data) {
                    fputcsv($handle, [
                        $data['reference_no'],
                        $data['cash_type'],
                        $data['quantity'],
                        $data['unit_purchase_price_of_white_cash'],
                        $data['unit_sale_price_of_white_cash'],
                        $data['unit_purchase_price_of_black_cash'],
                        $data['unit_sale_price_of_black_cash'],
                        $data['tax'],
                        $data['margin_rate'],
                    ]);
                }
                fclose($handle);
                $find_csv_record = SaveCsv::where('user_id', Auth::user()->id)->first();
                if (!empty($find_csv_record)) {
                    $find_csv_record->update([
                        'csv' => $filename
                    ]);
                } else {
                    SaveCsv::Create([
                        'user_id' => Auth::user()->id,
                        'csv' => $filename,
                    ]);
                }
                $csv_url = env('APP_URL') . '/' . $filename;
                $data = [
                    'sender' => Auth::user()->id,
                    'message' => $csv_url,
                ];
                $user = User::find($data['sender']);
                // dd($user);
                $user->notify(new SendNotification($data));
                // dd($check);
            }
            DB::commit();
            if ($record_save) {
                toastr()->success('csv import successfully');
            } else {
                toastr()->error('your data in csv have some issues not import successfully ! probably reference is not match ');
            }
            return redirect()->route('products.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug($th);
            return $th->getMessage();
        }
    }

    private function clear_encoding_str($value)
    {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}
