<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\StockManagement;
use App\Purchase;
use App\Repositories\Interfaces\StockManagementInterface;
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
            if (!count($records) > 0) {
                toastr()->error('you have no record in csv!');
                return redirect()->back();
            }
            // Get field names from header column
            $fields = array_map('strtolower', $records[0]);
            // $validator = Validator::make($fields, [
            //     'reference_no' => 'required',
            //     'white_items' => 'required',
            //     'black_items' => 'required',
            //     'unit_actual_price' => 'required',
            //     'unit_sale_price' => 'required',
            // ]);

            // dd("here",$fields,$validator,$validator->fails());
            // if ($validator->fails()) {
                
            //     return redirect()->withErrors($validator)->withInput();
            // }
            // Remove the header column
            array_shift($records);
            $article_with_reference_no = Article::select('articleNumber')->get();
            foreach ($records as $record) {
                if (count($fields) != count($record)) {
                    toastr()->error('csv upload invalid data');
                    return redirect()->back();
                }
                // Decode unwanted html entities
                $record =  array_map("html_entity_decode", $record);
                // Set the field name as key
                $record = array_combine($fields, $record);
                // Get the clean data
                $this->rows[] = $this->clear_encoding_str($record);
            }
            $saved_stock = StockManagement::where('retailer_id', Auth::user()->id)->get();
            // dd($saved_stock);
            $record_save=false;
            foreach ($this->rows as $data) {
                if ($data['reference_no'] == null || $data['reference_no'] == "" || $data['unit_actual_price'] < 0 || $data['unit_sale_price'] < 0) {
                    continue;
                }
                if ($data['white_items'] <= 0 && $data['black_items'] <= 0) {  // incase of the black and white items both are 0 we can ignore the row
                    continue;
                }
                $record_repeat = false;
                foreach ($saved_stock as $key => $stock) {
                    if ($data['reference_no'] == $stock->reference_no) {
                        $record_repeat = true;
                    }
                }
                $article_of_this_reference_not_exist= false;
                foreach ($article_with_reference_no as $key => $reference_no) {
                    if($reference_no->articleNumber != $data['reference_no']){
                       $article_of_this_reference_not_exist = true;
                    }
                }
                if ($record_repeat == true || $article_of_this_reference_not_exist == true) {   /// incase some product (reference no is already exists in the table then we can ignore the record from the csv in storage procedure)
                    continue;
                } else {
                    $legacy_article_id = Article::select('legacyArticleId')->where('articleNumber',$data['reference_no'])->first();
                    // dd($legacy_article_id);
                    StockManagement::create([
                        'retailer_id' => Auth::user()->id,
                        'reference_no' => $data['reference_no'],
                        'white_items' => $data['white_items'],
                        'black_items' => $data['black_items'],
                        'unit_actual_price' => $data['unit_actual_price'],
                        'unit_sale_price' => $data['unit_sale_price'],
                        'total_qty' => $data['white_items'] + $data['black_items'],
                        'product_id' => $legacy_article_id,
                    ]);
                    $record_save = true;
                }
            }
            DB::commit();
            if ($record_save) {
                toastr()->success('csv import successfully');
            }else{
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
