@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Retailer')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
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
                        <form action="{{ route('retailers.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="other_data"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <strong>Name: *</strong>
                                                <input type="text" name="name" class="form-control" required value="{{old('name')}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <strong>Email: *</strong>
                                                <input type="email" name="email" class="form-control" required value="{{old('email')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <strong>Shop Name: *</strong>
                                                <input type="text" name="shop_name" class="form-control" required value="{{old('shop_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <strong>Phone Number: *</strong>
                                                <input type="number" name="phone" class="form-control" required value="{{old('phone')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
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
@endsection

