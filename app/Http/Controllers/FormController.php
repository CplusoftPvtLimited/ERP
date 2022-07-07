<?php

namespace App\Http\Controllers;
use App\Form;
use App\FormField;
use App\FormFieldData;
use App\FormUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use App\Events\FormApprove;
use Mail;
use App\Notifications\SendNotification;
use App\Mail\FormSubmit;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
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
    

    public function show($id)
    {
        $form = Form::find($id);
        $form_fields = FormField::where('form_id',$id)->get();

        return view('forms.show',compact('form','form_fields'));
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


    public function destroy($id)
    {
        $form = Form::find($id);

        $form->delete();

        return back();
    }

    public function getForm($id)
    {
        $form = Form::find($id);
        $form_fields = FormField::where('form_id',$id)->get();
        // dd($form_fields_data);
        


        return view('forms.show_form_fields',compact('form','form_fields'));
    }

    public function formSave(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try
        {
        $form = Form::find($request->form);
        // $userform = FormUser::where('user_id',auth()->user()->id)->where('status',0)->first();
        // if($userform)
            if(!empty($form)){
                $form_fields = FormField::where('form_id',$form->id)->get();
                $formuser = FormUser::where('user_id',auth()->user()->id)->first();
                if(!$formuser)
                {
                    $formuser = new FormUser();
                    $formuser->form_id = $form->id;
                    $formuser->user_id = auth()->user()->id;
                    $formuser->role_id = auth()->user()->role_id;
                    $formuser->status = 0;
                    $formuser->save();
                    foreach($form_fields as $f){
                        if($request->hasFile($f->field_name)){
                            $file = $request[$f->field_name];
                            $imageName = time() . rand(1, 10000) . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('images/form'), $imageName);
                        
                            $f_data = new FormFieldData();
                            $f_data->form_id = $form->id;
                            $f_data->field_id = $f->id;
                            $f_data->user_id = auth()->user()->id;
                            $f_data->field_value = $imageName;
                            $f_data->save();
                        }
                    else if($request->has($f->field_name)){
                        // dump($request[$f->field_name]);
                        $f_data = new FormFieldData();
                        $f_data->form_id = $form->id;
                        $f_data->field_id = $f->id;
                        $f_data->user_id = auth()->user()->id;
                        $f_data->field_value = $request[$f->field_name];
                        $f_data->save();
                    }
                }
                }
                else{
                        $formuser->form_id = $form->id;
                        $formuser->user_id = auth()->user()->id;
                        $formuser->role_id = auth()->user()->role_id;
                        $formuser->status = 0;
                        $formuser->update();
                        $form_fields_data = FormFieldData::where('user_id',auth()->user()->id)->where('form_id',$form->id)->get();
                        foreach($form_fields_data as $ffd)
                        {
                            $ffd->delete();
                        }
                    foreach($form_fields as $f){
                        if($request->hasFile($f->field_name)){
                            $file = $request[$f->field_name];
                            $imageName = time() . rand(1, 10000) . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('images/form'), $imageName);
                        
                            $f_data = new FormFieldData();
                            $f_data->form_id = $form->id;
                            $f_data->field_id = $f->id;
                            $f_data->user_id = auth()->user()->id;
                            $f_data->field_value = $imageName;
                            $f_data->save();
                        }
                    else if($request->has($f->field_name)){
                        // dump($request[$f->field_name]);
                        $f_data = new FormFieldData();
                        $f_data->form_id = $form->id;
                        $f_data->field_id = $f->id;
                        $f_data->user_id = auth()->user()->id;
                        $f_data->field_value = $request[$f->field_name];
                        $f_data->save();
                    }
                }

                }
                $admin = User::where('role_id', 1)->first();
                $mailData = [
                    'header' => isset($mail_data) ? $mail_data->header : 'Header',
                    'title' => 'Mail from SalePro.com',
                    'body' => auth()->user()->name .' has Submitted a form.',
                    'footer' => isset($mail_data) ? $mail_data->footer : 'Footer',

            // 'action_url' => url('verify/account')
        ];

        Mail::to($admin->email)->send(new FormSubmit($mailData));
        
        $data = [

                'receiver' => $admin->id,
                'sender' => auth()->user()->id,
                'sender_name' => auth()->user()->name,
                'message' => auth()->user()->name.' has submitted a Form',
                'url' => 'submitted_form_show',

            ];
            // dd($data);
            // dd('sdfsdf');
            // dd($newuser);
            // $user = $newuser;
            $admin->notify(new SendNotification($data));

            $noti = $admin->notifications()->latest()->first();
            // dd($noti);

            $noti->noti_type = "formsubmission";
            // $noti->sender_id = $newuser->id;
            $noti->update();
            $data['id'] = $noti->id;
            // event(new FormApprove('Someone'));
            broadcast(new FormApprove($data));
            }
            DB::commit();
 
           return redirect()->route('formMessage');
            // return back();
        }catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
        

    }
    public function formMessage()
    {
       return view('forms.show_form_save_message');
    }

    public function showSubmitForm()
    {
        $form = Form::where('role_id', Auth::user()->role_id)->first();
        // dd($form->id);
        $form_fields = FormField::where('form_id',$form->id)->get();
        return view('forms.Form_index',compact('form','form_fields'));
        
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
public function reShowSubmitForm($noti_id)
{
    // dd('ddhjdhj');
    $form = Form::where('role_id', Auth::user()->role_id)->first();
        // dd($form->id);
        $form_fields = FormField::where('form_id',$form->id)->get();
        $notis = auth()->user()->unreadNotifications;
        foreach($notis as $n){
            if($n->id == $noti_id){
                $n->markAsRead();
            }
        }

        return view('forms.show_form_fields',compact('form','form_fields'));


}

 
}
