<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use App\Activity;
use Mail;
use App\Mail\AccountCreation;
use Illuminate\Support\Facades\DB;
use App\Events\FormApprove;
use App\Notifications\SendNotification;


class RetailerRegisterController extends Controller
{

    protected function validator(Request $request)
    {
       $data = $request->all();
       dd($data);
        return Validator::make($data, [
            'shop_name' => 'required|string|max:255|unique:users',
            'name' => [
                'required',
                'max:20',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                    Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            
        ]);
    }

    protected function create(Request $request)
    {
      

        $request->validate([
        'shop_name' => 'required|string|max:255|unique:users',
        'name' => [
            'required',
            'max:20',
            Rule::unique('users')->where(function ($query) {
                return $query->where('is_deleted', false);
            }),
        ],
        'email' => [
            'email',
            'max:255',
                Rule::unique('users')->where(function ($query) {
                return $query->where('is_deleted', false);
            }),
        ],
        
    ]);

    $data = $request->all();
       DB::beginTransaction();
        try {
            // dd($data);
           
                // dd("sdf");
                $data['is_active'] = false;
                // $mailData = [];
                $newuser = User::create([
            // 'name' => $data['name'],
            'name' => $data['name'],
            'shop_name' => $data['shop_name'],
            'email' => $data['email'],
            // 'phone' => $data['phone_number'],
            // 'company_name' => $data['company_name'],
            // 'role_id' => $data['role_id'],
            // 'biller_id' => $data['biller_id'],
            // 'warehouse_id' => $data['warehouse_id'],
            'is_active' => 1,
            'is_deleted' => false,
            'role_id' => $data['role'],
            'password' => bcrypt("123456789"),
        ]);
        
           
        $mailData = [
            'title' => 'Mail from ERP',
            'body' => 'Your Account Credentials.',
            'name' => $data['name'],
        ];

        $log = new Activity();
        $log->log_name = $data['name'];
        $log->subject_type = "Account Created";
        $log->causer_type = "Customer";
        $log->causer_id = 'ERP-'.$newuser->id;
        $log->save();
         
        Mail::to($data['email'])->send(new AccountCreation($mailData));
        $admin = User::where('role_id', 1)->first();
        
        $data = [

                'receiver' => $admin->id,
                'sender' => $newuser->id,
                'sender_name' => $newuser->name,
                'message' => 'A new User has been Registered',
                'url' => 'user_show',

            ];
            // dd($data);
            // dd('sdfsdf');
            // dd($newuser);
            // $user = $newuser;
            $admin->notify(new SendNotification($data));

            $noti = $admin->notifications()->latest()->first();
            // dd($noti);

            $noti->noti_type = "registration";
            // $noti->sender_id = $newuser->id;
            $noti->update();
            $data['id'] = $noti->id;
            // event(new FormApprove('Someone'));
            broadcast(new FormApprove($data));

        // dd("mail sent");
        DB::commit();
        return redirect()->back()->with('message','Account Credentials has been sent your email address');
        } 
        catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

    }
}
