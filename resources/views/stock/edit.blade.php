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
                            <h4>{{ trans('file.Add Purchase') }}</h4>
                        </div>
                        <div class="card-body">
                            {{-- {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!} --}}
                            <form method="POST" action="{{ route('stockManagement.store') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="other_data"></div>
                                        <input type="hidden" name="id" value="{{$product_stock->id}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Retailer Id</label>
                                                    <input type="text" id="" name="retailer_id"
                                                        class="form-control" value="{{ $product_stock->retailer_id }}"
                                                        readonly />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Refrence No</label>
                                                    <input type="text" id="" name="reference_no"
                                                        class="form-control" value="{{ $product_stock->reference_no }}"
                                                        readonly />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>White cash Items</label>
                                                    <input type="number" name="white_items" class="form-control"
                                                        value="{{ $product_stock->white_items }}" placeholder=""
                                                        min="1" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Black cash Items</label>
                                                    <input type="number" name="black_items" class="form-control"
                                                        value="{{ $product_stock->black_items }}" placeholder=""
                                                        min="1" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Actual Price Per Unit</label>
                                                    <input type="number" name="unit_actual_price" class="form-control"
                                                        value="{{ $product_stock->unit_actual_price }}" placeholder=""
                                                        min="1" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Sale Price Per Unit</label>
                                                    <input type="number" name="unit_sale_price" class="form-control"
                                                        value="{{ $product_stock->unit_sale_price }}" placeholder=""
                                                        min="1" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Total Quantity</label>
                                                    <input type="text" name="total_quantity" class="form-control"
                                                        value="{{ $product_stock->total_qty }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-11">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            {{-- <button type="button" class="btn btn-primary">Primary</button> --}}
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
    <script type="text/javascript"></script>


    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
