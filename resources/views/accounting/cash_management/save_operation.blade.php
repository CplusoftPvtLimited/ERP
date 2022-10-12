<div class="card-body m-0" style="margin: 0px;">
    <p class="italic"><small>{{ trans('file.The field labels marked with * are required input fields') }}.</small></p>
    @if (Session::has('error'))
        <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
    @endif
    @if (Session::has('success'))
        <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        $date = Carbon\Carbon::now();
        $current_date = $date->format('Y-m-d');
        // dd($current_date);
    @endphp
    <form action="{{ route('balanceSheet.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="other_data">
                </div>
                <div class="row">
                    <div class="col-4">
                        <input type="hidden" name="balance_type" id="balance_type" value={{ $balance_type }}>
                        <div class="form-group">
                            <h6>Type Payment *</h6>
                            <input type="radio" id="debit" name="transaction_type" value="debit"
                                data-href="{{ route('get_categories_from_type') }}">
                            <label for="debit">Debit</label>
                            <input type="radio" id="credit" name="transaction_type" value="credit"
                                data-href="{{ route('get_categories_from_type') }}">
                            <label for="credit">Credit</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Category *</h6>
                            <select name="category_id" id="category_id" class="selectpicker form-control"
                                data-live-search="true" data-live-search-style="begins" title="Select Category...">
                                @foreach ($bal_categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->category }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Mode Payment *</h6>
                            <input type="radio" id="cash_payment" name="mode_payment"
                                class="form_control operation_mode_payment" value="cash">
                            <label for="cash">Cash</label>
                            <input type="radio" id="cheque" name="mode_payment"
                                class="form_control operation_mode_payment" value="cheque">
                            <label for="cheque">Cheque</label>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Amount *</h6>
                            <div class="input-group">
                                <div class="input-group-text">TND</div>
                                <input type="number" name="amount" id="total_amount" step="any" maxlength="191"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Settelement Date *</h6>
                            <input type="date" name="settlement_date" id="settlement_date" max={{ $current_date }}
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-4" id="carrier">
                        <h6>Carrier *</h6>
                        <input type="text" name="carrier" id="cheque_carrier" class="form-control">
                    </div>
                    <div class="col-4" id="account_source">
                        <div class="form-group">
                            <h6>Account Source *</h6>
                            <select name="account_source" id="account_source_overview" class="form-control">
                                <option value="">--Select One--</option>
                                @foreach ($source_accounts as $item)
                                    <option value="{{ $item->id }}">{{ $item->account_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="check_content">
                    <div class="col-4" id="bank">
                        <div class="form-group">
                            <h6>Bank *</h6>
                            <select name="bank_id" id="bank_id" class="form-control">
                                <option value="">--Select One--</option>
                                @foreach ($banks as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4" id="check_number">
                        <div class="form-group">
                            <h6>Cheque Number *</h6>
                            <input type="text" name="cheque_number" id="cheque_number" class="form-control">
                        </div>
                    </div>
                    <div class="col-4" id="due_date">
                        <h6>Due Date *</h6>
                        <input type="date" name="due_date" id="cheque_due_date" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h6>Note</h6>
                        <textarea name="note" id="note" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="button" id="save_operation" class="btn btn-success"
                        style="width:100px">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        var type = $('.operation_mode_payment:checked').val();
        var trans = $('input[name="transaction_type"]:checked').val();
        // alert(trans);
        $("#due_date").css('display', "none");
        $("#check_number").css('display', "none");
        $("#account_source").css('display', "none");
        $("#bank").css('display', "none");
        $("#carrier").css('display', "none");
        $("#cash_payment").attr('checked', 'checked');
        $("#debit").attr('checked', 'checked');

        $('.operation_mode_payment').on("click", function() {
            console.log('jdhjhd' + type);
            var type = $('.operation_mode_payment:checked').val();
            trans = $('input[name="transaction_type"]:checked').val();
            // alert(type);
            if (type == "cash") {
                $("#due_date").css('display', "none");
                $("#check_number").css('display', "none");
                $("#bank").css('display', "none");
                $("#carrier").css('display', "none");
                $("#account_source").css('display', "none");
            }
            if (type == "cheque" && trans == "credit") {
                $("#due_date").css('display', "");
                $("#check_number").css('display', "");
                $("#bank").css('display', "");
                $("#carrier").css('display', "");
                $("#account_source").css('display', "none");

            }
            if ((type == "cheque" && trans == "debit")) {
                $("#due_date").css('display', "");
                $("#check_number").css('display', "");
                $("#account_source").css('display', "");
                $("#bank").css('display', "none");
                $("#carrier").css('display', "none");
            }
        });
        $('input[name="transaction_type"]').on("click", function() {
            $("#due_date").css('display', "none");
            $("#check_number").css('display', "none");
            $("#bank").css('display', "none");
            $("#carrier").css('display', "none");
            $("#account_source").css('display', "none");
            $("#cash_payment").prop('checked', true);
            $("#cheque").prop('checked', false);
            let transaction_type = $(this).val();
            let url = $(this).attr('data-href');
            // alert(url);
            getCategories(url, transaction_type);
        });

        function getCategories(url, transaction_type) {
            $.get(url + '?transaction_type=' + transaction_type, function(data) {
                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html +=
                        `<option value="${value.id}">${value.category }</option>`;
                });
                $('#category_id').html(view_html);
                $("#category_id").val(4);
                $("#category_id").selectpicker("refresh");
            })
        }

        $('#save_operation').on('click', function() {
            var transaction_type = $('input[name="transaction_type"]:checked').val();
            var category_id = $('#category_id').val();
            var mode_payment = $('.operation_mode_payment:checked').val();
            var amount = $('#total_amount').val();
            var settlement_date = $('#settlement_date').val();
            var carrier = $('#cheque_carrier').val();
            var account_source = $('#account_source_overview').val();
            var bank_id = $('#bank_id').val();
            var cheque_number = $('#cheque_number').val();
            var due_date = $('#cheque_due_date').val();
            var note = $('#note').val();
            var balance_type = $('#balance_type').val();
            var ajax = 1;
            if (mode_payment == "cash") {
                if (amount != "" && amount <= 0) {
                    Swal.fire({
                        title: 'Error',
                        text: "Amount Must be greater Than 0",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                } else if (category_id != "" && amount != "" && settlement_date != "") {
                    $.ajax({
                        url: "{{ route('balanceSheet.store') }}",
                        type: "POST",
                        data: {
                            transaction_type: transaction_type,
                            category_id: category_id,
                            mode_payment: mode_payment,
                            amount: amount,
                            settlement_date: settlement_date,
                            carrier: carrier,
                            account_source: account_source,
                            bank_id: bank_id,
                            cheque_number: cheque_number,
                            due_date: due_date,
                            note: note,
                            balance_type: balance_type,
                            ajax: ajax,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.data.id) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let url =
                                            "{{ route('cash.management.balance') }}";
                                        let operation_save = "1";
                                        window.location = url + '?balance_type=' +
                                            balance_type + '&operation=' +
                                            operation_save;
                                    }
                                });

                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "Something Went Wrong",
                                    icon: 'warning',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: "Please Fill Out the Required Fields",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }


            } else if (mode_payment == "cheque" && transaction_type == "debit") {
                if (amount != "" && amount <= 0) {
                    Swal.fire({
                        title: 'Error',
                        text: "Amount Must be greater Than 0",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                } else if (category_id != "" && amount != "" && settlement_date != "" &&
                    account_source !=
                    "" && cheque_number != "" && due_date != "") {
                    $.ajax({
                        url: "{{ route('balanceSheet.store') }}",
                        type: "POST",
                        data: {
                            transaction_type: transaction_type,
                            category_id: category_id,
                            mode_payment: mode_payment,
                            amount: amount,
                            settlement_date: settlement_date,
                            carrier: carrier,
                            account_source: account_source,
                            bank_id: bank_id,
                            cheque_number: cheque_number,
                            due_date: due_date,
                            note: note,
                            balance_type: balance_type,
                            ajax: ajax,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.data.id) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let url =
                                            "{{ route('cash.management.balance') }}";
                                        let operation_save = "1";
                                        window.location = url + '?balance_type=' +
                                            balance_type + '&operation=' +
                                            operation_save;
                                    }
                                });

                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "Something Went Wrong",
                                    icon: 'warning',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: "Please Fill Out the Required Fields",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }


            } else if (mode_payment == "cheque" && transaction_type == "credit") {
                if (amount != "" && amount <= 0) {
                    Swal.fire({
                        title: 'Error',
                        text: "Amount Must be greater Than 0",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                } else if (category_id != "" && amount != "" && settlement_date != "" && carrier !=
                    "" &&
                    cheque_number != "" && due_date != "" && bank_id != "'") {
                    $.ajax({
                        url: "{{ route('balanceSheet.store') }}",
                        type: "POST",
                        data: {
                            transaction_type: transaction_type,
                            category_id: category_id,
                            mode_payment: mode_payment,
                            amount: amount,
                            settlement_date: settlement_date,
                            carrier: carrier,
                            account_source: account_source,
                            bank_id: bank_id,
                            cheque_number: cheque_number,
                            due_date: due_date,
                            note: note,
                            balance_type: balance_type,
                            ajax: ajax,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.data.id) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let url =
                                            "{{ route('cash.management.balance') }}";
                                        let operation_save = "1";
                                        window.location = url + '?balance_type=' +
                                            balance_type + '&operation=' +
                                            operation_save;
                                    }
                                });

                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "Something Went Wrong",
                                    icon: 'warning',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: "Please Fill Out the Required Fields",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }


            }
        });
    });
</script>
