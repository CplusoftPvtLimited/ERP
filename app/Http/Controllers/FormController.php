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
    

    public function show($id,$user_id)
    {
        // dd($user_id);
        $form = Form::find($id);
        $user= User::find($user_id);
        // if(!$form){
        //     return back();
        // }
        // if(!$user){
        //     return back();
        // }
        $fields_data =[];

        $form_fields = FormField::where('form_id',$id)->get();
        


        return view('forms.show',compact('form','form_fields','fields_data','user_id'));
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
                'user' => $user
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

    public function rejectedForms()
    {
        $data = [];

        $userforms = FormUser::where('status', 2)->get();

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
        return view('forms.rejectedforms', compact('userforms','data'));
    }

    public function userFormApprove($id)
    {
        // dd('jhjhj');
        $userform = FormUser::find($id);
        $userform->status = 1 ;
        $userform->update();
        return redirect('approved/form');
    }

    public function userFormReject($id)
    {
        // dd('jhjhj');
        $userform = FormUser::find($id);
        $userform->status = 2 ;
        $userform->update();
        return redirect('rejected/form');
    }

    public function destroy($id)
    {
        $form = Form::find($id);
        $form->delete();
        return back();
    }
}
