<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use App\FormUser;
use App\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd("hddjhdjhdjhdjhdjhdjhdh");
        if (Auth::guard($guard)->check()) {
            $formuser = FormUser::where('user_id',auth::user()->id)->latest()->first();
            // dd($formuser);
           if($formuser)
           {
            if (auth::user()->is_active == 1) {
                // dd("h");
                return redirect(RouteServiceProvider::HOME);
            }
           }
           if(! $formuser)
           {
            //   dd('empty');
            $form = Form::where('role_id',auth::user()->role_id)->first();
            // dd($form);
            return redirect('getform');
            // return redirect(RouteServiceProvider::HOMES);
           }
            else
            return redirect('logout');


        }

        return $next($request);
    }
}
