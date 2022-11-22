@extends('layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection

@section('style')
@endsection




@section('breadcrumb-title')
    <h3>Invoicees list</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Invoices list</li>
@endsection

@section('content')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <div class="container-fluid">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div>
                                <h4>Delivery Slips</h4>
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
                                <div class="row">
                                    <div class="col-sm-12 table-responsive">
                                        <table class="display" id="data-table">
                                            <thead class="article_view_tr_head">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Customer</th>
                                                    <th>Cash Type</th>
                                                    <th>Total Quantity</th>
                                                    <th>Invoice Status</th>
                                                    {{-- <th>Paid</th>
                                                    <th>Due</th> --}}
                                                    <th width="100px">Action</th>
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
                </div>
            </div>
            <!-- Individual column searching (text inputs) Ends-->
        </div>
    </div>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script> --}}
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
            var table = $('#data-table').DataTable({
                "order": [
                    [1, "DESC"]
                ],
                // "scrollX": true,
                "overflow-x": "auto",
                "processing": true,
                "serverside": true,
                "paging": true,
                ajax: "{{ route('delivery_slips') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        "data": "date",
                        name: 'date'
                    },
                    {
                        "data": "customer",
                        name: 'customer'
                    },
                    {
                        "data": "cash_type",
                        name: 'cash_type'
                    },
                    {
                        "data": "total_qty",
                        name: 'total_qty'
                    },
                    {
                        "data": "invoice_status",
                        name: 'invoice_status'
                    },
                    // {
                    //     "data": "paid_amount",
                    //     name: 'paid_amount'
                    // },
                    // {
                    //     "data": "due_amount",
                    //     name: 'due_amount'
                    // },

                    {
                        "data": 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],


            });

            table.ajax.reload(null, false);

        });
        //////// sweet alert ///////////
        function changeInvoiceStatus(id) {
            var status = $('#invoice_status').find(":selected").val();

            $.ajax({
                url: "{{ route('change_invoice_status') }}",
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

        
    </script>
@endsection
