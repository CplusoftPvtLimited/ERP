@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="text-left">{{trans('file.Sales Invoices List')}}</h3>
            </div>
            </div>
            {!! Form::close() !!}
        </div>
            <!-- <a href="{{route('sales.create')}}" class="btn btn-info ml-4"><i class="dripicons-plus"></i> {{trans('file.Add')}}</a>&nbsp; -->
            <!-- <a href="{{url('sales/sale_by_csv')}}" class="btn btn-primary"><i class="dripicons-copy"></i> {{trans('file.Import Sale')}}</a> -->
    </div>
    <div class="card p-3">
    <div class="table-responsive">
        <table id="sale-table" class="table sale-list" style="width: 100%">
            <thead>
                <tr>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Invoice Number')}}</th>
                    <!-- <th>{{trans('file.Biller')}}</th> -->
                    <th>{{trans('file.Customer')}}</th>
                    <!-- <th>{{trans('file.Sale Status')}}</th> -->
                    <!-- <th>{{trans('file.Payment Status')}}</th> -->
                    <th>{{trans('file.Grand Total')}}</th>
                    <!-- <th>{{trans('file.Payment Method')}}</th> -->

                    <!-- <th>{{trans('file.Paid')}}</th> -->
                    <th>{{trans('file.Status')}}</th>
                    <th>{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                @php 
                $customer=App\Customer::find($invoice->customer_id); @endphp
                <tr>
                    <td>{{$invoice->created_at->format('d-m-Y')}}</td>
                    <td>{{$invoice->invoice_number}}</td>
                    <td>{{$customer->name}}</td>
                    <td>{{$invoice->grand_total}}</td>
                    @if($invoice->status == 1)
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.Un Paid')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                <a class="btn btn-link" href="{{ route('sales.changeInvoiceStatus',[$invoice->id,3]) }}">{{trans('file.Partial')}}</a>
                                </li>
                                <li>
                                    <a class="btn btn-link" href="{{ route('sales.changeInvoiceStatus',[$invoice->id,2]) }}"> {{trans('file.Paid')}}</a>
                                </li>
                            </ul>
                        </div></td>
                    @elseif($invoice->status == 2)
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.Paid')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <!-- <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                <a class="btn btn-link" href="{{ route('sales.cancelEstimate',$invoice->id) }}">{{trans('file.Partial')}}</a>
                                </li>
                                <li>
                                    <a class="btn btn-link" href="{{ route('sales.acceptEstimate',$invoice->id) }}"> {{trans('file.Paid')}}</a>
                                </li> -->
                            </ul>
                        </div></td>
                    @else
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.Partial')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <!-- <li>
                                <a class="btn btn-link" href="{{ route('sales.cancelEstimate',$invoice->id) }}">{{trans('file.Partial')}}</a>
                                </li> -->
                                <li>
                                    <a class="btn btn-link" href="{{ route('sales.changeInvoiceStatus',[$invoice->id,2]) }}"> {{trans('file.Paid')}}</a>
                                </li>
                            </ul>
                        </div></td>
                    @endif
                    <td>
                    @if($invoice->status != 2)
                        <a class="btn" href="{{route('sales.editInvoice',$invoice->id)}}"><i class="dripicons-document-edit"></i></a>
                        @endif
                        <!-- <a class="btn" href="">$</a> -->
                        <!-- <a class="btn" href=""><i class="fa fa-file-pdf-o"></i></a> -->
                        <!-- <a class="btn" href="{{route('sales.approveEstimate',$invoice->id)}}"><i class="fa fa-eye"></i></a> -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-refresh"></i>
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                @if($invoice->status == 2)
                                <li>
                                <a class="btn btn-link" href="{{ route('sales.reactivateEstimate',$invoice->id) }}">{{trans('file.Delivery Slip')}}</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</section>



@endsection