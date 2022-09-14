@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section>
<div class="container-fluid"><!-- Revenue, Hit Rate & Deals -->
            @if(Session::has('error'))
            <p class="bg-danger text-white p-2 rounded">{{Session::get('error')}}</p>
            @endif
            @if(Session::has('success'))
            <p class="bg-success text-white p-2 rounded">{{Session::get('success')}}</p>
            @endif
            <div class="row">
                <div class="col-6">
                  <h3>Submitted Forms</h3>
                </div>
                <div class="col-6" align="right">
                    <a class="btn btn-primary float-right" href="{{ url()->previous() }}"> Back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                  <p><strong>User Name:</strong> {{$user->name}}</p>
                  <p><strong>Email:</strong> {{$user->email}}</p>
                  
                </div>
                @if(count($userforms) > 0)
                <div class="col-6">
                  <p><strong>Role:</strong> {{$role->name}}</p>
                  <p><strong>Form Name:</strong> {{$form->form_name}}</p>
                </div>
                @endif
            </div>

</div>


<div class="table-responsive mt-5 p-2">
    <!-- <div class="col-lg-3"></div> -->
    @if(count($userforms) > 0)
    <table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>Date / Time</th>
     <th>Status</th>
     <th width="280px">Action</th>
  </tr>
    @php $i=0; @endphp
    @foreach($userforms as $u)
      @php
            $form = App\Form::find($u->form_id);
            $role = App\Roles::find($u->role_id);
            $status = $u->status;
            $date = $u->created_at->format('m/d/Y');
            $time = $u->created_at->format('H:i');
      @endphp
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{$date}} ({{$time}}) </td>
          @if($status == 0)
            <td>
              Pending
            </td>
            @elseif($status == 1)
            <td>
              Approved
            </td>
            @elseif($status  == 2)
            <td>
              Pending Resubmission
            </td>
            @else
            <td>
              Rejected
            </td>
          @endif
          @if($status == 0)
          <td>
            <div class="row">
                <div class="col-2">
                    <a class="btn btn-primary" href="{{ route('show_form',[$user->id,$u->id])}}"><i class="fa fa-eye"></i></a>
                    
                </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formapprove{{$u->id}}" class="btn btn-success"><i class="fa fa-check"></i></a>
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formreject{{$u->id}}" class="btn btn-danger">&times</a>
                    <!-- <a class="btn btn-danger" href="{{ route('reject_form',$u->id)}}">&times;</a> -->
                </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formresubmit{{$u->id}}" class="btn btn-secondary"><i class="fa fa-question"></i></a>
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                </div>
            </div>
          </td>
          @else
          <td>
            <div class="row">
                <div class="col-2">
                    <a class="btn btn-primary" href="{{ route('show_form',[$user->id,$u->id])}}"><i class="fa fa-eye"></i></a>
                    
                </div>
                <!-- <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formapprove{{$u->id}}" class="btn btn-primary"><i class="fa fa-check"></i></a> -->
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                <!-- </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formreject{{$u->id}}" class="btn btn-danger">&times</a> -->
                    <!-- <a class="btn btn-danger" href="{{ route('reject_form',$u->id)}}">&times;</a> -->
                <!-- </div>
                <div class="col-2">
                  <a href="#" data-toggle="modal" data-target="#formresubmit{{$u->id}}" class="btn btn-secondary"><i class="fa fa-question"></i></a> -->
                    <!-- <button id="modalshow" class="btn btn-success"><i class="fa fa-check"></i></button> -->
                <!-- </div> -->
            </div>
          </td>
          @endif

    </tr>
    <!--Approve Modal -->
    <div id="formapprove{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'approve_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Comment')}}</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="form_id" value="{{ $u->id }}">
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
    <div id="formreject{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'reject_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Comment')}}</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="form_id" value="{{ $u->id }}">
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
    <div id="formresubmit{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        {!! Form::open(['route' => 'resubmit_form', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Comment')}}</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="form_id" value="{{ $u->id }}">
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
