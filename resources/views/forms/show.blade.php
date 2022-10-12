@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
<form action="" method="post" enctype="multipart/form-data">
    @csrf

<div class="container">
    <div class="row">
        <div class="col-lg-12">
        <div class="row card mt-5 p-2">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{$form->form_name}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

<input type="hidden" name="form" value="{{ $form->id }}">
<div class="row card mt-5 p-2">
    
    @foreach($form_fields as $f)
    @php $form_fields_data = App\FormFieldsData::where('field_id',$f->id)->where('user_id',$user_id)->where('form_user_id',$formuserid)->first();  @endphp
@if($form_fields_data)
    <div class="col-xs-12 col-sm-12 col-md-12">
        @if($f->field_type == 1)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="text" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : 'Null'}}" disabled>
        </div>
        @elseif($f->field_type == 2)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <textarea name="{{ $f->field_name }}" id="" cols="30" rows="10"class="form-control" disabled>{{ isset($form_fields_data) ? $form_fields_data->field_value : 'Null'}}</textarea>
        </div>
        @elseif($f->field_type == 3)
        <div class="form-group col-md-12">
            <label>{{$f->field_label}}</label>
            @php
            $infoPath = pathinfo(public_path('/images/'.$form_fields_data->field_value));
            
            $path = ('http://retailer-erp.cplusoft.com/public/images/form/'.$form_fields_data->field_value);
            $extension = isset($infoPath['extension']) ? $infoPath['extension'] : '';
            @endphp
            @if(!empty($extension))
                @if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'webp')
                <div align="center">
                <img src="{{$path}}" height="150px" width="150px" alt="image">
                </div>
                @else
                <div style="border:1px solid lightgrey" class="p-1 rounded">
                    {{$form_fields_data->field_value}}
                    <a class="btn btn-success" href="{{url('download_file',[$form_fields_data->field_value,$extension])}}"><i class="fa fa-download" aria-hidden="true"></i></a>
                </div>
                
                @endif
            @else
            <p>No file exist</p>
            @endif
           
        </div>
        @elseif($f->field_type == 4)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="radio"  name="{{ $f->field_name }}" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : 'Null'}}" disabled>
        </div>
        @elseif($f->field_type == 6)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="email" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : 'Null'}}" disabled>
        </div>
        @elseif($f->field_type == 5)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="password" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : 'Null'}}" disabled>
        </div>
        @endif
    </div>
    @endif
    @endforeach
</div>
        </div>

    </div>
</div>

</form>
</section>
@endsection

