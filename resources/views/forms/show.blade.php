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
            <a class="btn btn-primary" href="{{ route('form.index') }}"> Back</a>
        </div>
    </div>
</div>

<input type="hidden" name="form" value="{{ $form->id }}">
<div class="row card mt-5 p-2">
    
    @foreach($form_fields as $f)
    @php $form_fields_data = App\FormFieldsData::where('field_id',$f->id)->where('user_id',$user_id)->first();  @endphp

    <div class="col-xs-12 col-sm-12 col-md-12">
        @if($f->field_type == 1)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="text" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}">
        </div>
        @elseif($f->field_type == 2)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <textarea name="{{ $f->field_name }}" id="" cols="30" rows="10"class="form-control">{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}</textarea>
        </div>
        @elseif($f->field_type == 3)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="file" name="{{ $f->field_name }}" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}">
        </div>
        @elseif($f->field_type == 4)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="radio" name="{{ $f->field_name }}" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}">
        </div>
        @elseif($f->field_type == 6)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="email" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}">
        </div>
        @elseif($f->field_type == 5)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="password" name="{{ $f->field_name }}" class="form-control" value="{{ isset($form_fields_data) ? $form_fields_data->field_value : ''}}" readonly>
        </div>
        @endif
    </div>
    @endforeach

    <div class="ml-4">
    <button class="btn btn-primary" type="submit" >Save</button>
    </div>
</div>
        </div>

    </div>
</div>

</form>
</section>
@endsection

