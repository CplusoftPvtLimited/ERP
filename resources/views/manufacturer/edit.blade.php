@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

@php
    
@endphp

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Edit Manufacturer')}}</h4>
                    </div>
                    <div class="card-body">
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="other_data"></div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Manufacturer Name: *</strong>
                                                <input type="text" name="manuName" class="form-control" required value="{{$manufacturer->manuName}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Linkage Target Type:</strong>
                                                <select id="linkageTarget" class="form-control" required>
                                                    <option value="" selected disabled>--Select One--</option>
                                                    <option value="P" {{($target_type == 'P') ? 'selected' : ''}}>Passenger + Motorcycle + LCV</option>
                                                        <option value="O" {{($target_type == 'O') ? 'selected' : ''}}>Commercial Vehicle + Tractor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Sub-Linkage Target Type</strong>
                                                <select name="linkingTargetType" id="subLinkageTarget" class="selectpicker form-control" required>
                                                    <option value="">Select One</option>
                                                    @foreach ($types as $key => $item)
                                                        <option value="{{$key}}" {{($key == $sub_target_type) ? 'selected' : ''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Update</button>
                                    </div>
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

<script>
    $('#linkageTarget').on('change', function() {
        var val = this.value;
        
        if (val == "P") {
            $('#subLinkageTarget').empty();
            $('#subLinkageTarget').append(`
            <option value="V">Passenger Car</option>
            <option value="L">LCV</option>
            <option value="B">Motorcycle</option>`);
            $('.selectpicker').selectpicker('refresh');
        } else if(val == "O") {
            $('#subLinkageTarget').empty();
            $('#subLinkageTarget').append(`
            <option value="C">Commercial Vehicle</option>
            <option value="T">Tractor</option>
            <option value="M">Engine</option>
            <option value="A">Axle</option>
            <option value="K">CV Body Type</option>`);
            $('.selectpicker').selectpicker('refresh');
        } else {
            $('#subLinkageTarget').empty();
            $('.selectpicker').selectpicker('refresh');
        }
        
    });
</script>
@endsection

