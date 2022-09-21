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
                            <h4><b>{{ trans('file.Purchase') }}</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Supplier/Brand name</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->purchase->brand->brandName) ? $get_product->purchaseProduct->purchase->brand->brandName : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Purchase Created At</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->purchase->date) ? $get_product->purchaseProduct->purchase->date : '' }}" readonly>

                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Product Purchase Reference No</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->purchase->purchaseProduct->reference_no) ? $get_product->purchaseProduct->purchase->purchaseProduct->reference_no : '' }}" readonly>

                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Product Id</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->purchase->purchaseProduct->product_id) ? $get_product->purchaseProduct->purchase->purchaseProduct->product_id : '' }}" readonly>
                                </div>
                            </div> 
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Total Quntity</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->qty) ? $get_product->purchaseProduct->qty : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Purchase Status</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->status) ? $get_product->purchaseProduct->status : '' }}" readonly>

                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Manufacturer Name</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->manufacture->manuName) ? $get_product->purchaseProduct->manufacture->manuName : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">product Supplier Name</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->supplier->brandName) ? $get_product->purchaseProduct->supplier->brandName : '' }}" readonly>
                                </div>
                            </div> 
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Model Id</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->model_id) ? $get_product->purchaseProduct->model_id : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Engine Details</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->engine_details) ? $get_product->purchaseProduct->engine_details : '' }}" readonly>

                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Engine Linkage Target Id</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->eng_linkage_target_id) ? $get_product->purchaseProduct->eng_linkage_target_id : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Assembly Group Node Id</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->purchaseProduct->assembly_group_node_id) ? $get_product->purchaseProduct->assembly_group_node_id : '' }}" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">White Cash Items</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->white_items) ? $get_product->white_items : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Black Cash Items</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->black_items) ? $get_product->black_items : '' }}" readonly>

                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Unit Actual Price</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->unit_actual_price) ? $get_product->unit_actual_price : '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Unit Sale price</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->unit_sale_price) ? $get_product->unit_sale_price : '' }}" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="view-edit-purchase">Total Product Quantity</label>
                                    <input type="text" class="form-control view-edit-purchase-input" value="{{ isset($get_product->total_qty) ? $get_product->total_qty : '' }}" readonly>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection
@push('scripts')
<script>
    $('#purchase-table').DataTable( {
        "processing": true,
        "searching" : true,
    });
</script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
