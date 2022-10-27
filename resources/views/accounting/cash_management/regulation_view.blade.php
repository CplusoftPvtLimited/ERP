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
                        <div class=" card-header d-flex flex-row-reverse mb-3 article_view_tr_head">
                            <a href="{{ url()->previous() }}" class="btn btn-info mb-1 mr-4" style="background: white;
                                color: #6244a6;">{{ trans('file.Back') }}</a>
                            <div class="col-11 pt-1">
                                <h2>Regulation Data</h2>
                            </div>
                        </div>
                        <div class="card-body">
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="other_data"></div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Transaction Type</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->transaction_type }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Mode of Payment</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->mode_payment }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Category</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->balanceCategory->category }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Supplier/Customer</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->supplier_id != null ? $regulation->afterMarketSupplier->name : ($regulation->customer_id != null ? 'Walk In Customer' : 'N/A') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Amount</strong>
                                                <div class="input-group">
                                                    <div class="input-group-text article_view_tr_head">TND</div>
                                                    <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->amount }}">
                                                  </div>
                                               
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Settelement Date</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->settlement_date}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Check/Draft</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->check_number != null ? $regulation->check_number : ($regulation->draft_number != null ? $regulation->draft_number : 'N/A')  }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Due Date</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->due_date != null ? $regulation->due_date : 'N/A'}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Account Source / Bank</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->bank_id != null ? $regulation->bankList->title : ($regulation->account_source != null ? $regulation->bankAccount->account_title : 'N/A') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Carrier</strong>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $regulation->carrier != null ? $regulation->carrier : 'N/A' }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Note</strong>
                                                <textarea name="" id="" cols="30" rows="5" class="form-control" readonly>{{$regulation->note}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
