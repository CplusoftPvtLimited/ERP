@extends('layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection

@section('style')
@endsection
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@section('breadcrumb-title')
    <h3>Purchases Products list</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Purchases Products list</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>All Products Stock</h4>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-row-reverse mb-3">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <a class="p-3"><button class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModal">Import</button></a>
                                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Upload
                                                                    Products
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('stock.import') }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="modal-body">

                                                                    @csrf
                                                                    <div class="row">

                                                                        <div class="col-md-5">
                                                                            <input type="file" name="file" required>
                                                                        </div>
                                                                        {{-- <div class="col-md-3">
                                                                            <button class="btn btn-primary">CSV
                                                                                Import</button>
                                                                        </div> --}}
                                                                    </div>
                                                                    {{-- <a class="p-1" href=""><button class="btn btn-primary">CSV Import</button></a> --}}

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" id="upload_btn"
                                                                        class="btn btn-primary">Upload</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>


                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="product-table">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                <table class="display" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>Product Id</th>
                                            <th>Refrence No</th>
                                            <th>White Items</th>
                                            <th>Black Items</th>
                                            {{-- <th>Purchase Price/Unit <span>(White Cash)</span></th> --}}
                                            <th>Sale Price/Unit <span>(White Cash)</span></th>
                                            {{-- <th>Purchase Price/Unit <span>(Black Cash)</span></th> --}}
                                            <th>Sale Price/Unit <span>(Black Cash)</span></th>
                                            {{-- <th>Discount</th> --}}
                                            <th>Total Quantity</th>
                                            <th style="">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Individual column searching (text inputs) Ends-->
        </div>
    </div>
    <script type="text/javascript">
        $('#upload_btn').on('click', function() {
            console.log("here");
            // $('#upload_btn').css('disabled', true);
            document.getElementById('upload_btn').style.disabled = true;
            $("form").submit();
        });

        $(document).ready(function() {
            console.log('here');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#data-table').DataTable({
                "order": [
                    [1, "DESC"]
                ],
                "scrollX": true,
                "processing": true,
                "serverside": true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    {
                        "data": "product_id",
                        name: 'product_id'
                    },
                    {
                        "data": "reference_no",
                        name: 'reference_no'
                    },
                    {
                        "data": "white_items",
                        name: 'white_items'
                    },
                    {
                        "data": "black_items",
                        name: 'black_items'
                    },
                    // {
                    //     "data": "unit_purchase_price_of_white_cash",
                    //     name: 'unit_purchase_price_of_white_cash'
                    // },
                    {
                        "data": "unit_sale_price_of_white_cash",
                        name: 'unit_sale_price_of_white_cash'
                    },
                    // {
                    //     "data": "unit_purchase_price_of_black_cash",
                    //     name: 'unit_purchase_price_of_black_cash'
                    // },
                    {
                        "data": "unit_sale_price_of_black_cash",
                        name: 'unit_sale_price_of_black_cash'
                    },
                    // {
                    //     "data": "discount",
                    //     name: 'discount'
                    // },
                    {
                        "data": "total_qty",
                        name: 'total_qty'
                    },
                    {
                        "data": 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });

        //////// sweet alert ///////////
        function deleteStock(id) {
            console.log(id);
            // href="deletePurchase/' . $row['id'] . '"
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.ajax({
                        method: "GET",
                        url: "/product/deleteStock/" + id,
                        data: {
                            id: id
                        },
                        success: function($data) {
                            location.reload();
                        }
                    });
                }
            });

        }
    </script>
@endsection
