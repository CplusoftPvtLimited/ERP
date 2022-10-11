<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Validation\Rule;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use App\Models\AfterMarkitSupplier;
use Illuminate\Support\Facades\Mail;
use App\Models\Ambrand;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    // public function index()
    // {
    //     $role = Role::find(Auth::user()->role_id);
    //     if($role->hasPermissionTo('suppliers-index')){
    //         $permissions = Role::findByName($role->name)->permissions;
    //         foreach ($permissions as $permission)
    //             $all_permission[] = $permission->name;
    //         if(empty($all_permission))
    //             $all_permission[] = 'dummy text';
    //         $lims_supplier_all = Supplier::where('is_active', true)->get();
    //         return view('supplier.index',compact('lims_supplier_all', 'all_permission'));
    //     }
    //     else
    //         return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    // }

    public function index(){

    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        $suppliers = AfterMarkitSupplier::where('retailer_id',Auth::user()->id)->get();
        if ($role->hasPermissionTo('suppliers-add')) {
            if (request()->ajax()) {
                $all_stocks = AfterMarkitSupplier::where('retailer_id', Auth::user()->id)->orderBy('id', 'desc')
                ->skip(0)->take(100)->get();
                return DataTables::of($all_stocks)
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="row">
                        <div class="col-sm-4">
                             <button class="btn btn-primary btn-sm edit_supplier" type="button"
                                         data-original-title="btn btn-danger btn-xs"
                                         data-id="'. $row["id"] .'" data-name="'. $row["name"] .'" 
                                         data-email="'. $row["email"] .'" data-image="'. $row["image"] .'"
                                         data-shop_name="'. $row["shop_name"] .'" data-phone="'. $row["phone"] .'"
                                         data-address="'. $row["address"] .'" data-city="'. $row["city"] .'"
                                         data-country="'. $row["country"] .'"
                                         title=""><i class="fa fa-edit"></i></button>
                         </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])->make(true);
            }
            return view('supplier.create');
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'shop_name' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'email' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],'phone' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);


        try {
            // Mail::send( 'mail.supplier_create', $lims_supplier_data, function( $message ) use ($lims_supplier_data)
            // {
            //     $message->to( $lims_supplier_data['email'] )->subject( 'New Supplier' );
            // });
            $lims_supplier_data = $request->except('image');
            $lims_supplier_data['is_active'] = true;
            $lims_supplier_data['retailer_id'] = auth()->user()->id;
            $image = $request->image;
            if ($image) {
                $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['shop_name']);
                $imageName = $imageName . '.' . $ext;
                $image->move('public/images/supplier', $imageName);
                $lims_supplier_data['image'] = $imageName;
            }
            AfterMarkitSupplier::create($lims_supplier_data);
            // dd($create);
            // $message ="";
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
            // $message = 'Data inserted successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }
        toastr()->success('supplier create successfully');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'shop_name' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'email' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],'phone' => [
                'max:255',
                Rule::unique('after_markit_suppliers')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ]
        ]);
        try {
            DB::beginTransaction();
            $supplier = AfterMarkitSupplier::findOrFail(request('supplier-id'));
            $data = [
                'name' => request('supplier-name'),
                'email' => request('supplier-email'),
                'phone' => request('supplier-phone'),
                'shop_name' => request('supplier-shop_name'),
                'address' => request('supplier-address'),
                'city' => request('supplier-city'),
                'country' => request('supplier-country'),
            ];
            $supplier->update($data);
            DB::commit();
            toastr()->success('Data updated successfully');
            return redirect()->route('supplier.create');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
        
    }

    public function getSuppliers() // by afzal
    {

        $suppliers = Ambrand::select('brandId', 'brandLogoID', 'brandName')->orderBy('brandName', 'ASC')->distinct()->paginate(100);

        return view('supplier.get', compact('suppliers'));
    }
}
