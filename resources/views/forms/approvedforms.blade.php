@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
<div class="container-fluid"><!-- Revenue, Hit Rate & Deals -->
@if(Session::has('error'))
            <p class="bg-danger text-white p-2 rounded">{{Session::get('error')}}</p>
            @endif
            @if(Session::has('success'))
            <p class="bg-success text-white p-2 rounded">{{Session::get('success')}}</p>
            @endif
                <div class="pull-left">
                    <h2>Approved Forms</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary float-right" href="{{ route('form.index') }}"> Back</a>
                </div>
</div>



<div class="table-responsive mt-5 p-2">
    <!-- <div class="col-lg-3"></div> -->
    @if(count($userforms) > 0)
    <table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>User Name</th>
     <th>Form Name</th>
     <th>Role</th>
     <th width="280px">Action</th>
  </tr>
    @php $i=0; @endphp
    @foreach ($userforms as $key => $form)
    @foreach ($data as $key => $datas)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $datas['user']->name }}</td>
        <td>{{ $datas['form']->form_name }}</td>
        <td>{{ $datas['role']->name }}</td>

        <td>
            <div class="row">
                <div class="col-2">
                    <a class="btn btn-primary" href="{{ route('show_form',[$datas['form']->id,$datas['user']->id])}}"><i class="fa fa-eye"></i></a>
                </div>
            </div>
        </td>
    </tr>

    @endforeach
    @endforeach
</table>

    @else
    <div class="row" align="center">
                <div class="col-4 offset-4 bg-success">
                    <h3>No Data Available...</h3>
                </div>
            </div>
            @endif
    </div>
</section>
@endsection

