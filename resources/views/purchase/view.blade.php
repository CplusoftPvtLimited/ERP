@extends('layout.main') @section('content')

    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Purchase') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Date</label>
                                    <input type="text" class="form-control" value="{{ $purchase->date }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Total Items</label>
                                    <input type="text" class="form-control" value="{{ $purchase->item }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Total Quantity</label>
                                    <input type="text" class="form-control" value="{{ $purchase->total_qty }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Grand Total</label>
                                    <input type="text" class="form-control" value="{{ $purchase->grand_total }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Purchase Products') }}</h4>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Reference No</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Section</th>
                                    <th>Brand</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_products as $key => $product)
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $product->date }}</td>
                                    <td>{{ $product->reference_no }}</td>
                                    <td>{{ $product->manufacturer }}</td>
                                    <td>{{ $product->model }}</td>
                                    <td>{{ $product->section }}</td>
                                    <td>{{ $product->supplier }}</td>
                                    <td><button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#viewPurchaseProduct_{{ $product->id }}">View</button></td>
                                    <!-- Modal -->
                                    <div class="modal fade" id="viewPurchaseProduct_{{ $product->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{ $product->section_part }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="">Purchase Status</label>
                                                            <input type="text" class="form-control" value="{{ $product->status }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Total Quantity</label>
                                                            <input type="text" class="form-control" value="{{ $product->white_item_qty +  $product->black_item_qty}}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Black Quantity</label>
                                                            <input type="text" class="form-control" value="{{ $product->black_item_qty }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="">White Quantity</label>
                                                            <input type="text" class="form-control" value="{{ $product->white_item_qty }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Purchase Price</label>
                                                            <input type="text" class="form-control" value="{{ $product->actual_price}}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Sale Price</label>
                                                            <input type="text" class="form-control" value="{{ $product->sell_price }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="">Engine Details</label>
                                                            <input type="text" class="form-control" value="{{ $product->engine_details }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Net Unit Cost</label>
                                                            <input type="text" class="form-control" value="{{ $product->net_unit_cost }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
