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
        $.ajax({
            method: "GET",
            url: "{{ url('sale_products_by_product_number') }}",
            data: {
                name: name
            },

            success: function(data) {

                let response = data.data;

                var html = "";
                var articleNumbers = [];
                $.each(response, function(key, value) {
                    if (value != null) {
                        articleNumbers.push(value.reference_no)
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
        var date = $('#product_sale_date').val();
        var cashType = $('#cash_type').find(":selected").val();

        checkIfExists(date, cashType);
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
                }
                $('#submit-button').css("display", "block");
                $('#total_sale_calculations').css('display', 'block');
                $('#order-table-header').text(`{{ trans('file.Order Table') }} *`);
                var tableBody = $("table tbody");
                var tableHead = $("table thead");
                var tableHeadRow = $("table thead tr");
                var other_data_div = $('#other_data');

                var total_calculations = $('#total_sale_calculations');

                var white_cash_head = "";
                var black_cash_head = "";
                var white_cash_calculations_head = "";
                white_cash_calculations_head += `
                       <div class="col-md-12">
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Total Exculding VAT (Before Discount)</h5>    
                                </div>
                                <div class="col-md-3">
                                   <div class="input-group mb-3">     
                                        <input type="number" name="sale_entire_total_exculding_vat" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_entire_total_exculding_vat" 
                                            class="form-control" min="0" step="any" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Discount <span style="font-size:10px;color:#98AFC7">(value)</span></h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="sale_discount" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_discount" 
                                            class="form-control" min="0" step="any" max="100000000" onkeyup="calculateSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>VAT <span style="font-size:10px;color:#98AFC7">(value)</span></5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="entire_vat" value="1" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_entire_vat" 
                                            class="form-control" min="1" step="any" max="100000000" onkeyup="calculateSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Tax Stamp</h5>    
                                </div> 
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="tax_stamp" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_tax_stamp" 
                                            class="form-control" min="0" step="any" max="100000000" onkeyup="calculateSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Total To Be Paid</h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="total_to_be_paid" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="total_to_be_paid" 
                                            class="form-control" min="0" step="any" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div> 
                            </div>
                       </div>
                `;

                white_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }} <span>Optional</span></th>
                    
                    <th style="width:200px">{{ trans('file.VAT %') }}</th>
                    
                    <th>{{ trans('file.Total (With Discount) Excluding Vat') }} </th>
                    
                    <th>Action</th>
                </tr>`;
                // sale price => editable for white but non-editable for black
                black_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }}</th>
                    
                    <th>{{ trans('file.Total (Without Discount)') }}</th>
                    <th>{{ trans('file.Total (With Discount)') }}</th>
                    <th>Action</th>
                </tr>`;

                var length = document.getElementById("myTable").rows.length;

                var html = '';

                html += '<input type="hidden" name="article_number[]" value="' + data.data
                    .articleNumber + '">';
                calculateEntireSaleTotal(all_product_ids);
                // $('#myTable tr').each(function() {
                //     if (this.id != '') {
                //         article_ids_array.push(this.id)
                //     }
                // })

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
                }
                $('#total_sale_calculations').css('display', 'block')
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
                    var white_price = 1;
                    if (data.stock.unit_sale_price_of_white_cash != null) {
                        white_price = data.stock.unit_sale_price_of_white_cash;
                    }
                    markup +=
                        '<td><input style="width:150px" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + white_price +
                        '" step="any" class="form-control"  id="sale_sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]"></td>';
                } else if (data.cash_type == "black") {
                    markup += '<input type="hidden" value="' + data.stock.black_items +
                        '" id="stock_items_' + data.data.legacyArticleId + '">';
                    var black_price = 1;
                    if (data.stock.unit_sale_price_of_black_cash != null) {
                        black_price = data.stock.unit_sale_price_of_black_cash;
                    }
                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" id="sale_item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="0" max="' + data.stock.black_items +
                        '" name="item_qty[]" required></td>';
                    markup +=
                        '<td><input style="width:100px" onkeyup="alterSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + black_price +
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

                markup += '<td><button type="button" id="article_delete_' +
                    data.data.legacyArticleId + '" onclick="deleteSaleArticle(' + data.data
                    .legacyArticleId +
                    ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>';

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

                var sale_price = parseFloat($("#sale_sale_price_" + data.data.legacyArticleId)
                    .val());
                var discount = parseFloat($("#sale_discount_" + data.data.legacyArticleId).val());
                var item_qty = parseInt($("#sale_item_qty" + data.data.legacyArticleId).val());

                var sale_total_with_discount = (item_qty * sale_price) - discount;
                var sale_total_without_discount = (item_qty * sale_price);

                $('#sale_total_with_discount' + data.data.legacyArticleId).val(
                    sale_total_with_discount.toFixed(2));
                $('#sale_total_without_discount' + data.data.legacyArticleId).val(
                    sale_total_without_discount.toFixed(2));
            }
        });

    });

    function checkIfExists(date, cashType) {


        // if (!status) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'Please select a status',

        //     });
        //     exit();
        // }
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

    var id_array = [];
    var total_quantity_of_all_row_products = 0;

    function alterSaleQty(id) {

        var error = 0;
        var item_qty = parseInt($("#sale_item_qty" + id).val());
        if (!item_qty) {
            item_qty = 1;
        }


        var sale_price = parseFloat($("#sale_sale_price_" + id).val());
        if (!sale_price) {
            sale_price = 1;
        }
        var discount_check = $("#sale_discount_" + id).val();
        if (discount_check % 1 != 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Discount must be Type of Integer',

            });
            $('#sale_discount_' + id).val(0)
            error = 1;
        }
        if (discount_check > 100) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Discount must be less or equal to 100',
            });
            $('#sale_discount_' + id).val(0)
            error = 1;
        }
        if (error == 0) {
            var discount = (parseFloat(1) - (parseFloat($("#sale_discount_" + id).val() / 100)));
            if (!discount) {
                discount = (parseFloat(1) - (0 / 100));
            }
            var sale_total_with_discount = (item_qty * sale_price) * discount;
            var sale_total_without_discount = (item_qty * sale_price);
            if (sale_total_with_discount <= 0) {
                $('#sale_total_with_discount' + id).val(0);
            } else {
                $('#sale_total_with_discount' + id).val(sale_total_with_discount.toFixed(2))
            }

            if (sale_total_without_discount <= 0) {
                $('#sale_total_without_discount' + id).val(0);
            } else {
                $('#sale_total_without_discount' + id).val(sale_total_without_discount.toFixed(2))
            }
            calculateEntireSaleTotal(all_product_ids);
        }
    }


    function deleteSaleArticle(id) {
        $('#article_' + id).remove();
        for (var i = 0; i < all_product_ids.length; i++) {

            if (all_product_ids[i] === id) {

                all_product_ids.splice(i, 1);
            }

        }
        for (var i = 0; i < article_ids_array.length; i++) {

            if (article_ids_array[i] === "article_" + id) {
                console.log("article_4444444444444idsssssss", article_ids_array);

                article_ids_array.splice(i, 1);
            }

        }
        if (all_product_ids.length <= 0) {
            $('#total_sale_calculations').css('display', 'none');
            $('#submit-button').css('display', 'none');
        }
        calculateEntireSaleTotal(all_product_ids);
        // article_ids_array = [];
        if ($('#myTable tr').length == 0) {
            selected_cash_type = [];
        }
    }

    function calculateEntireSaleTotal(product_ids_array) {
        var total_before_discount = 0.0;
        var total_to_be_paid = 0.0;
        // console.log(product_ids_array)
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = product_ids_array.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {

                var it_qty = $('#sale_item_qty' + id).val();
                if (!it_qty) {
                    it_qty = 1;
                }
                var sal_price = $('#sale_sale_price_' + id).val();
                if (!sal_price) {
                    sal_price = 1;
                }
                total_before_discount += (parseInt(it_qty) * parseFloat(sal_price));
            }


            var tax_stamp = $('#sale_tax_stamp').val();
            if (!tax_stamp) {
                tax_stamp = 0
            }
            var entire_vat = $('#sale_entire_vat').val();
            if (!entire_vat) {
                entire_vat = 1
            }
            var discount = $('#sale_discount').val();
            if (!discount) {
                discount = 0
            }
            $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));
            total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount)) * parseFloat(
                entire_vat) + parseFloat(tax_stamp);
            if (total_to_be_paid < 0) {
                $('#total_to_be_paid').val(0);
            } else {
                $('#total_to_be_paid').val(total_to_be_paid);
            }


        }
        // }
    }

    function calculateSaleTotal() {
        var total_before_discount = 0.0;
        var total_to_be_paid = 0.0;
        var total_sale_price = 0.0;

        // console.log(product_ids_array)
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = all_product_ids.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {
                var it_qty = $('#sale_item_qty' + id).val();
                if (!it_qty) {
                    it_qty = 1;
                }
                var sal_price = $('#sale_sale_price_' + id).val();
                if (!sal_price) {
                    sal_price = 1;
                }
                total_sale_price += parseFloat(sal_price);
                total_before_discount += (parseInt(it_qty) * parseFloat(sal_price));
            }


            var tax_stamp = $('#sale_tax_stamp').val();
            if (!tax_stamp) {
                tax_stamp = 0
            }
            var entire_vat = $('#sale_entire_vat').val();
            if (!entire_vat) {
                entire_vat = 1
            }
            var discount = $('#sale_discount').val();
            if (!discount) {
                discount = 0
            }

            if (discount > total_sale_price) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'discount can not be greater than sum of sale price',

                });
                $('#sale_discount').val(discount - parseInt(1))
                exit();

            }
            $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));
            total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount)) * parseFloat(
                entire_vat) + parseFloat(tax_stamp);

            if (total_to_be_paid < 0) {
                $('#total_to_be_paid').val(0);
            } else {
                $('#total_to_be_paid').val(total_to_be_paid);
            }


        }
    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
</script>
