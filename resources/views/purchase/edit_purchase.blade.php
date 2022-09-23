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
                                <div class="col-md-4">
                                    <label for=""><b>Date</b></label>
                                    <p>{{ $purchase->date }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label for=""><b>Total Items</b></label>
                                    <p>{{ $purchase->item }}</p>
                                    
                                </div>
                                <div class="col-md-4">
                                    <label for=""><b>Total Quantity</b></label>
                                    <p>{{ $purchase->total_qty }}</p>
                                    
                                </div>
                                <div class="col-md-4">
                                    <label for=""><b>Grand Total</b></label>
                                    <p>{{ $purchase->grand_total }}</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4><b>{{ trans('file.Purchase Products') }}</b></h4>
                        </div>
                        <table class="table" id="purchase-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Reference No</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Section</th>
                                    <th>Brand</th>
                                    <th>Supplier</th>
                                    <th>Purchase Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_products as $key => $product)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $product->date }}</td>
                                    <td>{{ $product->reference_no }}</td>
                                    <td>{{ $product->manufacturer }}</td>
                                    <td>{{ $product->model }}</td>
                                    <td>{{ $product->section }}</td>
                                    <td>{{ $product->brand }}</td>
                                    <td>{{ $product->supplier }}</td>
                                    <td>
                                            <input type="hidden" name="" value="{{ $product->id }}">
                                            @if($product->status == 'ordered')
                                            <select name="product_purchase_status" id="status_{{$product->id}}" class="form-control">
                                                <option value="ordered"
                                                    {{ $product->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                                <option value="received"
                                                    {{ $product->status == 'received' ? 'selected' : '' }}>Received
                                                </option>
                                            </select>
                                            @else
                                            {{$product->status}}
                                            @endif
                                        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
                                        <script>
                                            $('#status_'+{{$product->id}}).on('change', function(){
                                                var val = this.value;
                                                $.ajax({
                                                    method: "get",
                                                    url: "{{ route('update_purchase') }}",
                                                    data: {
                                                        id: "{{$product->id}}",
                                                        status: val
                                                    },
                                                    success: function(data){
                                                        location.reload();
                                                    }

                                                });
                                            });
                                        </script>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#viewPurchaseProduct_{{ $product->id }}"><i class="fa fa-eye"></i></button>
                                            </div>
                                            <div class="col-sm-4">
                                                <a href="{{ route('delete_purchase',[$purchase->id,$product->id]) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Modal -->
                                    <div class="modal fade" id="viewPurchaseProduct_{{ $product->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3>Product Detail ({{ $product->section_part }} <span>Article Number</span>)</h3>
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Purchase Status</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->status }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Total Quantity</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->white_item_qty +  $product->black_item_qty}}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Black Quantity</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->black_item_qty }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">White Quantity</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->white_item_qty }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Purchase Price</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->actual_price}}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Sale Price</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->sell_price }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="" class="view-edit-purchase">Engine Details</label>
                                                            <input type="text" class="form-control view-edit-purchase-input" value="{{ $product->engine_details }}" readonly>
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
                                </tr>
                                    
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
    <script>
        $('#purchase-table').DataTable( {
            "processing": true,
            "searching" : true,
        });
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
