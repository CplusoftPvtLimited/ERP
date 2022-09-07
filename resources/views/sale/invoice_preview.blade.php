@extends('layout.main') 

@section('content')
<div class="page-content container mt-2 p-4">
    <div class="card p-5">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Invoice
        </h1>
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                            <img src="images/logo.png" alt="" width="200">
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
                <div class="row">
                    <div class="col-sm-5">
                        <div>
                            <span class="text-600 text-110">Customer : </span>{{$customer->name}}
                            <hr style="border: 2px solid black; opacity:1">
                        </div>
                        <div>
                            <span class="">Company : </span>{{$customer->company_name}}
                        </div>
                        <div>
                            <span class="">Tax number : </span>{{$customer->tax_no}}
                        </div>
                        <div>
                            <span class="">Mobile number : </span>{{$customer->phone_number}}
                        </div>
                        <div>
                            <span class="">Address : </span>{{$customer->address}}
                        </div>
                        <div>
                            <span class="">Email : </span>{{$customer->email}}
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-5 offset-2">
                        <hr class="d-sm-none" />
                        <div class="text-black-m2">
                            <div>
                            <span class="text-600 text-110">Document Details:</span>
                            <hr style="border: 2px solid black;opacity:1">
                            </div>
                            <div>
                                <span class="">Document Date : </span>{{$invoice->created_at->format('d-m-Y')}}
                            </div>
                            <div>
                                <span class="">Note : </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="mt-4">
                    <div class="row">
                <hr style="border: 3px solid black;opacity:1;margin:0px;width:100%;">

                    </div>

                    <div class="row text-600 text-white pm-25 pb-3" style="background:lightgray;">
                        <div class="d-none d-sm-block col-1">#</div>
                        <div class="col-8 col-sm-4">Description</div>
                        <div class="d-none d-sm-block col-3 col-sm-1">Qty</div>
                        <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                        <div class="d-none d-sm-block col-sm-2">Unit Tax</div>

                        <div class="col-2">Total Amount</div>
                    </div>

                    <div class="text-95 text-secondary-d3">
                        @php $i = 0;
                        @endphp
                        @foreach($products as $product_id)
                        @php 
                        $product = App\Product::Find($product_id->product_id);
                        $tax = App\Tax::Find($product->tax_id);
                        @endphp
                        <div class="row mb-2 mb-sm-0 py-25">
                            <div class="d-none d-sm-block col-1">{{$i+1}}</div>
                            <div class="col-8 col-sm-4">{{$product->name}}</div>
                            <div class="d-none d-sm-block col-1">{{$product_id->qty}}</div>
                            <div class="d-none d-sm-block col-2 text-95">${{$product->price}}</div>
                            @if($tax)
                                @php $taxes = ($tax->rate * $product->price)/100; @endphp
                            <div class="d-none d-sm-block col-2 text-95">${{$taxes}}</div>
                            <div class="col-2 text-secondary-d2">${{($product->price +$taxes)  * $product_id->qty}}</div>
                            @else
                            <div class="d-none d-sm-block col-2 text-95">$0</div>
                            <div class="col-2 text-secondary-d2">${{$product->price  * $product_id->qty}}</div>
                            @endif
                        </div>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                    </div>

                    <div class="row border-b-2 brc-default-l2"></div>

                    <!-- or use a table instead -->
                    <!--
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                    <thead class="bg-none bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th width="140">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                        <tr></tr>
                        <tr>
                            <td>1</td>
                            <td>Domain registration</td>
                            <td>2</td>
                            <td class="text-95">$10</td>
                            <td class="text-secondary-d2">$20</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
            -->

                    <div class="row mt-3">

                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            
                        </div>

                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                    <hr style="border: 2px solid black;opacity:1">
                            <div class="row my-2">
                    <!-- <hr style="border: 2px solid black;opacity:1"> -->

                                <div class="col-7 ">
                                    
                                    SubTotal
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">${{$invoice->total_price}}</span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-7 ">
                                    Discount 
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$invoice->order_discount}}</span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-7 ">
                                    Tax
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$invoice->order_tax}}</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-7 ">
                                    Shipping Cost
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$invoice->shipping_cost}}</span>
                                </div>
                            </div>

                            <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                <div class="col-7 ">
                                    Net To Pay
                                </div>
                                <div class="col-5">
                                    <span class="text-150 text-success-d3 opacity-2">${{$invoice->grand_total}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:100px;">
        <div class="col-12">

        </div>
    </div>
</div>
</div>

<footer style="position: fixed;bottom: 0;height: 75px;width:100%;background: white;border-top: 5px solid #6699cc;">
    <div class="row p-2">
        <div class="col-6">
            <h1><i class="bi bi-file-earmark-text"></i><i class="fa-solid fa-file-lines"></i> Invoice</h1>
        </div>
        <div class="col-6">
            @if($invoice->status == 1)
            <a href="{{route('sales.salesInvoices')}}" class="btn btn-primary">Return</a>
            <a href="{{ route('sales.changeInvoiceStatus',[$invoice->id,3]) }}" class="btn btn-danger">Partial</a>
            <a href="{{ route('sales.changeInvoiceStatus',[$invoice->id,2]) }}" class="btn btn-success">Paid</a>
            <!-- <a href="{{route('sales.negotiateEstimate',$invoice->id)}}" class="btn btn-secondary">Negotiate</a> -->
            @elseif($invoice->status == 3)
            <a href="{{route('sales.salesInvoices')}}" class="btn btn-primary">Return</a>
            <a href="{{ route('sales.changeInvoiceStatus',[$invoice->id,2]) }}" class="btn btn-danger">Paid</a>
            <!-- <a href="{{route('sales.acceptEstimate',$invoice->id)}}" class="btn btn-success">Accept</a> -->
            <!-- <a href="{{route('sales.negotiateEstimate',$invoice->id)}}" class="btn btn-secondary">Negotiate</a> -->
            @elseif($invoice->status == 2)
            <a href="{{route('sales.salesInvoices')}}" class="btn btn-primary">Return</a>
            <!-- <a href="{{route('sales.reactivateEstimate',$invoice->id)}}" class="btn btn-secondary">Reactivate</a> -->
            <!-- <a href="{{route('sales.acceptEstimate',$invoice->id)}}" class="btn btn-success">Accept</a>
            <a href="{{route('sales.negotiateEstimate',$invoice->id)}}" class="btn btn-secondary">Negotiate</a> -->
            @endif
        </div>
    </div>
</footer>


@endsection
