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
                <h3 class="text-left">{{trans('file.Sales Delivery Slips')}}</h3>
            </div>
            </div>
            {!! Form::close() !!}
        </div>
            <!-- <a href="{{route('sales.create')}}" class="btn btn-info ml-4"><i class="dripicons-plus"></i> {{trans('file.Add')}}</a>&nbsp; -->
            <!-- <a href="{{url('sales/sale_by_csv')}}" class="btn btn-primary"><i class="dripicons-copy"></i> {{trans('file.Import Sale')}}</a> -->
    </div>
    <div class="card p-3">
    <div class="table-responsive">
        <table id="delivery-table" class="table sale-list" style="width: 100%">
            <thead>
                <tr>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Estimate Ref. No.')}}</th>
                    <th>{{trans('file.Invoice Number')}}</th>

                    <!-- <th>{{trans('file.Biller')}}</th> -->
                    <th>{{trans('file.Customer')}}</th>
                    <!-- <th>{{trans('file.Sale Status')}}</th> -->
                    <!-- <th>{{trans('file.Payment Status')}}</th> -->
                    <th>{{trans('file.Grand Total')}}</th>
                    <!-- <th>{{trans('file.Payment Method')}}</th> -->

                    <!-- <th>{{trans('file.Paid')}}</th> -->
                    <th>{{trans('file.Payment Method')}}</th>

                    <!-- <th>{{trans('file.Status')}}</th> -->

                    <th>{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryslips as $deliveryslip)
                @php
                    $sale = App\Sale::find($deliveryslip->sale_id);
                    $invoice = App\SalesInvoice::find($deliveryslip->invoice_id);
                    if($sale)
                    $customer=App\Customer::find($sale->customer_id);
                    else
                    $customer=App\Customer::find($invoice->customer_id);
                    @endphp


                    <tr>
                            <td>{{$deliveryslip->created_at->format('d-m-Y')}}</td>
                            @if($sale)
                            <td>{{$sale->reference_no}}</td>
                            @else
                            <td>N/A</td>
                            @endif
                            @if($invoice)
                            <td>{{$invoice->invoice_number}}</td>
                            @else
                            <td>N/A</td>
                            @endif
                            <td>{{$customer->name}}</td>
                            @if($sale)
                            <td>{{$sale->grand_total}}</td>
                            @else
                            <td>{{$invoice->grand_total}}</td>
                            @endif
                            @if($sale)
                            @if($sale->payment_method == 1)
                            <td>{{trans('file.White Cash')}}</td>
                            @elseif($sale->payment_method == 2)
                            <td>{{trans('file.Black Cash')}}</td>
                            @endif
                            @endif

                            @if($invoice)
                            @if($invoice->payment_method == 2)
                            <td>{{trans('file.Black Cash')}}</td>
                            @elseif($invoice->payment_method == 1)
                            <td>{{trans('file.White Cash')}}</td>
                            @endif
                            @endif
                            <td>
                                <a class="btn" href="{{route('sales.generateDeliveryPDF',$deliveryslip->id)}}"><i class="fa fa-file-pdf-o"></i></a>
                                <a class="btn" href="{{route('sales.deliverySlipPreview',$deliveryslip->id)}}"><i class="fa fa-eye"></i></a>
                            </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</section>



@endsection
@push('scripts')
<script>
    $(document).ready( function () {
    $('#delivery-table').DataTable();
} );
</script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush