@extends('layout.main') @section('content')

   


<section>
<form action="{{route('formSave')}}" method="post" enctype="multipart/form-data">
    @csrf

<div class="container" style="padding: 58px;">
    <div class="row">
        <div class="col-lg-12">
              <div class="row card p-2">
                        <div class="pull-left">
                          <h2 class="text-center">{{$form->form_name}}</h2>
                       </div>
                    </div>
               </div>
        </div>
<input type="hidden" name="form" value="{{ $form->id }}">
<div class="row card p-2">
   
    @foreach($form_fields as $f)
        @php $field_value = App\FormFieldData::where('user_id', auth()->user()->id)->where('form_id', $form->id)
        ->where('field_id', $f->id)->orderBy('id', 'desc')->first(); @endphp
    <div class="col-xs-12 col-sm-12 col-md-12">
        @if($f->field_type == 1)
          <div class="form-group col-md-12">
               <label for="">{{$f->field_label}}</label>
               <input type="text" name="{{ $f->field_name }}" value="{{ isset($field_value) ? (isset($field_value->field_value) ? $field_value->field_value : '') : ''}}" class="form-control" readonly>
        </div>
        @elseif($f->field_type == 2)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <textarea name="{{ $f->field_name }}" id="" cols="30" rows="10"class="form-control" readonly>{{ isset($field_value) ? (isset($field_value->field_value) ? $field_value->field_value : '') : ''}}</textarea>
        </div>
        @elseif($f->field_type == 3)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="file" name="{{ $f->field_name }}" value="{{ isset($field_value) ? (isset($field_value->field_value) ? 'images/form/'.$field_value->field_value : '') : ''}}" readonly>
        </div>
        @elseif($f->field_type == 4)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="radio" name="{{ $f->field_name }}" {{ isset($field_value) ? (isset($field_value->field_value) ? 'checked' : '') : ''}} readonly>
        </div>
        @elseif($f->field_type == 6)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="email" name="{{ $f->field_name }}" class="form-control" value="{{ isset($field_value) ? (isset($field_value->field_value) ? $field_value->field_value : '') : ''}}" readonly>
        </div>
        @elseif($f->field_type == 5)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="password" name="{{ $f->field_name }}" class="form-control" value="{{ isset($data) ? $data : ''}}" readonly>
        </div>
        @endif
    </div>
    @endforeach
    
    <!-- <div class="ml-4">
    <button class="btn btn-primary" type="submit" >Save</button>
    </div> -->
</div>
        </div>

    </div>
</div>

</form>
</section>


@endsection
@push('scripts')

@endpush
