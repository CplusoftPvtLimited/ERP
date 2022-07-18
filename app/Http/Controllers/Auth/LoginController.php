<?php

  

namespace App\Http\Controllers\Auth;

  

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
// use Session;


  

class LoginController extends Controller

{

  

    use AuthenticatesUsers;

    

    protected $redirectTo = '/';

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest')->except('logout');

    }

  

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function login(Request $request)
    { 

        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        // dd(Auth::user()->id);
        // dd($fieldType);
        $user = User::where('name',$request->name)->first();
        if($user)
        {
            if($user->role_id != 1){
            return redirect()->route('login')->with('error','You are not allowed to access the system');
        }
    }

        if(auth()->attempt(array($fieldType => $input['name'], 'password' => $input['password'])))
        {
            // dd("hello");
            // if(auth::user()->is_active == 1)
            // if(role()->projectmanager)
            return redirect('/');
            // else
            // return redirect('logout');
        }
        else {
            return redirect()->route('login')->with('error','Username And Password Are Wrong.');
        }
    }
}