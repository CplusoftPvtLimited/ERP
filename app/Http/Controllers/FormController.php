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

    public function getForm()
    {
        $formuser = FormUser::where('user_id',auth()->user()->id)->where('status', 1)->latest()->first();
        // dd($formuser);
        if($formuser)
        {
            // dd('dsjkjjskdkdjakjadhad');
            return redirect('/');
        }
        $formuserr = FormUser::where('user_id',auth()->user()->id)->where('status', 0)->latest()->first();
        // dd($formuser);
        if($formuserr)
        {
            // dd('dsjkjjskdkdjakjadhad');
            return back();
        }
        $form = Form::where('role_id',auth()->user()->role_id)->first();
        // dd($form);
        if(!$form)
        {
            return back();
        }
        $form_fields = FormField::where('form_id',$form->id)->get();
        // $user_form = FormUser::where('user_id',auth()->user()->id)->where('status','0')->where('status','1')->first();
        // // dd($user_form);
        // if(!empty($user_form)){
        //     return redirect()->back();
        // }
        // dd($form_fields_data);
        


        return view('forms.show_form_fields',compact('form','form_fields'));
    }

    public function formSave(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try
        {
        $form = Form::find($request->form);
        $user_form = FormUser::where('user_id',auth()->user()->id)->where('status','0')->latest()->first();
        // dd($user_form);
        if(!empty($user_form)){
            return redirect('formMessage')->with('error','Your Form Already Submitted');
        }
       
            if(!empty($form)){
                $form_fields = FormField::where('form_id',$form->id)->get();


                $formuser = new FormUser();
                $formuser->form_id = $form->id;
                $formuser->user_id = auth()->user()->id;
                $formuser->role_id = auth()->user()->role_id;
                $formuser->status = 0;
                $formuser->save();
                foreach($form_fields as $f){
                    $fields = explode(" ",$f->field_name);
                    $implode = implode("_",$fields);

                    // dump($fields);
                    // dump($implode);
                    // dump($request[$f->field_name]);
                    $f_data = new FormFieldData();
                        // dump($request[$implode]);

                    if ($request->hasFile($implode)) {
                        // dump($request[$implode]);
                        $file = $request[$implode];
                        $imageName = time() . rand(1, 10000) . '.' . $file->getClientOriginalExtension();
                        $f_data->field_value = $imageName;
                        // dump($imageName);
                        $file->move(public_path('images/form'), $imageName);
                        
                    }
                    else
                    {
                        $validated = $request->validate([
                            $implode => 'required|max:255',
                        ]);
                        $f_data->field_value = $request[$implode];
                    }  
                        // $f_data = new FormFieldData();
                        $f_data->form_id = $form->id;
                        $f_data->field_id = $f->id;
                        $f_data->user_id = auth()->user()->id;
                        $f_data->form_user_id = $formuser->id;
                        
                        $f_data->save();
                    }
                    // else if($request[$f->field_name]){
                    //     // dump($request[$f->field_name]);
                    //     $f_data = new FormFieldData();
                    //     $f_data->form_id = $form->id;
                    //     $f_data->field_id = $f->id;
                    //     $f_data->user_id = auth()->user()->id;
                    //     $f_data->field_value = $request[$f->field_name];
                    //     $f_data->save();
                    // }
                
                // dd("stop");
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
            // dd($e->getMessage());
            return back()->with('error','Fields Data Must be greater than 0 & less than 255');
        }
        

    }
    public function formMessage()
    {
        $formuser = FormUser::where('user_id',auth()->user()->id)->where('status', '1')->latest()->first();
        if($formuser)
        {
            return redirect('/');
        }
        // $formuserr = FormUser::where('user_id',auth()->user()->id)->where('status', '2')->latest()->first();
        // if($formuserr)
        // {
        //     // dd('ghghghh');
        //     return redirect('getform');
        
        // }
        // $formuserrr = FormUser::where('user_id',auth()->user()->id)->where('status', '3')->latest()->first();
        // if($formuserrr)
        // {
        //     // dd('ghghghh');
        //     return redirect('getform');
        
        // }
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
    $formuser = FormUser::where('user_id',auth()->user()->id)->where('status', '1')->where('status','0')->latest()->first();
        if($formuser)
        {
            return back();
        }
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
