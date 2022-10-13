<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="row">
    <div class="col-md-12">
        <div id="other_data"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12 mt-3">
                        <div class="ui-widget">

                            <label for="automplete-1">Product Number: </label>
                            <input id="automplete-1" class="form-control">
                        </div>

                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn"
                        id="save-button">{{ trans('file.Save') }}</button>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

<script>
    $(function() {
        var name = $('#automplete-1').val();
        console.log("jjjjjjjjjjjjj")
        $.ajax({
            method: "GET",
            url: "{{ url('articlesByReferenceNo') }}",
            data: {
                name: name
            },

            success: function(data) {

                let response = data.data.data;

                var html = "";
                var articleNumbers = [];
                $.each(response, function(key, value) {
                    if (value != null) {
                        articleNumbers.push(value.articleNumber)
                    }

                });

                $("#automplete-1").autocomplete({
                    source: articleNumbers
                });



            },
            error: function(error) {
                console.log(error);
            }
        });
        // var availableTutorials  =  [
        //    "ActionScript",
        //    "Bootstrap",
        //    "C",
        //    "C++",
        // ];
        // $( "#automplete-1" ).autocomplete({
        //    source: availableTutorials
        // });
    });
    // $(function() {
    //     let name = $('#automplete-1').val();
    //     console.log(name)
    //     $.ajax({
    //         method: "GET",
    //         url: "{{ url('articlesByReferenceNo') }}",
    //         data: {
    //             name: name
    //         },

    //         success: function(data) {

    //             let response = data.data;

    //             var html = "";
    //             var articleNumbers = [];
    //             $.each(response, function(key, value) {
    //                 if(value != null){
    //                     articleNumbers.push(value.articleNumber)
    //                 }

    //             });

    //             $("#automplete-1").autocomplete({
    //                 source: articleNumbers
    //             });



    //         },
    //         error: function(error) {
    //             console.log(error);
    //         }
    //     });
    // });
    var product_name = "";
    $(document).ready(function() {
        $('#automplete-1').on('autocompletechange change', function() {
            product_name = this.value;
        }).change();
    });

    var supplier_ids_array = [],
        article_ids_array = [],
        selected_cash_type = [],
        all_product_ids = [];
    // var article_ids_array = [];
    var total_quantity = $('#total-quantity');
    var total_amount = $('#total-amount');

    $("#save-button").click(function() {
        var supplier_id = $('#supplier_id').find(":selected").val();
        var status = $('#status').find(":selected").val();
        var date = $('#product_purchase_date').val();
        var cashType = $('#cash_type').find(":selected").val();

        checkIfExists(supplier_id, status, date, cashType, product_name);
        $.ajax({
            method: "GET",
            url: "{{ url('getArticleInfo') }}",
            data: {
                supplier_id: supplier_id,
                status: status,
                date: date,
                cash_type: cashType,
                name: product_name
            },
            success: function(data) {
                if (data.data == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,

                    });
                    exit();
                } else {
                    $('#submit-button').css("display", "block");
                    $('#order-table-header').text(`{{ trans('file.Order Table') }} *`);
                    var tableBody = $("table tbody");
                    var tableHead = $("table thead");
                    var tableHeadRow = $("table thead tr");
                    var total_calculations = $('#total_calculations');
                    var white_cash_head = "";
                    var black_cash_head = "";

                    var white_cash_calculations_head = "";
                    var black_cash_calculations_head = "";
                    white_cash_calculations_head += `
                       <div class="col-md-12">
                            <div class="row total-calculations"> 
                                <div class="col-md-3">
                                   <h5>Total Exculding VAT</h5>    
                                </div>
                                <div class="col-md-3">
                                   <div class="input-group mb-3">     
                                        <input type="number" name="entire_total_exculding_vat" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="entire_total_exculding_vat"
                                            class="form-control" min="0" step="any" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-3">
                                   <h5>VAT</h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="entire_vat" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="entire_vat" 
                                            class="form-control" min="0" step="any" max="100000000">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-3">
                                   <h5>Tax Stamp</h5>    
                                </div> 
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="tax_stamp" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="tax_stamp" 
                                            class="form-control" min="0" step="any" max="100000000">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                        
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-3">
                                   <h5>Total To Be Paid</h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="total_to_be_paid" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="total_to_be_paid"
                                            class="form-control" min="0" max="100000000">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div> 
                            </div>
                       </div>
                `;
                black_cash_calculations_head += `
                            <div class="row total-calculations"> 
                                <div class="col-md-3">
                                   <h5>Total To Be Paid</h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="total_to_be_paid" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="total_to_be_paid" onkeyup="calculatePurchasePrice()"
                                            class="form-control" min="0" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div> 
                                </div> 
                            </div>
                `;

                    white_cash_head += `<tr id="">
                        <th>{{ trans('file.name') }}</th>
                        <th>{{ trans('file.Quantity') }}</th>
                        <th>{{ trans('file.Purchase Price') }}</th>
                        <th>{{ trans('file.Sale Price') }}</th>
                        <th>{{ trans('file.Discount') }} %</th>
                        <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                        <th>{{ trans('file.Additional Cost With VAT') }}</th>
                        <th style="width:200px">{{ trans('file.VAT %') }}</th>
                        <th>{{ trans('file.Profit Margin') }} %</th>
                        <th>{{ trans('file.Total Excluding Vat') }}</th>
                        <th>{{ trans('file.Actual Cost Per Product') }}</th>
                        <th>Action</th>
                    </tr>`;

                    black_cash_head += `<tr id="">
                        <th>{{ trans('file.name') }}</th>
                        <th>{{ trans('file.Quantity') }}</th>
                        <th>{{ trans('file.Purchase Price') }}</th>
                        <th>{{ trans('file.Sale Price') }}</th>
                        <th>{{ trans('file.Discount') }} %</th>
                        <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                        <th>{{ trans('file.Profit Margin') }} %</th>
                        <th>{{ trans('file.Total Excluding Vat') }}</th>
                        <th>{{ trans('file.Actual Cost Per Product') }}</th>
                        <th>Action</th>
                    </tr>`;

                    var length = document.getElementById("myTable").rows.length;

                    var html = '';
                    html += '<input type="hidden" name="manufacturer_id[]" value="' + data
                        .manufacturer_id + '">';
                    html += '<input type="hidden" name="linkage_target_type[]" value="' + data
                        .linkage_target_type + '">';
                    html += '<input type="hidden" name="linkage_target_sub_type[]" value="' + data
                        .linkage_target_sub_type + '">';
                    html += '<input type="hidden" name="modell_id[]" value="' + data.model_id +
                        '">';
                    html += '<input type="hidden" name="enginee_id[]" value="' + data.engine_id +
                        '">';
                    html += '<input type="hidden" name="sectionn_id[]" value="' + data.section_id +
                        '">';
                    html += '<input type="hidden" name="sectionn_part_id[]" value="' + data
                        .section_part_id + '">';
                    html += '<input type="hidden" name="statuss[]" value="' + data.status + '">';
                    html += '<input type="hidden" name="datee[]" value="' + data.date + '">';
                    html += '<input type="hidden" name="cash_type" value="' + data.cash_type + '">';
                    html += '<input type="hidden" name="brand_id[]" value="' + data.brand_id + '">';
                    calculateEntireTotal(all_product_ids);

                    // start
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
                        total_calculations.html(white_cash_calculations_head);
                    } else if (data.cash_type == "black" && tableHeadRow.length <= 0) {
                        tableHead.append(black_cash_head);
                        total_calculations.html(black_cash_calculations_head);
                    }

                    markup = '<tr id="article_' + data.data.legacyArticleId + '"><td>' + data.data
                        .genericArticleDescription + '-' + data.data.articleNumber +
                        '</td>';

                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterQty(' +
                        data.data.legacyArticleId + ')" id="item_qty' + data.data
                        .legacyArticleId +
                        '" value="0" min="0" name="item_qty[]" required></td>';

                    markup +=
                        '<td><input style="width:100px" type="number" class="form-control" onkeyup="alterQty(' +
                        data.data.legacyArticleId +
                        ')" value="1" min="0" step="any" id="purchase_price_' +
                        data.data.legacyArticleId +
                        '" name="purchase_price[]" required></td>';

                    markup +=
                        '<td><input style="width:100px" type="number" class="form-control"  id="sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]" readonly></td>';

                    markup +=
                        '<td><input type="number" class="form-control" value="0" min="0" step="any" id="discount_' +
                        data.data.legacyArticleId +
                        '" name="discount[]"></td>';

                    markup +=
                        '<td><input type="number" class="form-control" value="0" min="1" step="any"  onkeyup="alterQty(' +
                        data.data.legacyArticleId + ')" id="additional_cost_without_vat_' + data
                        .data
                        .legacyArticleId +
                        '" name="additional_cost_without_vat[]"></td>';
                    if (data.cash_type == "white") {
                        markup +=
                            '<td><input type="number" class="form-control" value="0" min="1" step="any" id="additional_cost_with_vat_' +
                            data.data.legacyArticleId +
                            '" name="additional_cost_with_vat[]"></td>';
                    }

                    if (data.cash_type == "white") {
                        markup +=
                            '<td><input style="width:100px" type="number" class="form-control" value="0" min="1" step="any" id="vat_' +
                            data.data.legacyArticleId +
                            '" name="vat[]" required></td>';
                    }

                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" value="0" min="1" step="any"   onkeyup="alterQty(' +
                        data.data.legacyArticleId + ')" id="profit_margin_' + data.data
                        .legacyArticleId +
                        '" name="profit_margin[]" required></td>';

                    markup +=
                        '<td><input style="width:100px" type="number" class="form-control" value="0" min="0"   id="total_excluding_vat_' +
                        data.data.legacyArticleId +
                        '" name="total_excluding_vat[]" readonly></td>';

                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" value="0" min="0"   id="actual_cost_per_product_' +
                        data.data.legacyArticleId +
                        '" name="actual_cost_per_product[]" readonly></td>';

                    markup += '<td><button class="btn btn-danger"><i id="article_delete_' +
                    data.data.legacyArticleId + '" onclick="deleteArticle(' + data.data
                    .legacyArticleId +
                    ')" class="fa fa-trash"></i></button></td>';

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

                }
            },
        });

    });

    var t_qty = 0;
    // let w_qty = 0;
    // let b_qty = 0;
    var id_array = [];
    var total_quantity_of_all_row_products = 0;

    function alterQty(id) {
        item_qty = parseInt($("#item_qty" + id).val());
        var purchasePrice = parseFloat($("#purchase_price_" + id).val());
        var additional_cost_without_vat = parseFloat($("#additional_cost_without_vat_" + id).val());
        var entireAditionalCost = $("#purchase_additional_cost").val();

        var total_cost_without_vat = (purchasePrice * item_qty) + additional_cost_without_vat;
        $("#total_excluding_vat_" + id).val(total_cost_without_vat.toFixed(2));

        if (all_product_ids.length > 0) {
            all_product_ids.forEach(getActualProductCost);

            function getActualProductCost(id, index) {
                total_quantity_of_all_row_products += parseInt($("#item_qty" + id).val());
            }

            var actual_cost_per_product = (total_cost_without_vat / item_qty) + (entireAditionalCost /
                total_quantity_of_all_row_products);
        }

        $('#actual_cost_per_product_' + id).val(actual_cost_per_product.toFixed(2));
        calculateEntireTotal(all_product_ids);
        var profit_margin = parseFloat($('#profit_margin_' + id).val() / 100);
        var sale_price_per_product = actual_cost_per_product * (1 + profit_margin);
        sale_price_per_product = parseFloat(sale_price_per_product);
        $('#sale_price_' + id).val(sale_price_per_product.toFixed(2));
        total_quantity_of_all_row_products = 0;
    }

    function calculateSalePrice() {
        if (all_product_ids.length > 0) {
            var entireAditionalCost = parseFloat($("#purchase_additional_cost").val());
            all_product_ids.forEach(getSalePrice);

            function getSalePrice(id, index) {
                item_qty = parseInt($("#item_qty" + id).val());
                var purchasePrice = parseFloat($("#purchase_price_" + id).val());
                var additional_cost_without_vat = parseFloat($("#additional_cost_without_vat_" + id).val());
                var entireAditionalCost = $("#purchase_additional_cost").val();


                var total_cost_without_vat = (purchasePrice * item_qty) + additional_cost_without_vat;
                $("#total_excluding_vat_" + id).val(total_cost_without_vat.toFixed(2));


                total_quantity_of_all_row_products += parseInt($("#item_qty" + id).val());

                var actual_cost_per_product = (total_cost_without_vat / item_qty) + (entireAditionalCost /
                    total_quantity_of_all_row_products);

                $('#actual_cost_per_product_' + id).val(actual_cost_per_product.toFixed(2));
                var profit_margin = parseFloat($('#profit_margin_' + id).val() / 100);
                var sale_price_per_product = actual_cost_per_product * (1 + profit_margin);



                sale_price_per_product = parseFloat(sale_price_per_product);
                $('#sale_price_' + id).val(sale_price_per_product.toFixed(2));
            }
            calculateEntireTotal(all_product_ids);
            total_quantity_of_all_row_products = 0;
        }
    }

    function deleteArticle(id) {
        $('#article_' + id).remove();
        for (var i = 0; i < all_product_ids.length; i++) {

            if (all_product_ids[i] === id) {

                all_product_ids.splice(i, 1);
            }

        }
        if(all_product_ids.length <= 0){
            $('#total_calculations').css('display','none');
            $('#submit-button').css('display','none');
            $("table thead").css('display','none');


        }
        calculateEntireTotal(all_product_ids);
        // article_ids_array = [];
        if ($('#myTable tr').length == 0) {
            selected_cash_type = [];
        }
    }

    function changeTotalWithVAT() {
        var total_vat = 0.0;
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = all_product_ids.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {

                if (cashType == "white") {
                    total_vat = total_vat + parseFloat($('#vat_' + id).val() / 100) + parseFloat($(
                        '#additional_cost_with_vat_' + id).val());
                }


            }
            total_vat = total_vat + parseFloat($('#purchase_additional_cost').val());

            $('#entire_vat').val(total_vat.toFixed(2));
            var tax_stamp = parseFloat($('#tax_stamp').val());
            var total_to_be_paid = total_actual.toFixed(2) + entire_vat.toFixed(2) + tax_stamp.toFixed(2);
            $('#total_to_be_paid').val(total_to_be_paid);
        }
    }

    function calculateEntireTotal(product_ids_array) {
        var total_actual = 0.0;
        var total_vat = 0.0;
        var total_to_be_paid = 0.0;
        // console.log(product_ids_array)
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = product_ids_array.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {

                total_actual += parseFloat($('#actual_cost_per_product_' + id).val());
                if (cashType == "white") {
                    total_vat = total_vat + parseFloat($('#vat_' + id).val() / 100) + parseFloat($(
                        '#additional_cost_with_vat_' + id).val());
                }


            }
            total_vat = total_vat + parseFloat($('#purchase_additional_cost').val());

            $('#entire_total_exculding_vat').val(total_actual.toFixed(2));
            $('#entire_vat').val(total_vat.toFixed(2));
            var tax_stamp = parseFloat($('#tax_stamp').val());
            console.log('stamp', tax_stamp)
            total_to_be_paid = parseFloat(total_actual.toFixed(2)) + parseFloat(total_vat.toFixed(2)) + parseFloat(
                tax_stamp.toFixed(2));
            if (cashType == "white") {
                $('#total_to_be_paid').val(total_to_be_paid);
            } else if (cashType == "black") {
                $('#total_to_be_paid').val(total_actual);
            }

        }

    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    function checkIfExists(supplier_id, status, date, cashType, product_name) {
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

        if (!product_name) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a product name',

            });
            exit();
        }

    }
</script>
