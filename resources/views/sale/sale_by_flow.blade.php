<div class="row">
    <div class="col-md-12">
        <div id="other_data"></div>
        <div class="row">
            <!-- {{-- <div class="col-md-4">
                <div class="form-group">
                    <label>{{ trans('file.Date') }}</label>
                    <input type="text" id="product_purchase_date" name="created_at" class="form-control date"
                        placeholder="Choose date" />
                </div>
            </div> --}} -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engine Type</label>
                    <select name="linkageTargetType" id="linkageTarget" class="selectpicker form-control">

                        <option>Select Type</option>
                        <option value="P">Passenger</option>
                        <option value="O">Commercial Vehicle and Tractor</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engine Sub-Type</label>
                    <select name="subLinkageTargetType" data-href="{{ route('get_manufacturers_by_engine_type') }}"
                        id="subLinkageTarget" class="selectpicker form-control">
                        <option value="-2">Select One</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Manufacturers</label>
                    <select name="manufacture_id" id="manufacturer_id" class="selectpicker form-control"
                        data-live-search="true" data-live-search-style="begins" title="Select Manufacturer..."
                        data-href="{{ route('get_models_by_manufacturer') }}">

                    </select>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="model_id">Select Model</label>
                    <select name="model_id" id="model_id" data-href="{{ route('get_engines_by_model') }}"
                        class="form-control" required>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="engine_id">Select Engine</label>
                    <select name="engine_id" id="engine_id" data-href="{{ route('get_sections_by_engine') }}"
                        class="form-control" required>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="section_id">Select Section</label>
                    <select name="section_id" id="section_id" data-href="{{ route('get_section_parts_for_sale') }}"
                        class="form-control" required>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="section_part_id">Select Section Part</label>
                    <select name="section_part_id" id="section_part_id" data-href="{{ route('check_product_stock') }}"
                        class="form-control" required>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="form-group">
                    <label for="brand">Select brand</label>
                    <select name="brand_id" id="brand_id" class="form-control" required>
                    </select>
                </div>
            </div> -->

        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn" id="save-btn">Save</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
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
        //alert(engine_sub_type)
        let url = '/get_manufacturers_by_engine_type';
        // alert(url)
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
        // let url = '/get_models_by_manufacturer';
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
        // let url = '/get_engines_by_model';
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
        // let url = '/get_sections_by_engine';
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
        // let url = '/get_section_parts_for_sale';
        let url = $(this).attr('data-href');

        let engine_sub_type = $('#subLinkageTarget :selected').val();
        getSectionParts(url, section_id, engine_sub_type);
    });

    function getSectionParts(url, section_id, engine_sub_type) {
        $.get(url + '?section_id=' + section_id + '&engine_sub_type=' + engine_sub_type, function(data) {
            if (data.message == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Your Stock is empty',

                });
                exit();
            } else if (data.message == 1 && data.data.length <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You dont have any product against this section in your stock',

                });
                exit();
            }
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

    // check product stock
    $(document).on('change', '#section_part_id', function() {
        let section_part_id = $(this).val();
        // let url = '/get_section_parts_for_sale';
        let url = $(this).attr('data-href');

        let engine_sub_type = $('#subLinkageTarget :selected').val();
        var cashType = $('#cash_type').find(":selected").val();
        checkProductStock(url, section_part_id, engine_sub_type, cashType);
    });

    function checkProductStock(url, section_part_id, engine_sub_type, cashType) {
        $.get(url + '?section_part_id=' + section_part_id + '&engine_sub_type=' + engine_sub_type + '&cash_type=' +
            cashType,
            function(data) {
                if (data.message == "no_white_items") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Items are availble for this White Cash',

                    });
                    exit();
                    // $('#section_part_id').html('<option value="">Select One</option>');
                    $('#section_part_id').selectpicker("refresh");
                    exit();
                } else if (data.message == "no_black_items") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Items are availble for this Black Cash',

                    });
                    exit();
                    // $('#section_part_id').html('<option value="">Select One</option>');
                    $('#section_part_id').selectpicker("refresh");
                    exit();
                }

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
        var status = $('#status').find(":selected").val();
        var date = $('#product_purchase_date').val();
        var cashType = $('#cash_type').find(":selected").val();

        checkIfExists(engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
            section_part_id, status, date, cashType);

        $.ajax({
            method: "GET",
            url: "{{ url('show_section_parts_in_table_for_sale') }}",
            data: {
                id: id,
                engine_type: engine_type,
                engine_sub_type: engine_sub_type,
                manufacturer_id: manufacturer_id,
                model_id: model_id,
                engine_id: engine_id,
                section_id: section_id,
                section_part_id: section_part_id,

                status: status,
                date: date,
                cash_type: cashType
            },

            success: function(data) {
                // alert(data);
                // $('#myTable').DataTable( {
                //     "processing": true,
                //     "searching" : true,
                // });
                $('#submit-button').css("display", "block");
                $('#order-table-header').text(`{{ trans('file.Order Table') }} *`);
                var tableBody = $("table tbody");
                var tableHead = $("table thead");
                var tableHeadRow = $("table thead tr");
                var other_data_div = $('#other_data');

                var white_cash_head = "";
                var black_cash_head = "";

                white_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }} <span>Optional</span></th>
                    
                    <th style="width:200px">{{ trans('file.VAT %') }}</th>
                    
                    <th>{{ trans('file.Total (With Discount) Excluding Vat') }} </th>
                    
                    <th><i class="dripicons-trash"></i></th>
                </tr>`;
                // sale price => editable for white but non-editable for black
                black_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }}</th>
                    
                    <th>{{ trans('file.Total (Without Discount)') }}</th>
                    <th>{{ trans('file.Total (With Discount)') }}</th>
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


                if (selected_cash_type.length > 0) {
                    selected_cash_type.forEach(checkCashType);

                    function checkCashType(element, index, data) {
                        console.log(element, index, data, )
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
                if (data.cash_type == "white" && tableHeadRow.length <= 0) {
                    tableHead.append(white_cash_head);
                } else if (data.cash_type == "black" && tableHeadRow.length <= 0) {
                    tableHead.append(black_cash_head);
                }

                markup = '<tr id="article_' + data.data.legacyArticleId + '"><td>' + data.data
                    .genericArticleDescription + '-' + data.data.articleNumber +
                    '</td>';




                if (data.cash_type == "white") {
                    markup += '<input type="hidden" value="' + data.stock.white_items +
                        '" id="stock_items_' + data.data.legacyArticleId + '">';
                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" id="sale_item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="0" max="' + data.stock.white_items +
                        '" name="item_qty[]" required></td>';

                    markup +=
                        '<td><input style="width:100px" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + data.stock
                        .unit_sale_price_of_white_cash +
                        '" step="any" class="form-control"  id="sale_sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]"></td>';
                } else if (data.cash_type == "black") {
                    markup += '<input type="hidden" value="' + data.stock.black_items +
                        '" id="stock_items_' + data.data.legacyArticleId + '">';

                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" id="sale_item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="0" max="' + data.stock.black_items +
                        '" name="item_qty[]" required></td>';
                    markup +=
                        '<td><input style="width:100px" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + data.stock
                        .unit_sale_price_of_black_cash +
                        '" step="any" class="form-control"  id="sale_sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]" readonly></td>';
                }


                markup +=
                    '<td><input type="number" onkeyup="alterSaleQty(' +
                    data.data.legacyArticleId +
                    ')" class="form-control" value="0" min="0" max="100" step="any" id="sale_discount_' +
                    data.data.legacyArticleId +
                    '" name="discount[]"></td>';



                if (data.cash_type == "white") {
                    markup +=
                        '<td><input style="width:100px" type="number" class="form-control" value="0" min="0" step="any" id="vat_' +
                        data.data.legacyArticleId +
                        '" name="vat[]" required></td>';
                }

                if (data.cash_type == "black") {
                    markup +=
                        '<td><input style="width:200px" type="number" step="any" class="form-control" min="0"   id="sale_total_without_discount' +
                        data.data.legacyArticleId +
                        '" name="sale_total_without_discount[]" readonly></td>';
                }

                markup +=
                    '<td><input style="width:200px" type="number" step="any" class="form-control" min="0"   id="sale_total_with_discount' +
                    data.data.legacyArticleId +
                    '" name="sale_total_with_discount[]" readonly></td>';



                markup += '<td><i id="article_delete_' +
                    data.data.legacyArticleId + '" onclick="deleteSaleArticle(' + data.data
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
                    });

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
                    selected_cash_type = [];
                }
                all_product_ids.push(data.data.legacyArticleId);

                var sale_price = parseFloat($("#sale_sale_price_" + data.data.legacyArticleId).val());
                var discount = parseFloat($("#sale_discount_" + data.data.legacyArticleId).val());
                var item_qty = parseInt($("#sale_item_qty" + data.data.legacyArticleId).val());

                var sale_total_with_discount = (item_qty * sale_price) - discount;
                var sale_total_without_discount = (item_qty * sale_price);

                $('#sale_total_with_discount' + data.data.legacyArticleId).val(sale_total_with_discount.toFixed(2));
                $('#sale_total_without_discount' + data.data.legacyArticleId).val(sale_total_without_discount.toFixed(2));
            }
        });
    });

    function checkIfExists(engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
        section_part_id, status, date, cashType) {
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
    var total_quantity_of_all_row_products = 0;

    function alterSaleQty(id) {

        var item_qty = parseInt($("#sale_item_qty" + id).val());
        var stock = parseInt($("#stock_items_" + id).val());
        if (item_qty > stock) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Quantity must not be greater than Stock',

            });
            $("#sale_item_qty" + id).val(stock - parseInt(1));
            exit();
        }

        var sale_price = parseFloat($("#sale_sale_price_" + id).val());
        var discount = (parseFloat(1) - (parseFloat($("#sale_discount_" + id).val() / 100)));

        var sale_total_with_discount = (item_qty * sale_price) * discount;
        var sale_total_without_discount = (item_qty * sale_price);

        $('#sale_total_with_discount' + id).val(sale_total_with_discount.toFixed(2));
        $('#sale_total_without_discount' + id).val(sale_total_without_discount.toFixed(2));

    }



    function deleteSaleArticle(id) {
        $('#article_' + id).remove();
        article_ids_array = [];
        if ($('#myTable tr').length == 0) {
            selected_cash_type = [];
        }
    }
</script>
