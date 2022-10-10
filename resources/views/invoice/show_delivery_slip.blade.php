@extends('layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <link rel="stylesheet" href="{{ asset('css/purchase_create.css') }}">
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-header">
                            <h3>Delivery Slip</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <span></span>
                            </div>

                            <form action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                {{-- {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!} --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="invoice-label">{{ trans('file.Date') }}</label>
                                                    <input type="text" id="product_sale_date" name="date"
                                                        class="form-control" value="{{ $sale->date }}" readonly />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="invoice-label">{{ trans('file.Customer') }}</label>

                                                    @if ($sale->customer_id == 1)
                                                        <input type="text" class="form-control" value="Walkin" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="invoice-label">{{ trans('file.Cash Type') }}</label>
                                                    @if ($sale->cash_type == 'white')
                                                        <input type="text" class="form-control" value="White Cash"
                                                            readonly>
                                                    @else
                                                        <input type="text" class="form-control" value="Black Cash"
                                                            readonly>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="invoice-label">{{ trans('file.Invoice Status') }}</label>
                                                    @if ($sale->status == 'paid')
                                                        <input type="text" class="form-control" value="Paid" readonly>
                                                    @else
                                                        <input type="text" class="form-control" value="UnPaid" readonly>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4" id="sale_order_table">
                                    <div class="col-md-12">
                                        <h5 id="order-table-header"></h5>
                                        <div class="table-responsive mt-3">
                                            <table id="myTable" class="table table-hover order-list table-responsive">
                                                <thead>
                                                    @if ($sale->cash_type == 'white')
                                                        <tr id="">
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Quantity') }}</th>

                                                            <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                                                            <th>{{ trans('file.Discount (%)') }} <span>Optional</span></th>

                                                            <th style="width:200px">{{ trans('file.VAT %') }}</th>

                                                            <th>{{ trans('file.Total (With Discount) Excluding Vat') }}
                                                            </th>

                                                        </tr>
                                                    @else
                                                        <tr id="">
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Quantity') }}</th>
                                                            <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                                                            <th>{{ trans('file.Discount (%)') }}</th>

                                                            <th>{{ trans('file.Total (With Discount)') }}</th>
                                                            <th>{{ trans('file.Total (Without Discount)') }}</th>
                                                        </tr>
                                                    @endif
                                                </thead>
                                                <tbody>
                                                    @foreach ($sale_products as $sale_product)
                                                        @if ($sale->cash_type == 'white')
                                                            <tr id="item_row_"{{ $sale_product->id }}>
                                                                <td>
                                                                    {{ $sale_product->reference_no }}
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        id="sale_item_qty_{{ $sale_product->id }}"
                                                                        name="sale_item_qty[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->quantity }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->sale_price }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->discount }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any" id="vat_{{ $sale_product->id }}"
                                                                        name="vat[]" value="{{ $sale_product->vat }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->total_with_discount }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @else
                                                            <tr id="item_row_"{{ $sale_product->id }}>
                                                                <td>
                                                                    {{ $sale_product->reference_no }}
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        id="sale_item_qty_{{ $sale_product->id }}"
                                                                        name="sale_item_qty[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->quantity }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->sale_price }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->discount }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->total_with_discount }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group mb-3">
                                                        
                                                                        <input type="text" value="{{ $sale_product->total_without_discount }}" class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                                        <span class="input-group-text"><b>TND</b></span>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if ($sale->cash_type == 'white')
                                    <div class="row p-5" id="total_sale_calculations">
                                        <div class="col-md-12">
                                            <div class="row total-calculations">
                                                <div class="col-md-4">
                                                    <h5>Total Exculding Vat (Before Discount)</h5>
                                                </div>
                                                <div class="col-md-3">
                                                    
                                                        <div class="input-group mb-3">
                                                        
                                                            <input type="text" value="{{ $sale->sale_entire_total_exculding_vat }}" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)" readonly>
                                                            <span class="input-group-text"><b>TND</b></span>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row total-calculations">
                                                <div class="col-md-4">
                                                    <h5>Discount</h5>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group mb-3">
                                                        
                                                        <input type="text" value="{{ $sale->discount }}" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                        <span class="input-group-text"><b>TND</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row total-calculations">
                                                <div class="col-md-4">
                                                    <h5>Vat</h5>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" value="{{ $sale->entire_vat }}" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                        <span class="input-group-text"><b>TND</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row total-calculations">
                                                <div class="col-md-4">
                                                    <h5>Tax Stamp</h5>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" value="{{ $sale->tax_stamp }}" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                        <span class="input-group-text"><b>TND</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row total-calculations">
                                                <div class="col-md-4">
                                                    <h5>Total To Be Paid</h5>
                                                </div>
                                                <div class="col-md-3">
                                                    
                                                    <div class="input-group mb-3">
                                                        
                                                        <input type="text" value="{{ $sale->total_bill }}" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" readonly>
                                                        <span class="input-group-text"><b>TND</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{ url('delivery_slips') }}" class="btn btn-primary">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
