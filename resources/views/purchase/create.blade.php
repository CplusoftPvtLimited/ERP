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
                            <h3>Add Purchase</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <span></span>
                            </div>
                            {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Date') }}</label>
                                                <input type="text" id="product_purchase_date" name="created_at"
                                                    class="form-control date" placeholder="Choose date" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.After Market Supplier') }}</label>
                                                <select name="supplier_id" id="supplier_id" data-href="#"
                                                    class="selectpicker form-control" data-live-search="true"
                                                    data-live-search-style="begins" title="Select supplier...">
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Cash Type') }}</label>
                                                <select name="status" id="cash_type" class="form-control">
                                                    <option value="white">{{ trans('file.White Cash') }}</option>
                                                    <option value="black">{{ trans('file.Black Cash') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Purchase Status') }}</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="received">{{ trans('file.Recieved') }}</option>
                                                    <option value="ordered">{{ trans('file.Ordered') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Attach Document') }}</label> <i
                                                    class="dripicons-question" data-toggle="tooltip"
                                                    title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                                <input type="file" name="document" class="form-control">
                                                @if ($errors->has('extension'))
                                                    <span>
                                                        <strong>{{ $errors->first('extension') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div> --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Additional Cost') }}</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" name="purchase_additional_cost" value="0"
                                                        class="form-control" aria-label="Amount (to the nearest dollar)"
                                                        id="purchase_additional_cost" onkeyup="calculatePurchasePrice()"
                                                        class="form-control" min="0" max="100000000">
                                                    <span class="input-group-text"><b>TND</b></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary purchase-save-btn"
                                                        id="submit-btn">{{ trans('file.submit') }}</button>
                                                </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3" style="margin: 0px; padding:0px;">
                                    <div class="card" style="margin: 0px; padding:0px;">
                                        <div class="card-body" style="margin: 0px;">
                                            <div class="tab article-tabs">
                                                <button class="tablinks" onclick="openCity(event, 'London')"
                                                    id="defaultOpen">General
                                                    Search</button>
                                                <button class="tablinks" onclick="openCity(event, 'Paris')">By Product
                                                    Number</button>
                                                <button class="tablinks" onclick="openCity(event, 'Tokyo')">By Chassis
                                                    Number</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-9" style="margin: 0px; padding:0px;">
                                    <div class="card" style="margin: 0px; padding:0px;">
                                        <div class="card-body" style="margin: 0px;">
                                            <div id="London" class="tabcontent">
                                                @include('purchase.purchase_by_flow')
                                            </div>

                                            <div id="Paris" class="tabcontent">
                                                @include('purchase.purchase_by_article_number')
                                            </div>

                                            <div id="Tokyo" class="tabcontent">
                                                <h3>Tokyo</h3>
                                                <p>Tokyo is the capital of Japan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('purchase.order-table')
                            <div class="row p-5" id="total_calculations">

                            </div>
                            <div class="row" id="submit-button" style="display: none;">
                                <div class="col-md-12 form-group text-right">
                                    <button type="button" id="submit_button"
                                        class="btn btn-primary">{{ trans('file.submit') }}</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        function openCity(evt, cityName) {
            evt.preventDefault();
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
            return 1;
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();

        $('#submit_button').on('click', function() {
            var check_array = [];
            var supplier_id = $('#supplier_id').find(":selected").val();
            if (!supplier_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'please select a supplier',
                });
                check_array.push('error');
                exit();
                
            }
            if (selected_cash_type.length > 0) {
                var cashType = $('#cash_type').find(":selected").val();

                selected_cash_type.forEach(checkCashType);

                function checkCashType(element, index, data) {
                    console.log(element, index, data)
                    if (element != cashType) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'you can not able to change the cash type for a purchase once you selected',
                        });
                        check_array.push('error');
                        exit();
                    }
                   
                }

            }
            var table_body_rows = $("table tbody tr").length;
            if (table_body_rows <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please add atleast one purchase Item in table to proceed further',

                });
                check_array.push('error');
                exit();
            }
            all_product_ids.forEach(checkFields);

            function checkFields(id, index) {
                var sale_price = $('#sale_price_' + id).val();
                var actual_cost_per_product = $('#actual_cost_per_product_' + id).val();
                var total_excluding_vat = $("#total_excluding_vat_" + id).val();
                if (sale_price == null || sale_price <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Sale Price must be greater than zero',

                    });
                    check_array.push('error');
                    exit();
                }
                if (actual_cost_per_product == null || actual_cost_per_product <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Actual Cost Per Product must be greater than zero',

                    });
                    check_array.push('error');
                    exit();
                }
                if (total_excluding_vat == null || total_excluding_vat <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Total Excluding VAT must be greater than zero',

                    });
                    check_array.push('error');
                    exit();

                }

            }
            if(check_array.length <= 0){
                document.getElementById("purchase-form").submit();
            }
        });

        // $('#product_purchase_date').on('click',function(){
        //     alert(this.value)
        // });
        $("#product_purchase_date").on('change', function() {
            var selectedDate = this.value;
            var currentDate = new Date();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var date_array = selectedDate.split("-");
            var selected_day = date_array[0];
            var selected_month = date_array[1];
            var selected_year = date_array[2];
            today = mm + '-' + dd + '-' + yyyy;
            selected_date = selected_month + '-' + selected_day + '-' + selected_year;

            var selected_date_2 = new Date(selected_date);
            var today_date_2 = new Date(today);

            if (selected_date_2 > today_date_2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select the current date! currently you are not be able to add the purchase on future date',
                });
                $('#product_purchase_date').val('');
                exit();
            }



        });
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
