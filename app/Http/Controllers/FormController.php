<?php

namespace App\Http\Controllers;
use App\Form;
use App\FormField;
use App\FormFieldsData;
use App\FormUser;
use App\Roles;
use App\User;
use Illuminate\Support\Facades\DB;
use Session;
use Mail;
use App\Mail\AccountCreation;
use App\Mail\FormApprove as MailApprove;
use App\Mail\FormReject;
use App\Mail\FormResubmit;

use App\Activity;
use Illuminate\Support\Facades\Auth;

use App\Notifications\SendNotification;
use App\Events\FormApprove;


use App\GeneralMailSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        // dd();
        $form_all = Form::all();
        return view('forms.index', compact('form_all'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'field_name.*' => 'required',
            'field_type.*' => 'required',
            'name' => 'required',
            'role' => 'required'
        ]);
        DB::beginTransaction();
        try{
            $role = Form::where('role_id', $request->role)->first();
            // dd($role->id);
            if($role)
            {
                return back()->with('error','Form Already Exist related to this Role');
            }
            if(count($request->field_name) > 0 && count($request->field_type) > 0  && count($request->field_label) > 0 && count($request->field_type) == count($request->field_name)){
                $form = new Form();
                $form->form_name = $request->name;
                $form->role_id = $request->role;
                $form->save();

                for($i=0;$i < count($request->field_name); $i++){
                    $field = new FormField();
                    $field->form_id = $form->id;
                    $field->field_label = ucfirst($request->field_label[$i]);
                    $field->field_name = strtolower($request->field_name[$i]);
                    $field->field_type = $request->field_type[$i];
                    $field->save();
        }
        DB::commit();
        return redirect()->route('form.index')->with('success','Form Added Successfully');
    }
    else
    {
        return back()->with('error','Whoops: Something Gone Wrong');
    }
}
catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }

    }
    

    public function show($id,$user_id)
    {
        // dd($user_id);
        $form = Form::find($id);
        $user= User::find($user_id);
        $userform= FormUser::where('form_id',$id)->where('user_id',$user_id)->first();
        // dd($userform);
        if(!$userform){
            return redirect('pending/form');
        }
        // if(!$user){
        //     return back();
        // }

        $form_fields = FormField::where('form_id',$id)->get();
        


        return view('forms.show',compact('form','form_fields','user_id'));
    }

    public function edit($id)
    {
        $form = Form::find($id);
        $form_fields = FormField::where('form_id',$id)->get();
        $role_id = '';

        if($form->related_to == 10){
            $role_id = 10;
        }
        else if($form->related_to == 11){
            $role_id = 11;
        }

        return view('forms.edit',compact('form','form_fields','role_id'));

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            
        // dd($request->all());

            if(count($request->field_name) > 0 && count($request->field_type) > 0  && count($request->field_label) > 0 && count($request->field_type) == count($request->field_name)){
                $form = Form::find($id);
                $form->form_name = $request->name;
                $form->role_id = $request->role;
                $form->save();

                $fields = FormField::where('form_id',$form->id)->get();
                // dd($fields);
                for($i=0; $i < count($fields); $i++){
                    
                    $fields[$i]->delete();
                }
                for($i=0;$i < count($request->field_name); $i++){
                    $field = new FormField();
                    $field->form_id = $form->id;
                    $field->field_label = ucfirst($request->field_label[$i]);
                    $field->field_name = strtolower($request->field_name[$i]);
                    $field->field_type = $request->field_type[$i];
                    $field->save();
                }
                DB::commit();
                return redirect()->route('form.index')->with('success','Form Updated Successfully');
        }
        else
        {
        return back()->with('error','Whoops: Something Gone Wrong');
        }
    }
        catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function pendingForms()
    {
        $data = [];

        $userforms = FormUser::where('status', 0)->get();
        // dd(count($userforms));
        foreach($userforms as $u){
            $form = Form::find($u->form_id);
            $role = Roles::find($u->role_id);
            $user = User::find($u->user_id);
            $dat = [
                'form' => $form,
                'role' => $role,
                'user' => $user,
                'id' => $u->id
            ];

            array_push($data,$dat);
        }
        return view('forms.pendingforms', compact('userforms','data'));
    }

    public function approvedForms()
    {
        $data = [];

        $userforms = FormUser::where('status', 1)->get();

        // dd(count($userforms));
        foreach($userforms as $u){
            $form = Form::find($u->form_id);
            $role = Roles::find($u->role_id);
            $user = User::find($u->user_id);
            $dat = [
                'form' => $form,
                'role' => $role,
                'user' => $user
            ];
            array_push($data,$dat);
        }
        return view('forms.approvedforms', compact('userforms','data'));
    }

    // public function rejectedForms()
    // {
    //     $data = [];

    //     $userforms = FormUser::where('status', 2)->get();

    //     // dd(count($userforms));
    //     foreach($userforms as $u){
    //         $form = Form::find($u->form_id);
    //         $role = Roles::find($u->role_id);
    //         $user = User::find($u->user_id);
    //         $dat = [
    //             'form' => $form,
    //             'role' => $role,
    //             'user' => $user
    //         ];
    //         array_push($data,$dat);
    //     }
    //     return view('forms.rejectedforms', compact('userforms','data'));
    // }

    public function userFormApprove(Request $req)
    {
        // dd('jhjhj');
        // dd($req->all());

        DB::beginTransaction();
        try{
            $userform = FormUser::find($req->form_id);
            $user = User::find($userform->user_id);
            // dd($user->email);
            $userform->status = 1 ;
            $userform->update();
            $mail_data = GeneralMailSetting::first();
            $mailData = [
                'header' => isset($mail_data) ? $mail_data->header : 'Header',
                'title' => 'Mail from SalePro.com',
                'body' => 'Congratulations...Your Form has been approved.',
                'footer' => isset($mail_data) ? $mail_data->footer : 'Footer',

                // 'action_url' => url('verify/account')
            ];

            Mail::to($user->email)->send(new MailApprove($mailData));
            
            $log = new Activity();
                        $log->log_name = Auth::user()->name;
                        $log->subject_type = "Form Approve";
                        $log->causer_type = "Project Manager";
                        $log->causer_id = 'Project Manager-'.Auth::user()->id;
                        $log->comments = $req->comment;
                        $log->save();
                        
            // $user = User::find($request->user_id);
            // dd('sdfsd');
            $data = [
                'sender' => auth()->user()->id,
                'sender_name' => auth()->user()->name,
                'receiver' => $user->id,
                'message' => $req->comment,
				'url' => 'approved_dashboard',


            ];
            // dd('sdfsdf');
            $user->notify(new SendNotification($data));
            $noti = $user->notifications()->latest()->first();
            // dd($noti);
            $data['id'] = $noti->id;

            $noti->noti_type = "formapprove";
            $noti->update();
            // event(new FormApprove('Someone'));
            broadcast(new FormApprove($data));
            DB::commit();
            return redirect('approved/form');
        }catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return back();
        }
        
    }

    public function userFormReject(Request $req)
    {
        // dd('jhjhj');
        DB::beginTransaction();
        try {
            
        $userform = FormUser::find($req->form_id);
        $user = User::find($userform->user_id);
        $form = Form::find($userform->form_id);
        $userform->delete();
        $formfieldsdata = FormFieldsData::where('form_id',$form->id)->where('user_id',$user->id)->get();
        foreach($formfieldsdata as $ffd)
        {
            $ffd->delete();
        }
        $mail_data = GeneralMailSetting::first();
        $mailData = [
            'header' => isset($mail_data) ? $mail_data->header : 'gfg',
            'title' => 'Mail from SalePro.com',
            'body' => 'Oopps...Your Form has been Rejected.',
            'footer' => isset($mail_data) ? $mail_data->footer : 'gfg',

            // 'action_url' => url('verify/account')
        ];

        Mail::to($user->email)->send(new FormReject($mailData));
        $log = new Activity();
                    $log->log_name = Auth::user()->name;
                    $log->subject_type = "Form Reject";
                    $log->causer_type = "Project Manager";
                    $log->causer_id = 'Project Manager-'.Auth::user()->id;
                    $log->comments = $req->comment;
                    $log->save();

                    $data = [
                'sender' => auth()->user()->id,
                'sender_name' => auth()->user()->name,
                'receiver' => $user->id,
                'message' => $req->comment,
				 'url' => 'showSubmitForm',


            ];
            // dd('sdfsdf');
            $user->notify(new SendNotification($data));
            $noti = $user->notifications()->latest()->first();
            // dd($noti);
            $data['id'] = $noti->id;

            $noti->noti_type = "formreject";
            $noti->update();
            // event(new FormApprove('Someone'));
            broadcast(new FormApprove($data));
            DB::commit();
        return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return back();
        }
    }

    public function userFormResubmit(Request $req)
    {
        // dd('jhjhj');
        DB::beginTransaction();
        try {
        $userform = FormUser::find($req->form_id);
        $user = User::find($userform->user_id);
        // dd($user->email);
        $userform->status = 2 ;
        $userform->update();
        $mail_data = GeneralMailSetting::first();
        $mailData = [
            'header' => isset($mail_data) ? $mail_data->header : 'Header',
            'title' => 'Mail from SalePro.com',
            'body' => 'Whoops...You have been asked to Resubmit the form.',
            'footer' => isset($mail_data) ? $mail_data->footer : 'Footer',

            // 'action_url' => url('verify/account')
        ];

        Mail::to($user->email)->send(new FormResubmit($mailData));
        $log = new Activity();
                    $log->log_name = Auth::user()->name;
                    $log->subject_type = "Form Resubmission";
                    $log->causer_type = "Project Manager";
                    $log->causer_id = 'Project Manager-'.Auth::user()->id;
                    $log->comments = $req->comment;
                    $log->save();

                $data = [
                'sender' => auth()->user()->id,
                'sender_name' => auth()->user()->name,
                'receiver' => $user->id,
                'message' => $req->comment,
				 'url' => 'showSubmitForm',


            ];
            // dd('sdfsdf');
            $user->notify(new SendNotification($data));
            $noti = $user->notifications()->latest()->first();
            // dd($noti);
            $data['id'] = $noti->id;

            $noti->noti_type = "formresubmit";
            $noti->update();
            // event(new FormApprove('Someone'));
            broadcast(new FormApprove($data));
            DB::commit();
        return redirect('pending/form');
        }catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return back();
        }
    }

    public function downloadFile($name, $extension)
    {
        $path = public_path('/images/'.$name);
        // dd($path);
        $headers = ['Content-Type: application/'.$extension];
        // $fileName = $name;
        // dd(file_exists(public_path('/images/'.$name)));
        if(file_exists(public_path('/images/'.$name))){
            return response()->download($path, $name, $headers);
        }else{
            return back();
        }
    }
    public function destroy($id)
    {
        $form = Form::find($id);
        $form->delete();
        return back();
    }
public function showSubmittedForm($noti_id,$user_id)
{
        $user= User::find($user_id);

        $userform= FormUser::where('user_id',$user_id)->first();
        // dd($userform);
        if(!$userform){
            return redirect('pending/form');
        }
        // if(!$user){
        //     return back();
        // }
        $form = Form::find($userform->form_id);
        $form_fields = FormField::where('form_id',$form->id)->get();
        
        $notis = auth()->user()->unreadNotifications;
        foreach($notis as $n){
            if($n->id == $noti_id){
                $n->markAsRead();
            }
        }

        return view('forms.show',compact('form','form_fields','user_id'));
}

    public function readNotification($id=null){
        $notis = auth()->user()->unreadNotifications;
        foreach($notis as $n){
            if($n->id == $id){
                $n->markAsRead();
            }
        }
        return back();
        
    }
    public function showSubmitForm()
    {
        $form = Form::where('role_id', Auth::user()->role_id)->first();
        $form_fields = FormField::where('form_id',$form->id)->get();
        return view('forms.Form_index',compact('form','form_fields'));
    }
}
