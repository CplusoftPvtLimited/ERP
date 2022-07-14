<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Form;
use App\Activity;
use App\FormField;
use Illuminate\Support\Facades\DB;

class RetailerLoginController extends Controller
{
    public function login(Request $request)
    { 
        // dd($request->all());

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
                $FormUser = DB::table('form_user')->where('user_id',$id)->where('role_id',$role_id)->latest()->first();
                if(isset($FormUser) && $FormUser->status == 0){
                    $log = new Activity();
                    $log->log_name = Auth::user()->name;
                    $log->subject_type = "Account Login";
                    $log->causer_type = "Customer";
                    $log->causer_id = 'ERP-'.$id;
                    $log->save();
                    // return redirect('/index');
                    return redirect()->route('formMessage');
                }else if(isset($FormUser) && $FormUser->status == 1){
                    $log = new Activity();
                    $log->log_name = Auth::user()->name;
                    $log->subject_type = "Account Login";
                    $log->causer_type = "Customer";
                    $log->causer_id = 'ERP-'.$id;
                    $log->save();
                    return redirect('/');
                }
                if($FormUser == NULL || $FormUser->status == 2 || $FormUser->status == 3){
                   
                    $form = Form::where('role_id',$role_id)->first();
                    // dd($form);
                    if($form == NULL){
                        return redirect('logout');
                    }
                    $log = new Activity();
                    $log->log_name = Auth::user()->name;
                    $log->subject_type = "Account Login";
                    $log->causer_type = "Customer";
                    $log->causer_id = 'ERP-'.$id;
                    $log->save();
                    return redirect()->route('getform',$form->id);
                }
               
          
            } else {
                return redirect()->route('login')->with('error', 'Username And Password Are Wrong.');
            }
        }else{
            return back()->with('error1','Your are not allowed login from here');
        }
    }
}
