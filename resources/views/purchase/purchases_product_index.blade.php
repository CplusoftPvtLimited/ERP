@extends('layout.main')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection

@section('style')
@endsection
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
                            <div class="d-flex flex-row-reverse mb-3">
                                <a class="p-1" href=""><button class="btn btn-primary">CSV</button></a>
                                <a class="p-1" href=""><button class="btn btn-warning">Export</button></a>
                                <a class="p-1" href=""><button class="btn btn-success">Add</button></a>



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

                                            <th>#</th>
                                            <!-- <th>ID</th> -->
                                            <th>Date</th>
                                            <th>Supplier</th>
                                            <th>Items</th>
                                            <th>Total Quantity</th>
                                            <th>Purchase Status</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            <th>Grand Total</th>

                                            <th>Action</th>
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
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.jsap"></script>


    <script>
        

            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('purchases.index') }}",
                columns: [
                    {
                        "id": "id", 
                    },
                    {
                        "data": "date", 
                    },
                    {
                        "data": "supplier"
                    },
                    {
                        "data": "item"
                    },
                    {
                        "data": "total_qty"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "paid_amount"
                    },
                    {
                        "data": "due_amount"
                    },
                    {
                        "data": "grand_total"
                    },
                    {
                        "data": 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });


    </script>
@endsection
