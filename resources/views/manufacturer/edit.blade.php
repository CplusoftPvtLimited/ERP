@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
<div class="row allforms pr-4 pl-4">
            <div class="col-lg-6">
                <div class="pull-left">
                    <h2>Update Manufacturer</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="pull-right">
                    <a class="btn btn-primary float-right" href="{{ route('manufacturer.index') }}"> Back</a>
                </div>
            </div>
        </div>
</div>

<div class="row mr-4 ml-4">
    <div class="col-lg-12">
        @if(Session::has('error'))
            <p class="bg-danger text-white p-2 rounded">{{Session::get('error')}}</p>
            @endif
            @if(Session::has('success'))
            <p class="bg-success text-white p-2 rounded">{{Session::get('success')}}</p>
            @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif


<form action="{{ route('manufacturer.update', $manufacturer) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
<div class="row bg-white mt-2 p-2">
    <div class="col-6">
        <div class="form-group">
            <strong>Manufacturer Name:</strong>
            <input type="text" name="manuName" class="form-control" required value="{{$manufacturer->manuName}}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <strong>Linkage Target Type:</strong>
            <select name="linkingTargetType" id="" class="form-control" required>
                <!-- <option value="" selected disabled>--Select One--</option> -->
                <option value="P" {{($manufacturer->linkingTargetType == 'P') ? 'selected' : ''}}>P</option>
                <option value="O" {{$manufacturer->linkingTargetType == 'O'? 'selected' : ''}}>O</option>
            </select>
        </div>
    </div>
        <div class="col-1 offset-11">
        <button type="submit" class="btn btn-primary">Update</button>
        </div>
</div>
</form>
    </div>
</div>
</div>
</div>


</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</section>
@endsection

