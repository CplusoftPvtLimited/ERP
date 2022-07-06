<?php

namespace App\Http\Controllers;
use App\Form;
use App\FormField;
use App\FormFieldData;
use App\FormUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $form = Form::find($request->form);
            if(!empty($form)){
                $form_fields = FormField::where('form_id',$form->id)->get();

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
 
           return redirect()->route('formMessage');
            // return back();
        // }catch(\Exception $e){
        //     dd($e->getMessage());
        // }
        

    }
    public function formMessage()
    {
       return view('forms.show_form_save_message');
    }

    public function showSubmitForm()
    {
        $form = Form::where('role_id', Auth::user()->role_id)->first();
        $form_fields = FormField::where('form_id',$form->id)->get();
        return view('forms.Form_index',compact('form','form_fields'));
    }
 
}
