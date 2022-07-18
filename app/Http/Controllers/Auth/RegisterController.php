<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use Mail;
use App\Mail\AccountCreation;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd($data);
        return Validator::make($data, [
            // 'name' => 'required|string|max:255|unique:users',
            'name' => [
                'required',
                'max:10',
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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       DB::beginTransaction();
        try {
            // dd($data);
           
                // dd("sdf");
                $data['is_active'] = false;
                // $mailData = [];
                $user = User::create([
            // 'name' => $data['name'],
            'name' => $data['name'],
            'email' => $data['email'],
            // 'phone' => $data['phone_number'],
            // 'company_name' => $data['company_name'],
            // 'role_id' => $data['role_id'],
            // 'biller_id' => $data['biller_id'],
            // 'warehouse_id' => $data['warehouse_id'],
            'is_active' => $data['is_active'],
            'is_deleted' => false,
            'role_id' => $data['role'],
            'password' => bcrypt("123456789"),
        ]);
        
           
        $mailData = [
            'title' => 'Mail from SalePro.com',
            'body' => 'Please Verfiy Your Account.',
            'name' => $data['name'],
        ];
         
        Mail::to($data['email'])->send(new AccountCreation($mailData));
        // dd("mail sent");
        DB::commit();
        return redirect()->back()->with('message','A Credentials mail has been sent your email address');


        } 
        catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

    }
}
