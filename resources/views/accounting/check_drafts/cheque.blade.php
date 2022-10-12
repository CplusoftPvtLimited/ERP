@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section>
        @php
            $date = Carbon\Carbon::now();
            $current_date = $date->format('Y-m-d');
            // dd($current_date);
        @endphp
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-row-reverse">
                                <a href="" data-toggle="modal" data-target="#createModal"
                                    class="btn btn-info mb-1"><i class="dripicons-plus"></i> {{ trans('file.Add') }}</a>
                                <div class="col-11 pt-1">
                                    <h2>Cheque / Drafts</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="collapse show">
                                <form action="{{ route('cash.management.cheque') }}" method="get">
                                    @csrf
                                    <div class="flex-column d-flex flex-md-row p-2">
                                        <div class="d-flex p-2 flex-column">
                                            <label for="" class="text-secondary">Type date</label>
                                            <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input radiodueDate" type="radio"
                                                        name="radiodueDate" id="inlineRadio1" value="settelement_date"
                                                        checked>
                                                    <label class="form-check-label" for="inlineRadio1">Settlement
                                                        Date</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input radiodueDate" type="radio"
                                                        name="radiodueDate" id="inlineRadio2" value="due_date">
                                                    <label class="form-check-label" for="inlineRadio2">Due Date</label>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="form-group mb-0 mr-2">
                                                    <label for="" class="text-secondary">Start date</label>
                                                    <input type="date" mwlflatpickr="" name="start_date" id="start_date"
                                                        placeholder="" aria-describedby="helpId"
                                                        class="form-control input-date ng-untouched ng-pristine ng-valid flatpickr-input">
                                                </div>
                                                <div class="form-group p-l-10">
                                                    <label for="" class="text-secondary">End Date</label>
                                                    <input type="date" name="end_date" id="end_date" mwlflatpickr=""
                                                        placeholder="" aria-describedby="helpId"
                                                        class="form-control input-date ng-untouched ng-pristine ng-valid flatpickr-input">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex p-2 flex-column">
                                            <label for="" class="text-secondary">
                                                Method of payment
                                            </label>
                                            <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input ng-untouched ng-pristine ng-valid transaction_type"
                                                        type="radio" name="transaction_type" id="transaction_type"
                                                        value="credit" checked>
                                                    <label class="form-check-label" for="inlineRadio1">Recieved</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input ng-untouched ng-pristine ng-valid transaction_type"
                                                        type="radio" name="transaction_type" id="transaction_type"
                                                        value="debit">
                                                    <label class="form-check-label" for="inlineRadio2">Sent</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex p-2 flex-column">
                                            <label for="" class="text-secondary">Mode of payment</label>
                                            <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input ng-untouched ng-pristine ng-valid payment_method"
                                                        type="radio" name="payment_method" id="payment_method"
                                                        value="cheque" checked>
                                                    <label class="form-check-label" for="inlineRadio1">Cheque</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input ng-untouched ng-pristine ng-valid payment_method"
                                                        type="radio" name="payment_method" id="payment_method"
                                                        value="draft">
                                                    <label class="form-check-label" for="inlineRadio2">Draft</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex p-2 flex-column">
                                            <div class="form-group mb-0">
                                                <label for="" class="text-secondary">Balance Type</label>
                                                <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                                    <div class="form-check form-check-inline">
                                                        <input
                                                            class="form-check-input ng-untouched ng-pristine ng-valid balance_type"
                                                            type="radio" name="balance_type" id="balance_type"
                                                            value="primary" checked>
                                                        <label class="form-check-label" for="inlineRadio1">Primary</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input
                                                            class="form-check-input ng-untouched ng-pristine ng-valid balance_type"
                                                            type="radio" name="balance_type" id="balance_type"
                                                            value="secondary">
                                                        <label class="form-check-label"
                                                            for="inlineRadio2">Secondary</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex p-2 justify-content-end">
                                        <div class="d-flex flex-wrap p-2">
                                            <a href="{{ route('cash.management.cheque') }}" type="button" name=""
                                                id="" class="btn btn-outline-primary btn-lg btn-block float-left"
                                                style="font-family: Arial; font-size: 14px; text-align: center;">Reset</a>
                                        </div>
                                        <div class="d-flex flex-wrap p-2">
                                            <button type="submit" name="" id="filter_btn"
                                                class="btn btn-primary btn-lg btn-block float-left"
                                                style="color: #fff; font-family: Arial; font-size: 14px; text-align: center;">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3>{{trans('file.Cheque And Draft')}}</h3> --}}
                            {{-- <button class="btn btn-success"><i class="fa fa-plus"></i>abc</button> --}}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('file.Payment Mode') }}</th>
                                            <th>{{ trans('file.NUMBER') }}</th>
                                            <th>{{ trans('file.CARRIER') }}</th>
                                            <th>{{ trans('file.BANK') }}</th>
                                            <th>{{ trans('file.SETTLEMENT DATE') }}</th>
                                            <th>{{ trans('file.DUE DATE') }}</th>
                                            <th>{{ trans('file.AMOUNT') }}</th>
                                            <th>{{ trans('file.Balance Type') }}</th>
                                            <th>{{ trans('file.Transaction Type') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regulations as $item)
                                            <tr>
                                                <td>{{ $item->mode_payment }}</td>
                                                <td>{{ $item->cheque_number != null ? $item->cheque_number : $item->draft_number }}
                                                </td>
                                                <td>{{ $item->carrier != null ? $item->carrier : 'N/A' }}</td>
                                                <td>{{ $item->bank_id != null ? $item->bankList->title : ($item->bankAccount->account_title != null ? $item->bankAccount->account_title : null) }}
                                                </td>
                                                <td>{{ substr($item->settlement_date, 0, 10) }}</td>
                                                <td>{{ substr($item->due_date, 0, 10) }}</td>
                                                <td> TND {{ $item->amount }}</td>
                                                <td>{{ $item->balance_type }}</td>
                                                <td>{{ $item->transaction_type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Check / Draft') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body pr-5 pl-5">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        <form action="{{ route('balanceSheet.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Mode Payment') }} *</label>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <input type="radio" id="check" name="mode_payment" checked="checked"
                                            value="cheque" class="cheque_mode_payment">
                                        <label for="css">Cheque</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <input type="radio" id="draft" name="mode_payment" value="draft"
                                            class="cheque_mode_payment">
                                        <label for="html">Draft</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Transaction Type') }} *</label>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <input type="radio" id="debit" name="transaction_type" checked="checked"
                                            value="debit" class="cheque_transaction_type"
                                            data-href="{{ route('get_categories_from_type') }}">
                                        <label for="css">Sent</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <input type="radio" id="credit" name="transaction_type" value="credit"
                                            class="cheque_transaction_type"
                                            data-href="{{ route('get_categories_from_type') }}">
                                        <label for="html">Recieved</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Category') }}*</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="category_id" id="category_id" class="selectpicker form-control"
                                            data-live-search="true" data-live-search-style="begins"
                                            title="Select Category...">
                                            @foreach ($bal_categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Amount') }}*</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-text">TND</div>
                                            <input type="number" name="amount" id="cheque_amount" step="any"
                                                maxlength="191" min="0.1" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="cheque_supplier">
                                <div class="col-3">
                                    <label>{{ trans('file.Supplier') }}</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            <option value="">--Select One--</option>
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="cheque_customer">
                                <div class="col-3">
                                    <label>{{ trans('file.Customer') }}</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option value="">--Select One--</option>
                                            <option value="1">Walk In Customer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="cheque_balance_type">
                                <div class="col-3">
                                    <label>{{ trans('file.Balance Type') }}</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="balance_type" id="balance_type" class="form-control">
                                            <option value="">--Select One--</option>
                                            <option value="primary">Primary Balance</option>
                                            <option value="secondary">Secondary Balance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="accountSource">
                                <div class="col-3">
                                    <label>{{ trans('file.Account Source') }} *</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="account_source" id="overview_account_source" class="form-control">
                                            <option value="">--Select One--</option>
                                            @foreach ($source_accounts as $item)
                                                <option value="{{ $item->id }}">{{ $item->account_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="carrier">
                                <div class="col-3">
                                    <label>{{ trans('file.Carrier') }} *</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="text" name="carrier" maxlength="50" id="chequecarrier" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="bankSource">
                                <div class="col-3">
                                    <label>{{ trans('file.Bank') }} *</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <select name="bank_id" id="overview_account_source" class="form-control">
                                            <option value="">--Select One--</option>
                                            @foreach ($banks as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="checkNumber">
                                <div class="col-3">
                                    <label>{{ trans('file.Cheque Number') }} *</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="text" maxlength="20" name="cheque_number" id="cheque_cheque_number"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="draftNumber">
                                <div class="col-3">
                                    <label>{{ trans('file.Draft Number') }} *</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="text" name="draft_number" maxlength="20" id="cheque_draft_number"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Settelement Date') }}*</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="date" name="settlement_date" id="overview_settlement_date"
                                            max={{ $current_date }} placeholder="DD-MM-YYYY" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="duedate">
                                <div class="col-3">
                                    <label>{{ trans('file.Due Date') }}</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="date" name="due_date" id="overview_due_date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ trans('file.Note') }}</label>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <textarea name="note" id="overview_note" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                searching: false,
                ordering: false
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#draftNumber").css('display', "none");
            $("#cheque_customer").css('display', "none");
            $("#carrier").css('display', "none");
            $("#bankSource").css('display', "none");

            $('.cheque_mode_payment').on('click', function() {
                var paymentMode = $('.cheque_mode_payment:checked').val();
                var transactionType = $('.cheque_transaction_type:checked').val();
                if (paymentMode == 'cheque' && transactionType == 'debit') {
                    $("#cheque_supplier").css('display', "");
                    $("#cheque_customer").css('display', "none");
                    $("#accountSource").css('display', "");
                    $("#carrier").css('display', "none");
                    $("#bankSource").css('display', "none");
                    $("#checkNumber").css('display', "");
                    $("#draftNumber").css('display', "none");


                } else if (paymentMode == 'draft' && transactionType == 'debit') {
                    $("#cheque_supplier").css('display', "");
                    $("#cheque_customer").css('display', "none");
                    $("#accountSource").css('display', "");
                    $("#carrier").css('display', "none");
                    $("#bankSource").css('display', "none");
                    $("#checkNumber").css('display', "none");
                    $("#draftNumber").css('display', "");
                } else if (paymentMode == 'cheque' && transactionType == 'credit') {
                    $("#cheque_supplier").css('display', "none");
                    $("#cheque_customer").css('display', "");
                    $("#accountSource").css('display', "none");
                    $("#carrier").css('display', "");
                    $("#bankSource").css('display', "");
                    $("#checkNumber").css('display', "");
                    $("#draftNumber").css('display', "none");

                } else if (paymentMode == 'draft' && transactionType == 'credit') {
                    $("#cheque_supplier").css('display', "none");
                    $("#cheque_customer").css('display', "");
                    $("#accountSource").css('display', "none");
                    $("#carrier").css('display', "");
                    $("#bankSource").css('display', "");
                    $("#checkNumber").css('display', "none");
                    $("#draftNumber").css('display', "");
                }
            });
            $('.cheque_transaction_type').on('click', function() {
                var payment_mode = $('.cheque_mode_payment:checked').val();
                var transaction_type = $('.cheque_transaction_type:checked').val();
                if (payment_mode == 'cheque' && transaction_type == 'debit') {
                    $("#cheque_supplier").css('display', "");
                    $("#cheque_customer").css('display', "none");
                    $("#accountSource").css('display', "");
                    $("#carrier").css('display', "none");
                    $("#bankSource").css('display', "none");
                    $("#checkNumber").css('display', "");
                    $("#draftNumber").css('display', "none");


                } else if (payment_mode == 'draft' && transaction_type == 'debit') {
                    $("#cheque_supplier").css('display', "");
                    $("#cheque_customer").css('display', "none");
                    $("#accountSource").css('display', "");
                    $("#carrier").css('display', "none");
                    $("#bankSource").css('display', "none");
                    $("#checkNumber").css('display', "none");
                    $("#draftNumber").css('display', "");
                } else if (payment_mode == 'cheque' && transaction_type == 'credit') {
                    $("#cheque_supplier").css('display', "none");
                    $("#cheque_customer").css('display', "");
                    $("#accountSource").css('display', "none");
                    $("#carrier").css('display', "");
                    $("#bankSource").css('display', "");
                    $("#checkNumber").css('display', "");
                    $("#draftNumber").css('display', "none");

                } else if (payment_mode == 'draft' && transaction_type == 'credit') {
                    $("#cheque_supplier").css('display', "none");
                    $("#cheque_customer").css('display', "");
                    $("#accountSource").css('display', "none");
                    $("#carrier").css('display', "");
                    $("#bankSource").css('display', "");
                    $("#checkNumber").css('display', "none");
                    $("#draftNumber").css('display', "");
                }
                let url = $(this).attr('data-href');
                // alert(url);
                getCategories(url, transaction_type);
            });

            function getCategories(url, transaction_type) {
                $.get(url + '?transaction_type=' + transaction_type, function(data) {
                    let response = data.data;
                    let view_html = `<option value="">Select One</option>`;
                    $.each(response, function(key, value) {
                        view_html +=
                            `<option value="${value.id}">${value.category }</option>`;
                    });
                    $('#category_id').html(view_html);
                    $("#category_id").val(4);
                    $("#category_id").selectpicker("refresh");
                })
            }
        })
    </script>
@endpush
