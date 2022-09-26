@extends('layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4><b>{{ trans('file.Add Purchase') }}</b></h4>
                        </div>

                        <div class="card-body">
                            {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="other_data"></div>
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
                                                <label>Engine Type</label>
                                                <select name="linkageTargetType" id="linkageTarget"
                                                    class="selectpicker form-control">

                                                    <option>Select Type</option>
                                                    <option value="P">Passenger</option>
                                                    <option value="O">Commercial Vehicle and Tractor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Engine Sub-Type</label>
                                                <select name="subLinkageTargetType"
                                                    data-href="{{ route('get_manufacturers_by_engine_type') }}"
                                                    id="subLinkageTarget" class="selectpicker form-control">
                                                    <option value="-2">Select One</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Manufacturers') }}</label>
                                                <select name="manufacture_id" id="manufacturer_id"
                                                    class="selectpicker form-control" data-live-search="true"
                                                    data-live-search-style="begins" title="Select Manufacturer..."
                                                    data-href="{{ route('get_models_by_manufacturer') }}">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="model_id">{{ __('Select Model') }}</label>
                                                <select name="model_id" id="model_id"
                                                    data-href="{{ route('get_engines_by_model') }}" class="form-control"
                                                    required>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="engine_id">{{ __('Select Engine') }}</label>
                                                <select name="engine_id" id="engine_id"
                                                    data-href="{{ route('get_sections_by_engine') }}" class="form-control"
                                                    required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_id">{{ __('Select Section') }}</label>
                                                <select name="section_id" id="section_id"
                                                    data-href="{{ route('get_section_parts') }}" class="form-control"
                                                    required>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section_part_id">{{ __('Select Section Part') }}</label>
                                                <select name="section_part_id" id="section_part_id"
                                                    data-href="{{ route('get_brands_by_section_part') }}"
                                                    class="form-control" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="brand">{{ __('Select brand') }}</label>
                                                <select name="brand_id" id="brand_id" data-href="" class="form-control"
                                                    required>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.After Markit Supplier') }}</label>
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Purchase Status') }}</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="received">{{ trans('file.Recieved') }}</option>
                                                    <option value="ordered">{{ trans('file.Ordered') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        </div>
                                        <div class="col-md-4">
                                            <label>{{ trans('file.Additional Cost') }}</label>
                                            <input type="number" name="purchase_additional_cost" value="0"
                                                id="purchase_additional_cost" onkeyup="calculateSalePrice()"
                                                class="form-control" min="0" max="100000000">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10"></div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info purchase-save-btn"
                                                    id="save-btn">{{ trans('file.Save') }}</button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary purchase-save-btn"
                                                    id="submit-btn">{{ trans('file.submit') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5>{{ trans('file.Order Table') }} *</h5>
                                            <div class="table-responsive mt-3">
                                                <table id="myTable" class="table table-hover order-list">
                                                    <thead>
                                                        
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_qty" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_discount" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_tax" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_cost" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="item" />
                                                <input type="hidden" name="order_tax" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="grand_total" />
                                                <input type="hidden" name="paid_amount" value="0.00" />
                                                <input type="hidden" name="payment_status" value="1" />
                                            </div>
                                        </div>
                                    </div>
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
        // select engine type---unique

        $('#linkageTarget').on('change', function() {
            var val = this.value;

            if (val == "P") {
                $('#subLinkageTarget').empty();
                $('#subLinkageTarget').append(`<option value="V">
                                Passenger Car
                                  </option><option value="L">
                                       LCV
                                  </option><option value="B">
                                        Motorcycle
                                  </option>`);
                $('.selectpicker').selectpicker('refresh');
            } else if (val == "O") {
                $('#subLinkageTarget').empty();
                $('#subLinkageTarget').append(`<option value="C">
                            Commercial Vehicle
                                  </option><option value="T">
                                       Tractor
                                  </option><option value="M">
                                       Engine
                                  </option><option value="A">
                                       Axle
                                  </option><option value="K">
                                    CV Body Type
                                  </option>`);
                $('.selectpicker').selectpicker('refresh');
            } else {
                $('#subLinkageTarget').empty();
                $('.selectpicker').selectpicker('refresh');
            }
            $('#manufacturer_id').html('<option value="">Select One</option>');
            $('#manufacturer_id').selectpicker("refresh");
            $('#section_id').html('<option value="">Select One</option>');
            $('#section_id').selectpicker("refresh");
            $('#section_part_id').html('<option value="">Select One</option>');
            $('#section_part_id').selectpicker("refresh");
            $('#engine_id').html('<option value="">Select One</option>');
            $('#engine_id').selectpicker("refresh");


        });

        // get manufacturers
        $(document).on('change', '#subLinkageTarget', function() {

            let engine_sub_type = $(this).val();
            // alert(manufacture_id)
            let url = $(this).attr('data-href');
            getManufacturer(url, engine_sub_type);
        });

        function getManufacturer(url, engine_sub_type) {
            $.get(url + '?engine_sub_type=' + engine_sub_type, function(data) {
                // $('#model_id').html(`<option value="">Select Model</option>`);
                $('#section_id').html('<option value="">Select One</option>');
                $('#section_id').selectpicker("refresh");
                $('#section_part_id').html('<option value="">Select One</option>');
                $('#section_part_id').selectpicker("refresh");
                $('#engine_id').html('<option value="">Select One</option>');
                $('#engine_id').selectpicker("refresh");

                // $('#manufacturer_id').html('<option value="">Select One</option>');
                // $('#manufacturer_id').selectpicker("refresh");

                let response = data.data;
                // console.log(response)
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html += `<option value="${value.manuId}">${value.manuName}</option>`;
                });
                // console.log(data, view_html);
                $('#manufacturer_id').html(view_html);
                // $("#model_id").val(4);
                $("#manufacturer_id").selectpicker("refresh");


            })
        }

        //get models==================
        $(document).on('change', '#manufacturer_id', function() {
            let manufacturer_id = $(this).val();
            // alert(manufacture_id)
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            let url = $(this).attr('data-href');
            getModels(url, manufacturer_id, engine_sub_type);
        });

        function getModels(url, manufacturer_id, engine_sub_type) {
            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type, function(data) {
                // $('#model_id').html(`<option value="">Select Model</option>`);
                $('#section_id').html('<option value="">Select One</option>');
                $('#section_id').selectpicker("refresh");
                $('#section_part_id').html('<option value="">Select One</option>');
                $('#section_part_id').selectpicker("refresh");
                $('#engine_id').html('<option value="">Select One</option>');
                $('#engine_id').selectpicker("refresh");


                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html += `<option value="${value.modelId}">${value.modelname}</option>`;
                });
                // console.log(data, view_html);
                $('#model_id').html(view_html);
                // $("#model_id").val(4);
                $("#model_id").selectpicker("refresh");


            })
        }

        ////// get engines==================
        $(document).on('change', '#model_id', function() {
            let model_id = $(this).val();
            let url = $(this).attr('data-href');
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            getEngines(url, model_id, engine_sub_type);
        });

        function getEngines(url, model_id, engine_sub_type) {
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type, function(data) {
                $('#section_id').html('<option value="">Select One</option>');
                $('#section_id').selectpicker("refresh");
                $('#section_part_id').html('<option value="">Select One</option>');
                $('#section_part_id').selectpicker("refresh");
                $('#engine_id').html('<option value="">Select One</option>');
                $('#engine_id').selectpicker("refresh");

                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html +=
                        `<option value="${value.linkageTargetId}">${value.description + "(" + value.beginYearMonth+ " - "+ value.endYearMonth}</option>`;
                });
                // console.log(data, view_html);
                $('#engine_id').html(view_html);
                $("#engine_id").val(4);
                $("#engine_id").selectpicker("refresh");
            })
        }

        ///// get sections==================
        $(document).on('change', '#engine_id', function() {
            let engine_id = $(this).val();
            let url = $(this).attr('data-href');
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            getSections(url, engine_id, engine_sub_type);
        });

        function getSections(url, engine_id, engine_sub_type) {
            $.get(url + '?engine_id=' + engine_id + '&engine_sub_type=' + engine_sub_type, function(data) {

                $('#section_part_id').html('<option value="">Select One</option>');
                $('#section_part_id').selectpicker("refresh");
                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html +=
                        `<option value="${value.assemblyGroupNodeId}">${value.assemblyGroupName}</option>`;
                });
                // console.log(data, view_html);
                $('#section_id').html(view_html);
                $("#section_id").val(4);
                $("#section_id").selectpicker("refresh");
            })
        }

        ///// get section parts============
        $(document).on('change', '#section_id', function() {
            let section_id = $(this).val();
            let url = $(this).attr('data-href');
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            getSectionParts(url, section_id, engine_sub_type);
        });

        function getSectionParts(url, section_id, engine_sub_type) {
            $.get(url + '?section_id=' + section_id + '&engine_sub_type=' + engine_sub_type, function(data) {

                let response = data.data;
                let view_html = `<option value="" selected>Select One</option>`;
                $.each(response, function(key, value) {
                    view_html +=
                        `<option value="${value.dataSupplierId+"-"+value.legacyArticleId}">${value.genericArticleDescription +"-"+value.articleNumber}</option>`;
                });
                // console.log(data, view_html);
                $('#section_part_id').html(view_html);
                $("#section_part_id").val(4);
                $("#section_part_id").selectpicker("refresh");
            })
        }

        ///// get brands by section parts======
        $(document).on('change', '#section_part_id', function() {
            let section_part_id = $(this).val();
            let url = $(this).attr('data-href');
            getBrands(url, section_part_id);
        });

        function getBrands(url, section_part_id) {
            $.get(url + '?section_part_id=' + section_part_id, function(data) {
                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html += `<option value="${value.brandId}">${value.brandName}</option>`;
                });
                console.log(data, view_html);
                $('#brand_id').html(view_html);
                $("#brand_id").val(4);
                $("#brand_id").selectpicker("refresh");
            })
        }


        $("#product_purchase_date").on('change', function() {
            var selectedDate = this.value;
            var currentDate = new Date();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '-' + mm + '-' + yyyy;
            if (selectedDate > today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select the current date! currently you are not be able to add the purchase on future date',
                });
                $('#product_purchase_date').val('');
                exit();

            }
        });

        //// global veriables that we were use in the save functionality ///
        var supplier_ids_array = [],
            article_ids_array = [],
            selected_cash_type = [],
            all_product_ids = [];
        // var article_ids_array = [];
        var total_quantity = $('#total-quantity');
        var total_amount = $('#total-amount');
        // var all_product_ids = [];
        $("#save-btn").click(function() {
            var id = $('#section_part_id').val();
            var engine_type = $('#linkageTarget').find(":selected").val();
            var engine_sub_type = $('#subLinkageTarget').find(":selected").val();
            var manufacturer_id = $('#manufacturer_id').find(":selected").val();
            var model_id = $('#model_id').find(":selected").val();
            var engine_id = $('#engine_id').find(":selected").val();
            var section_id = $('#section_id').find(":selected").val();
            var section_part_id = $('#section_part_id').find(":selected").val();
            var supplier_id = $('#supplier_id').find(":selected").val();
            var status = $('#status').find(":selected").val();
            var date = $('#product_purchase_date').val();
            var cashType = $('#cash_type').find(":selected").val();

            checkIfExists(engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
                section_part_id, supplier_id, status, date, cashType);

            $.ajax({
                method: "GET",
                url: "{{ url('show_section_parts_in_table') }}",
                data: {
                    id: id,
                    engine_type: engine_type,
                    engine_sub_type: engine_sub_type,
                    manufacturer_id: manufacturer_id,
                    model_id: model_id,
                    engine_id: engine_id,
                    section_id: section_id,
                    section_part_id: section_part_id,
                    supplier_id: supplier_id,
                    status: status,
                    date: date,
                    cash_type: cashType
                },

                success: function(data) {
                    // alert(data);
                    var tableBody = $("table tbody");
                    var tableHead = $("table thead");
                    var tableHeadRow = $("table thead tr");
                    var other_data_div = $('#other_data');

                    var white_cash_head = "";
                    var black_cash_head = "";
                    white_cash_head += `<tr id="white_head">
                        <th>{{ trans('file.name') }}</th>
                        <th>{{ trans('file.Quantity') }}</th>
                        <th>{{ trans('file.Purchase Price') }}</th>
                        <th>{{ trans('file.Sale Price') }}</th>
                        <th>{{ trans('file.Discount') }}</th>
                        <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                        <th>{{ trans('file.Additional Cost With VAT') }}</th>
                        <th>{{ trans('file.VAT %') }}</th>
                        <th>{{ trans('file.Profit Margin') }}</th>
                        <th>{{ trans('file.Total Cost') }}</th>
                        <th><i class="dripicons-trash"></i></th>
                    </tr>`;
                    black_cash_head += `<tr id="black_head">
                        <th>{{ trans('file.name') }}</th>
                        <th>{{ trans('file.Quantity') }}</th>
                        <th>{{ trans('file.Purchase Price') }}</th>
                        <th>{{ trans('file.Sale Price') }}</th>
                        <th>{{ trans('file.Discount') }}</th>
                        <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                        <th>{{ trans('file.Profit Margin') }}</th>
                        <th>{{ trans('file.Total Cost') }}</th>
                        <th><i class="dripicons-trash"></i></th>
                    </tr>`;
                   
                    var length = document.getElementById("myTable").rows.length;

                    var html = '';
                    html += '<input type="hidden" name="manufacturer_id[]" value="' + data
                        .manufacturer_id + '">';
                    html += '<input type="hidden" name="linkage_target_type[]" value="' + data
                        .linkage_target_type + '">';
                    html += '<input type="hidden" name="linkage_target_sub_type[]" value="' + data
                        .linkage_target_sub_type + '">';
                    html += '<input type="hidden" name="modell_id[]" value="' + data.model_id + '">';
                    html += '<input type="hidden" name="enginee_id[]" value="' + data.engine_id + '">';
                    html += '<input type="hidden" name="sectionn_id[]" value="' + data.section_id +
                        '">';
                    html += '<input type="hidden" name="sectionn_part_id[]" value="' + data
                        .section_part_id + '">';
                    html += '<input type="hidden" name="statuss[]" value="' + data.status + '">';
                    html += '<input type="hidden" name="datee[]" value="' + data.date + '">';
                    html += '<input type="hidden" name="cash_type" value="' + data.cash_type + '">';

                    $('#myTable tr').each(function() {
                        if (this.id != '') {
                            article_ids_array.push(this.id)
                        }
                    })
                    if (supplier_ids_array.length > 0) {
                        supplier_ids_array.forEach(checkSupplier);

                        function checkSupplier(item, index) {
                            if (item != data.supplier) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'You have already selected a supplier , you are not to allowed to change the supplier during one purchase',

                                });
                                exit();
                            }
                        }
                    } else {
                        supplier_ids_array.push(data.supplier);
                    }
                    console.log(selected_cash_type.length)
                    if (selected_cash_type.length > 0) {
                        selected_cash_type.forEach(checkCashType);

                        function checkCashType(element, index, data) {
                            console.log(element,index,data,)
                            if (element != cashType) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'you can not able to change the cash type for a purchase once you selected',
                                });
                                exit();
                            }
                        }
                        
                    } else {
                        selected_cash_type.push(cashType);
                        
                    }
                    console.log("hyyyyyyyyyyyyyyyyyyyy---"+tableHeadRow.length)
                    if(data.cash_type == "white"  && tableHeadRow.length <= 0){
                        tableHead.append(white_cash_head);
                    }else if(data.cash_type == "black" && tableHeadRow.length <= 0){
                        tableHead.append(black_cash_head);
                    }

                    markup = '<tr id="article_' + data.data.legacyArticleId + '"><td>' + data.data
                        .genericArticleDescription + '-' + data.data.articleNumber +
                        '</td>';

                    markup +=
                        '<td><input type="number" class="form-control" onkeyup="alterQty(' +
                        data.data.legacyArticleId + ')" id="item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="1" name="item_qty[]" required></td>';

                    markup +=
                        '<td><input type="number" class="form-control" onkeyup="alterQty(' +
                        data.data.legacyArticleId +
                        ')" value="1" min="1" step="any" id="purchase_price_' +
                        data.data.legacyArticleId +
                        '" name="purchase_price[]" required></td>';
                    markup +=
                        '<td><input type="number" class="form-control"  id="sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]" readonly></td>';
                    markup +=
                        '<td><input type="number" class="form-control" onkeyup="alterQty(' +
                        data.data.legacyArticleId +
                        ')" value="0" min="0" step="any" id="additional_cost_' +
                        data.data.legacyArticleId +
                        '" name="additional_cost[]"></td>';
                    markup +=
                        '<td><input type="number" class="form-control" value="0" min="0"   id="total_cost_' +
                        data.data.legacyArticleId +
                        '" name="total_price[]" readonly></td>';

                    markup += '<td><i id="article_delete_' +
                        data.data.legacyArticleId + '" onclick="deleteArticle(' + data.data
                        .legacyArticleId +
                        ')" class="fa fa-trash"></i></td>';

                    markup += '<td style="display:none;">' + html +
                        '</td></tr>';

                    if (length <= 1) {
                        tableBody.append(markup);
                        $('#myTable tr').each(function() {
                            if (this.id != '') {
                                article_ids_array.push(this.id)
                            }
                        })
                    } else {
                        if (!article_ids_array.includes("article_" + data.data.legacyArticleId)) {
                            tableBody.append(markup);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'This product is already added...you can update its quantity',
                            })
                        }
                    }
                    if ($('#myTable tr').length <= 1) {
                        console.log($('#myTable tr').length)
                        selected_cash_type = [];
                    }
                    all_product_ids.push(data.data.legacyArticleId);
                }
            });
        });

        function checkIfExists(engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
            section_part_id, supplier_id, status, date, cashType) {
            if (!engine_type) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select an Engine Type',

                });
                exit();
            }
            if (!engine_sub_type) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select an Engine Type',
                });
                exit();
            }
            if (!manufacturer_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a manufacturer',

                });
                exit();
            }
            if (!model_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a Model',

                });
                exit();
            }
            if (!engine_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select an engine',

                });
                exit();
            }
            if (!section_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a section',

                });
                exit();
            }
            if (!section_part_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a section part',

                });
                exit();
            }
            if (!supplier_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a supplier',

                });
                exit();
            }
            if (!status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a status',

                });
                exit();
            }
            if (!date) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a date',

                });
                exit();
            }
            if (!cashType) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select Cash Type',

                });
                exit();
            }
        }
        var t_qty = 0;
        let w_qty = 0;
        let b_qty = 0;
        var id_array = [];

        function alterQty(id) {
            item_qty = parseInt($("#item_qty" + id).val());
            var purchasePrice = parseFloat($("#purchase_price_" + id).val());
            var additionalPrice = parseFloat($("#additional_cost_" + id).val());
            var entireAditionalCost = $("#purchase_additional_cost").val();

            var totalCost = (item_qty * purchasePrice) + additionalPrice;
            $("#total_cost_" + id).val(totalCost);

            var sale_price = purchasePrice + additionalPrice + (entireAditionalCost / item_qty);
            $("#sale_price_" + id).val(sale_price);

        }

        function calculateSalePrice() {
            if (all_product_ids.length > 0) {
                var entireAditionalCost = parseFloat($("#purchase_additional_cost").val());
                all_product_ids.forEach(getSalePrice);

                function getSalePrice(id, index) {
                    item_qty = parseInt($("#item_qty" + id).val());
                    var purchasePrice = parseFloat($("#purchase_price_" + id).val());
                    var additionalPrice = parseFloat($("#additional_cost_" + id).val());
                    var totalCost = (item_qty * purchasePrice) + additionalPrice;
                    $("#total_cost_" + id).val(totalCost);
                    var sale_price = purchasePrice + additionalPrice + (entireAditionalCost / item_qty);
                    $("#sale_price_" + id).val(sale_price);
                }
            }
        }

        function deleteArticle(id) {
            $('#article_' + id).remove();
            article_ids_array = [];
            if ($('#myTable tr').length == 1) {
                selected_cash_type = [];
            }
        }
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
