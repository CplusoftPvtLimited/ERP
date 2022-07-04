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
                    <h2>Pending Forms</h2>
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
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formapprove{{$datas['id']}}" class="btn btn-primary"><i class="fa fa-check"></i></a>
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formreject{{$datas['id']}}" class="btn btn-danger">&times</a>
                    <!-- <a class="btn btn-danger" href="{{ route('reject_form',$datas['id'])}}">&times;</a> -->
                </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formresubmit{{$datas['id']}}" class="btn btn-secondary"><i class="fa fa-question"></i></a>
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                </div>
            </div>
        </td>
    </tr>
    <!--Approve Modal -->
    <div id="formapprove{{ $datas['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'approve_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">Comment</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="text" name="form_id" value="{{ $datas['id'] }}">
          <div class="form-group">
            <label>{{trans('file.Comment')}} *</label>
              <textarea name="comment" class="form-control" id="" cols="30" rows="10"></textarea>
          </div>
          
            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
</div>
    <!-- Modal End -->
    <!--Reject Modal -->
    <div id="formreject{{ $datas['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'reject_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">Comment</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="text" name="form_id" value="{{ $datas['id'] }}">
          <div class="form-group">
            <label>{{trans('file.Comment')}} *</label>
              <textarea name="comment" class="form-control" id="" cols="30" rows="10"></textarea>
          </div>
          
            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
</div>
    <!-- Modal End -->
    <!--Resubmission Modal -->
    <div id="formresubmit{{ $datas['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'resubmit_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">Comment</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="text" name="form_id" value="{{ $datas['id'] }}">
          <div class="form-group">
            <label>{{trans('file.Comment')}} *</label>
              <textarea name="comment" class="form-control" id="" cols="30" rows="10"></textarea>
          </div>
          
            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
</div>
    <!-- Modal End -->
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

