@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}
        </div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="">{{ trans('file.Sales Estimate List') }}</h3>
                </div>
                {!! Form::open(['route' => 'sales.index', 'method' => 'get']) !!}

                <!-- <div class="col-md-4 mt-3 @if (\Auth::user()->role_id > 2) {{ 'd-none' }} @endif">
                                    <div class="d-flex">
                                        <label class="">{{ trans('file.Warehouse') }} &nbsp;</label>
                                        <div class="">
                                            <select id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" >
                                                <option value="0">{{ trans('file.All Warehouse') }}</option>
                                                @foreach ($lims_warehouse_list as $warehouse)
    @if ($warehouse->id == $warehouse_id)
    <option selected value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
@else
    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
    @endif
    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

            </div>
            {!! Form::close() !!}
        </div>
        @if (in_array('sales-add', $all_permission))
            <a href="{{ route('sales.create') }}" class="btn btn-info ml-4"><i class="dripicons-plus"></i>
                {{ trans('file.Add') }}</a>&nbsp;
            <!-- <a href="{{ url('sales/sale_by_csv') }}" class="btn btn-primary"><i class="dripicons-copy"></i> {{ trans('file.Import Sale') }}</a> -->
        @endif
        </div>
        <div class="table-responsive">
            <table id="sale-table" class="table sale-list" style="width: 100%">
                <thead>
                    <tr>
                        <th>{{ trans('file.Date') }}</th>
                        <th>{{ trans('file.Reference') }}</th>
                        <!-- <th>{{ trans('file.Biller') }}</th> -->
                        <th>{{ trans('file.Customer') }}</th>
                        <!-- <th>{{ trans('file.Sale Status') }}</th> -->
                        <!-- <th>{{ trans('file.Payment Status') }}</th> -->
                        <th>{{ trans('file.Grand Total') }}</th>
                        <th>{{ trans('file.Payment Method') }}</th>

                        <!-- <th>{{ trans('file.Paid') }}</th> -->
                        <th>{{ trans('file.Status') }}</th>
                        <th>{{ trans('file.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        @php $customer=App\Customer::find($sale->customer_id); @endphp
                        <tr>
                            <td>{{ $sale->date }}</td>
                            <td>{{ $sale->reference_no }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $sale->grand_total }}</td>
                            @if ($sale->payment_method == 1)
                                <td>{{ trans('file.white Cash') }}</td>
                            @elseif($sale->payment_method == 2)
                                <td>{{ trans('file.Black Cash') }}</td>
                            @else
                                <td></td>
                            @endif
                            @if ($sale->estimate_type == 0)
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle btn-primary"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{{ trans('file.Created') }}
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                            user="menu">
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.cancelEstimate', $sale->id) }}">{{ trans('file.Cancel') }}</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.acceptEstimate', $sale->id) }}">
                                                    {{ trans('file.Accept') }}</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.negotiateEstimate', $sale->id) }}">
                                                    {{ trans('file.Negotiate') }}</a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            @elseif($sale->estimate_type == 1)
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle btn-secondary"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{{ trans('file.Negotiation') }}
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                            user="menu">
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.cancelEstimate', $sale->id) }}">{{ trans('file.Cancel') }}</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.acceptEstimate', $sale->id) }}">
                                                    {{ trans('file.Accept') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            @elseif($sale->estimate_type == 2)
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle btn-danger"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{{ trans('file.Cancelled') }}
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                            user="menu">
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.reactivateEstimate', $sale->id) }}">{{ trans('file.Reactivate') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle btn-success"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{{ trans('file.Accepted') }}
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                            user="menu">
                                            <li>
                                                <a class="btn btn-link"
                                                    href="{{ route('sales.cancelEstimate', $sale->id) }}">{{ trans('file.Cancel') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <a class="btn" href="{{ route('sales.edit', $sale->id) }}"><i
                                        class="dripicons-document-edit"></i></a>
                                <a class="btn" href="{{ route('sales.generatePreInvoicePDF', $sale->id) }}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                <a class="btn" href="{{ route('sales.approveEstimate', $sale->id) }}"><i
                                        class="fa fa-eye"></i></a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false"><i class="fa fa-refresh"></i>
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                        user="menu">
                                        <!-- <li>
                                                <a class="btn btn-link" href="{{ route('sales.reactivateEstimate', $sale->id) }}">{{ trans('file.order') }}</a>
                                                </li> -->
                                        <li>
                                            <a class="btn btn-link"
                                                href="{{ route('sales.reactivateEstimate', $sale->id) }}">{{ trans('file.Delivery Slip') }}</a>
                                        </li>
                                        <li>
                                            <a class="btn btn-link"
                                                href="{{ route('sales.createSaleInvoice', $sale->id) }}">{{ trans('file.Invoice') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>



                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div id="sale-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="container mt-3 pb-2 border-bottom">
                    <div class="row">
                        <div class="col-md-6 d-print-none">
                            <button id="print-btn" type="button" class="btn btn-default btn-sm"><i
                                    class="dripicons-print"></i> {{ trans('file.Print') }}</button>

                            {{ Form::open(['route' => 'sale.sendmail', 'method' => 'post', 'class' => 'sendmail-form']) }}
                            <input type="hidden" name="sale_id">
                            <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i>
                                {{ trans('file.Email') }}</button>
                            {{ Form::close() }}
                        </div>
                        <div class="col-md-6 d-print-none">
                            <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close"
                                class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                        </div>
                        <div class="col-md-12">
                            <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">
                                {{ $general_setting->site_title }}</h3>
                        </div>
                        <div class="col-md-12 text-center">
                            <i style="font-size: 15px;">{{ trans('file.Sale Details') }}</i>
                        </div>
                    </div>
                </div>
                <div id="sale-content" class="modal-body">
                </div>
                <br>
                <table class="table table-bordered product-sale-list">
                    <thead>
                        <th>#</th>
                        <th>{{ trans('file.product') }}</th>
                        <th>{{ trans('file.Batch No') }}</th>
                        <th>{{ trans('file.Qty') }}</th>
                        <th>{{ trans('file.Unit') }}</th>
                        <th>{{ trans('file.Unit Price') }}</th>
                        <th>{{ trans('file.Tax') }}</th>
                        <th>{{ trans('file.Discount') }}</th>
                        <th>{{ trans('file.Subtotal') }}</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="sale-footer" class="modal-body"></div>
            </div>
        </div>
    </div>

    <div id="view-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.All') }} {{ trans('file.Payment') }}
                    </h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover payment-list">
                        <thead>
                            <tr>
                                <th>{{ trans('file.date') }}</th>
                                <th>{{ trans('file.reference') }}</th>
                                <th>{{ trans('file.Account') }}</th>
                                <th>{{ trans('file.Amount') }}</th>
                                <th>{{ trans('file.Paid By') }}</th>
                                <th>{{ trans('file.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Payment') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'sale.add-payment', 'method' => 'post', 'files' => true, 'class' => 'payment-form']) !!}
                    <div class="row">
                        <input type="hidden" name="balance">
                        <div class="col-md-6">
                            <label>{{ trans('file.Recieved Amount') }} *</label>
                            <input type="text" name="paying_amount" class="form-control numkey" step="any"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label>{{ trans('file.Paying Amount') }} *</label>
                            <input type="text" id="amount" name="amount" class="form-control" step="any"
                                required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Change') }} : </label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Paid By') }}</label>
                            <select name="paid_by_id" class="form-control">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                                @if ($lims_reward_point_setting_data->is_active)
                                    <option value="7">Points</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label> {{ trans('file.Gift Card') }} *</label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control"
                            data-live-search="true" data-live-search-style="begins" title="Select Gift Card...">
                            @php
                                $balance = [];
                                $expired_date = [];
                            @endphp
                            @foreach ($lims_gift_card_list as $gift_card)
                                <?php
                                $balance[$gift_card->id] = $gift_card->amount - $gift_card->expense;
                                $expired_date[$gift_card->id] = $gift_card->expired_date;
                                ?>
                                <option value="{{ $gift_card->id }}">{{ $gift_card->card_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="cheque">
                        <div class="form-group">
                            <label>{{ trans('file.Cheque Number') }} *</label>
                            <input type="text" name="cheque_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label> {{ trans('file.Account') }}</label>
                        <select class="form-control selectpicker" name="account_id">
                            @foreach ($lims_account_list as $account)
                                @if ($account->is_default)
                                    <option selected value="{{ $account->id }}">{{ $account->name }}
                                        [{{ $account->account_no }}]</option>
                                @else
                                    <option value="{{ $account->id }}">{{ $account->name }}
                                        [{{ $account->account_no }}]</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('file.Payment Note') }}</label>
                        <textarea rows="3" class="form-control" name="payment_note"></textarea>
                    </div>

                    <input type="hidden" name="sale_id">

                    <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div id="edit-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Update Payment') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'sale.update-payment', 'method' => 'post', 'class' => 'payment-form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{ trans('file.Recieved Amount') }} *</label>
                            <input type="text" name="edit_paying_amount" class="form-control numkey" step="any"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label>{{ trans('file.Paying Amount') }} *</label>
                            <input type="text" name="edit_amount" class="form-control" step="any" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Change') }} : </label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Paid By') }}</label>
                            <select name="edit_paid_by_id" class="form-control selectpicker">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                                @if ($lims_reward_point_setting_data->is_active)
                                    <option value="7">Points</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label> {{ trans('file.Gift Card') }} *</label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control"
                            data-live-search="true" data-live-search-style="begins" title="Select Gift Card...">
                            @foreach ($lims_gift_card_list as $gift_card)
                                <option value="{{ $gift_card->id }}">{{ $gift_card->card_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="edit-cheque">
                        <div class="form-group">
                            <label>{{ trans('file.Cheque Number') }} *</label>
                            <input type="text" name="edit_cheque_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label> {{ trans('file.Account') }}</label>
                        <select class="form-control selectpicker" name="account_id">
                            @foreach ($lims_account_list as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} [{{ $account->account_no }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('file.Payment Note') }}</label>
                        <textarea rows="3" class="form-control" name="edit_payment_note"></textarea>
                    </div>

                    <input type="hidden" name="payment_id">

                    <button type="submit" class="btn btn-primary">{{ trans('file.update') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div id="add-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Delivery') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'delivery.store', 'method' => 'post', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Delivery Reference') }}</label>
                            <p id="dr"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Sale Reference') }}</label>
                            <p id="sr"></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>{{ trans('file.Status') }} *</label>
                            <select name="status" required class="form-control selectpicker">
                                <option value="1">{{ trans('file.Packing') }}</option>
                                <option value="2">{{ trans('file.Delivering') }}</option>
                                <option value="3">{{ trans('file.Delivered') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-2 form-group">
                            <label>{{ trans('file.Delivered By') }}</label>
                            <input type="text" name="delivered_by" class="form-control">
                        </div>
                        <div class="col-md-6 mt-2 form-group">
                            <label>{{ trans('file.Recieved By') }} </label>
                            <input type="text" name="recieved_by" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.customer') }} *</label>
                            <p id="customer"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Attach File') }}</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Address') }} *</label>
                            <textarea rows="3" name="address" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Note') }}</label>
                            <textarea rows="3" name="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="reference_no">
                    <input type="hidden" name="sale_id">
                    <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    $(document).ready( function () {
    $('#sale-table').DataTable();
} );
</script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush