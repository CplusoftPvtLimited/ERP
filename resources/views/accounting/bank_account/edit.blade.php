@extends('layout.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />e
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <section>
        <div class="container-fluid">
            @php
                $auth_id = Auth::id();
                // dd($auth_id);
            @endphp
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Edit Bank Account') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            @if (Session::has('error'))
                                <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
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
                            <form action="{{ route('bank_account.update', $bankAccount) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <strong>Bank Title: *</strong>
                                                    <select name="bank_id" id="" class="form-control">
                                                        @foreach ($banks as $item)
                                                            <option value="{{$item->id}}" {{$item->id == $bankAccount->bank_id ? 'selected' : ''}}>{{$item->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <strong>Account Number: *</strong>
                                                    <input type="number" name="account_number" class="form-control"
                                                        required value="{{$bankAccount->account_number}}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <strong>Account Title: *</strong>
                                                    <input type="text" name="account_title" class="form-control" required
                                                    value="{{$bankAccount->account_title}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <strong>Swift Code: *</strong>
                                                    <input type="text" name="swift_code" class="form-control" required
                                                    value="{{$bankAccount->swift_code}}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <strong>IBAN: *</strong>
                                                    <input type="text" name="iban" class="form-control" required
                                                    value="{{$bankAccount->iban}}">
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </section>
@endsection
