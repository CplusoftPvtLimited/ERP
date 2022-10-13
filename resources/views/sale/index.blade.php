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
    <h3>Purchases list</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Purchases list</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div>
                                <h4>Sales</h4>
                            </div>
                            <div class="d-flex flex-row-reverse mb-3">
                                
                                <a class="p-1" href="{{ route('sales.create') }}"><button class="btn btn-success"><i
                                            class="fa fa-plus"></i> Add</button></a>
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

                                <table class="table" id="data-table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Cash Type</th>
                                            <th>Total Quantity</th>
                                            {{-- <th>Shipping Cost</th>
                    <th>Discount</th> --}}
                                            {{-- <th>Total Bill</th> --}}
                                            <th width="150px">Status</th>
                                            <th width="180px">Action</th>
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

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            console.log('here');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#data-table').DataTable({
                "ordering": false,
                "processing": true,
                "serverside": true,
                // "scrollX": true,
                ajax: "{{ route('sales.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        "data": "date",
                        name: 'date'
                    },
                    {
                        "data": "customer_id",
                        name: 'customer_id'
                    },
                    {
                        "data": "cash_type",
                        name: 'cash_type'
                    },
                    {
                        "data": "total_qty",
                        name: 'total_qty'
                    },
                    // {
                    //     "data": "shipping_cost",
                    //     name: 'shipping_cost'
                    // },
                    // {
                    //     "data": "discount",
                    //     name: 'discount'
                    // },
                    // {
                    //     "data": "total_bill",
                    //     name: 'total_bill'
                    // },
                    {
                        "data": "sale_status",
                        name: 'sale_status'
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

        function changeSaleStatus(id) {
            var status = $('#sale_status').find(":selected").val();

            $.ajax({
                url: "{{ route('change_sale_status') }}",
                method: "get",
                data: {
                    id: id,
                    status: status,
                },
                success: function(data) {
                    location.reload();
                }
            });
        }
        //////// sweet alert ///////////
        function deletePurchase(id) {
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
                        url: "/deletePurchase/" + id,
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
