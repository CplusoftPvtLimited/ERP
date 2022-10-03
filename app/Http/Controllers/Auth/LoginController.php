<?php

  

namespace App\Http\Controllers\Auth;

  

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Form;
use App\FormField;
use Illuminate\Support\Facades\DB;


  

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

        $check = User::where('name',$request->name)->where('role_id',1)->first();
        // dd($check);

        if (empty($check)) {
            $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

            if (auth()->attempt(array($fieldType => $input['name'], 'password' => $input['password']))) {
                $id = Auth::user()->id;
                $role_id = Auth::user()->role_id;
                $FormUser = DB::table('form_user')->where('user_id',$id)->where('role_id',$role_id)->first();
                // dd("out");
                if(empty($FormUser)){
                    // dd("if");
                    $form = Form::where('role_id',$role_id)->first();
                    // $form_fields = FormField::where('form_id',$id)->get();
                    // dd($form);
                    $id = $form->id;
                    return redirect('fillform/$id');
                }else{
                    return redirect('/');
                }
               
          
            } else {
                return redirect()->route('login')->with('error', 'Username And Password Are Wrong.');
            }
        }else{
            return back()->with('error1','Your are not allowed login from here');
        }
    }
}