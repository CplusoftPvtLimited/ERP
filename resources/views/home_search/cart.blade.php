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
                        <div class="card-header article_view_tr_head">
                            <h3>Cart</h3>
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
                                                <input type="text" class="form-control"
                                                    value="{{ $cart->cash_type == 'white' ? 'Primary Cash' : 'Secondary Cash' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Purchase Status') }}</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="ordered">{{ trans('file.Ordered') }}</option>
                                                    <option value="received">{{ trans('file.Recieved') }}</option>

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
                                                    <input type="number" name="purchase_additional_cost"
                                                        value="{{ $cart->additional_cost }}" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)"
                                                        id="purchase_additional_cost" onkeyup="calculateCartPrice()"
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
                                <div class="col-md-12">
                                    <div class="table">
                                        <table class="table table-responsive">
                                            <thead>
                                                @if ($cart->cash_type == 'white')
                                                    <tr id="" class="article_view_tr_head">
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
                                                    </tr>
                                                @else
                                                    <tr id="" class="article_view_tr_head">
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
                                                    </tr>
                                                @endif
                                            </thead>
                                            <tbody>
                                                @foreach ($cart_items as $cart_item)
                                                    <tr>
                                                        <td>
                                                            {{ $cart_item->reference_no }}
                                                        </td>
                                                        <td><input type="number" style="width:100px" class="form-control"
                                                                onkeyup="alterCartQty({{ $cart_item->id }})"
                                                                id="item_qty{{ $cart_item->id }}"
                                                                value="{{ $cart_item->qty }}" min="1"
                                                                name="item_qty[]" required></td>
                                                        <td><input type="number" style="width:100px" class="form-control"
                                                                onkeyup="alterCartQty({{ $cart_item->id }})"
                                                                id="purchase_price_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->actual_price }}" min="1"
                                                                name="purchase_price[]" required></td>
                                                        <td><input type="number" style="width:100px" class="form-control"
                                                                id="sale_price_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->sell_price }}" min="1"
                                                                name="sale_price[]" readonly></td>
                                                        <td><input type="number" style="width:100px" class="form-control"
                                                                id="discount_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->discount }}"
                                                                onkeyup="checkCartDiscount()" min="0"
                                                                max="100" name="discount[]"></td>
                                                        <td><input type="number" style="width:100px"
                                                                class="form-control"
                                                                onkeyup="alterCartQty({{ $cart_item->id }})"
                                                                id="additional_cost_without_vat_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->additional_cost_without_vat }}"
                                                                min="0" name="additional_cost_without_vat[]">
                                                        </td>
                                                        @if ($cart->cash_type == 'white')
                                                            <td><input type="number" style="width:100px"
                                                                    class="form-control"
                                                                    onkeyup="changeCartTotalWithVAT()"
                                                                    id="additional_cost_with_vat_{{ $cart_item->id }}"
                                                                    value="{{ $cart_item->additional_cost_with_vat }}"
                                                                    min="0" name="additional_cost_with_vat[]">
                                                            </td>
                                                            <td><input type="number" style="width:100px"
                                                                    class="form-control"
                                                                    onkeyup="changeCartTotalWithVAT()"
                                                                    id="vat_{{ $cart_item->id }}"
                                                                    value="{{ $cart_item->vat }}" min="0"
                                                                    max="100" name="vat[]"></td>
                                                        @endif
                                                        <td><input type="number" style="width:100px"
                                                                class="form-control"
                                                                onkeyup="alterCartQty({{ $cart_item->id }})"
                                                                id="profit_margin_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->profit_margin }}" min="1"
                                                                name="profit_margin[]" required></td>
                                                        <td><input type="number" style="width:100px"
                                                                class="form-control"
                                                                id="total_excluding_vat_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->total_excluding_vat }}"
                                                                min="1" name="total_excluding_vat[]" readonly>
                                                        </td>
                                                        <td><input type="number" style="width:100px"
                                                                class="form-control"
                                                                id="actual_cost_per_product_{{ $cart_item->id }}"
                                                                value="{{ $cart_item->actual_cost_per_product }}"
                                                                min="1" name="actual_cost_per_product[]" readonly>
                                                        </td>
                                                        <td><a href="{{ route('remove_cart_item', $cart_item->id) }}"
                                                                class="btn btn-danger" id="cart_item_delete"><i
                                                                    class="fa fa-trash"></i></a>
                                                        </td>

                                                    </tr>
                                                    <script>
                                                        all_product_ids = [];
                                                        all_product_ids.push({{ $cart_item->id }});
                                                    </script>
                                                    <input type="hidden" name="manufacturer_id[]"
                                                        value="{{ $cart_item->manufacture_id }}">

                                                    <input type="hidden" name="modell_id[]"
                                                        value="{{ $cart_item->model_id }}">
                                                    <input type="hidden" name="enginee_id[]"
                                                        value="{{ $cart_item->eng_linkage_target_id }}">
                                                    <input type="hidden" name="sectionn_id[]"
                                                        value="{{ $cart_item->assembly_group_node_id }}">
                                                    <input type="hidden" name="sectionn_part_id[]"
                                                        value="{{ $cart_item->legacy_article_id }}">
                                                    <input type="hidden" name="statuss[]"
                                                        value="{{ $cart_item->status }}">
                                                    <input type="hidden" name="datee[]" value="{{ $cart_item->date }}">
                                                    <input type="hidden" name="cash_type"
                                                        value="{{ $cart_item->cash_type }}">
                                                    <input type="hidden" name="brand_id[]"
                                                        value="{{ $cart_item->brand_id }}">
                                                    <input type="hidden" name="linkage_target_type[]"
                                                        value="{{ $cart_item->linkage_target_sub_type }}">
                                                    <input type="hidden" name="linkage_target_sub_type[]"
                                                        value="{{ $cart_item->linkage_target_sub_type }}">
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="valueCheck" id="valueCheck" value="0">
                            <input type="hidden" name="cart_id" id="cart_id" value="{{ $cart->id }}">

                            @if ($cart->cash_type == 'white')
                                <div class="col-md-12">
                                    <div class="row total-calculations">
                                        <div class="col-md-3">
                                            <h5>Total Exculding VAT</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group mb-3">
                                                <input type="number" name="entire_total_exculding_vat"
                                                    value="{{ $cart->total_exculding_vat }}" class="form-control"
                                                    aria-label="Amount (to the nearest dollar)"
                                                    id="entire_total_exculding_vat" class="form-control" min="0"
                                                    step="any" max="100000000" readonly>
                                                <span class="input-group-text"><b>TND</b></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row total-calculations">
                                        <div class="col-md-3">
                                            <h5>VAT <span style="font-size:10px;color:#98AFC7">(value)</span></h5>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group mb-3">
                                                <input type="number" name="entire_vat" value="{{ $cart->total_vat }}"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)"
                                                    id="entire_vat" class="form-control" min="0" step="any"
                                                    max="100000000" readonly>
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
                                                <input type="number" name="tax_stamp" value="{{ $cart->tax_stamp }}"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)"
                                                    onkeyup="changeCartTotalWithVAT()" id="tax_stamp"
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
                                                <input type="number" name="total_to_be_paid"
                                                    value="{{ $cart->total_cost }}" class="form-control"
                                                    aria-label="Amount (to the nearest dollar)" id="total_to_be_paid"
                                                    class="form-control" min="0" max="100000000" readonly>
                                                <span class="input-group-text"><b>TND</b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($cart->cash_type == 'black')
                                <div class="row total-calculations">
                                    <div class="col-md-3">
                                        <h5>Total To Be Paid</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <input type="number" name="total_to_be_paid"
                                                value="{{ $cart->total_cost }}" class="form-control"
                                                aria-label="Amount (to the nearest dollar)" id="total_to_be_paid"
                                                class="form-control" min="0" max="100000000" readonly>
                                            <span class="input-group-text"><b>TND</b></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row" id="submit-button">
                                <div class="col-md-7"></div>
                                <div class="col-md-2 form-group text-right">
                                    <a href="{{ route('home_search') }}" id="back_to_shopping"
                                        class="btn btn-info">{{ trans('file.Back To Shopping') }}</a>
                                </div>
                                <div class="col-md-2 form-group text-right">
                                    <button type="button" id="save_changes"
                                        class="btn btn-success">{{ trans('file.Save Changes') }}</button>
                                </div>
                                <div class="col-md-1 form-group text-right">
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
    <script>
         $(document).ready(function() {
           

                if ($(window).outerWidth() > 1199) {
                    $('nav.side-navbar').toggleClass('shrink');
                    $('.page').toggleClass('active');
                 
                }
            // });
        })
        var t_qty = 0;
        let w_qty = 0;
        let b_qty = 0;
        var id_array = [];
        var total_quantity_of_all_row_products = 0;
        $('#submit_button').on('click', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Work in Progress',
                text: 'On click this button, items will be added to cart and then create a purchase...This work is in progress',

            });
            exit();
            $('#valueCheck').val(1);
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

            if (check_array.length <= 0) {
                document.getElementById("purchase-form").submit();
            }
        });

        $('#save_changes').on('click', function() {
            var check_array = [];

            $('#valueCheck').val(2);
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
            if (check_array.length <= 0) {
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


        // Calculations

        function alterCartQty(id) {
            item_qty = parseInt($("#item_qty" + id).val());
            var purchasePrice = parseFloat($("#purchase_price_" + id).val());
            var additional_cost_without_vat = parseFloat($("#additional_cost_without_vat_" + id).val());
            if (additional_cost_without_vat == null || isNaN(additional_cost_without_vat)) {
                additional_cost_without_vat = 0;
            }
            console.log("acwv", additional_cost_without_vat);
            var purchase_additional_cost = $('#purchase_additional_cost').val();
            if (purchase_additional_cost == null || isNaN(purchase_additional_cost)) {
                purchase_additional_cost = 0;
            }
            var entireAditionalCost = purchase_additional_cost;
            var cashType = "{{ $cart->cash_type }}";
            var total_actual = 0.0;

            var total_cost_without_vat = (purchasePrice * item_qty) + additional_cost_without_vat;

            $("#total_excluding_vat_" + id).val(total_cost_without_vat.toFixed(2));

            if (all_product_ids.length > 0) {

                all_product_ids.forEach(getActualProductCost);

                function getActualProductCost(idd, index) {
                    total_actual += parseFloat($('#total_excluding_vat_' + idd).val());
                    total_quantity_of_all_row_products += parseInt($("#item_qty" + idd).val());

                }
                var actual_cost_per_product = (total_cost_without_vat / item_qty) + (entireAditionalCost /
                    total_quantity_of_all_row_products);

            }

            $('#actual_cost_per_product_' + id).val(actual_cost_per_product.toFixed(2));
            var profit_margin = parseFloat($('#profit_margin_' + id).val() / 100);
            if (profit_margin == null || profit_margin == NaN) {
                profit_margin = 0;
            }
            var sale_price_per_product = actual_cost_per_product * (1 + profit_margin);
            sale_price_per_product = parseFloat(sale_price_per_product);
            $('#sale_price_' + id).val(sale_price_per_product.toFixed(2));



            calculateCartEntireTotal(all_product_ids);


            total_quantity_of_all_row_products = 0;
        }



        function calculateCartPrice() {
            if (all_product_ids.length > 0) {


                all_product_ids.forEach(getSalePrice);
                var actual_total = 0.0;

                function getSalePrice(id, index) {
                    item_qty = parseInt($("#item_qty" + id).val());
                    var purchasePrice = parseFloat($("#purchase_price_" + id).val());
                    var additional_cost_without_vat = parseFloat($("#additional_cost_without_vat_" + id).val());
                    var purchase_additional_cost = $('#purchase_additional_cost').val();
                    if (purchase_additional_cost == null || isNaN(purchase_additional_cost)) {
                        purchase_additional_cost = 0;
                    }
                    var entireAditionalCost = purchase_additional_cost;

                    if (additional_cost_without_vat == null || additional_cost_without_vat == NaN) {
                        additional_cost_without_vat = 0;
                    }

                    var total_cost_without_vat = (purchasePrice * item_qty) + additional_cost_without_vat;
                    $("#total_excluding_vat_" + id).val(total_cost_without_vat.toFixed(2));


                    total_quantity_of_all_row_products += parseInt($("#item_qty" + id).val());

                    var actual_cost_per_product = (total_cost_without_vat / item_qty) + (entireAditionalCost /
                        total_quantity_of_all_row_products);

                    $('#actual_cost_per_product_' + id).val(actual_cost_per_product.toFixed(2));
                    actual_total += actual_cost_per_product.toFixed(2);
                    var profit_margin = $('#profit_margin_' + id).val() / 100;
                    if (profit_margin == null || profit_margin == NaN) {
                        profit_margin = 0.0;
                    }
                    var sale_price_per_product = actual_cost_per_product * (1 + parseFloat(profit_margin));
                    sale_price_per_product = parseFloat(sale_price_per_product);
                    $('#sale_price_' + id).val(sale_price_per_product.toFixed(2));

                }
                calculateCartEntireTotal(all_product_ids);
                total_quantity_of_all_row_products = 0;
            }

        }

        function deleteCartArticle(id) {
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


            console.log("all_productidsss", all_product_ids);
            console.log("article_idsssssss", article_ids_array);
            if (all_product_ids.length <= 0) {
                $('#total_calculations').css('display', 'none');
                $('#submit-button').css('display', 'none');
                $("table thead").empty();


            }
            calculateCartEntireTotal(all_product_ids);
            // article_ids_array = [];
            if ($('#myTable tr').length == 0) {
                selected_cash_type = [];
            }
        }

        function changeCartTotalWithVAT() {
            var total_vat = 0;
            var total_actual = 0;
            var cashType = "{{ $cart->cash_type }}";
            var id_array = [];
            var cart_item_array = <?php echo json_encode($cart_items); ?>;
            id_array = all_product_ids.filter(onlyUnique);

            if (id_array.length > 0) {
                id_array.forEach(getActualProductCost);

                function getActualProductCost(id, index) {

                    if (cashType == "white") {
                        var vat = document.getElementById('vat_'+id).value;
                        console.log(vat,"iiiii",$("vat_"+ id).val())
                        if (vat % 1 != 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'VAT must be Type of Integer',

                                });
                                $('#vat_' + id).val(cart_item_array[index].vat)
                                error = 1;
                            }
                            if (vat > 100) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'VAT must be less or equal to 100',
                                });
                                $('#vat_' + id).val(cart_item_array[index].vat)
                                error = 1;
                            }
                        vat = $('#vat_' + id).val();
                        var add_cost_with_vat = $('#additional_cost_with_vat_' + id).val();
                        if (add_cost_with_vat == null || isNaN(add_cost_with_vat)) {
                            $('#additional_cost_with_vat_' + id).val(0);
                            add_cost_with_vat = 0;
                        }
                        total_vat = total_vat + parseFloat(vat / 100) + parseFloat(add_cost_with_vat);
                    }


                }
                var purchase_additional_cost = $('#purchase_additional_cost').val();
                if (purchase_additional_cost == null || isNaN(purchase_additional_cost)) {
                    purchase_additional_cost = 0;
                }
                total_vat = total_vat + parseFloat(purchase_additional_cost);
                console.log(total_vat)
                if (total_vat == null || isNaN(total_vat)) {
                    total_vat = 0;
                }

                $('#entire_vat').val(total_vat.toFixed(2));
                var entire_vat = parseFloat($('#entire_vat').val());
                var tax_stamp = parseFloat($('#tax_stamp').val());

                var tot_to_be_paid = $('#total_to_be_paid').val();
                var entire_total_exculding_vat = $('#entire_total_exculding_vat').val();
                if (tax_stamp == null || isNaN(tax_stamp)) {
                    tax_stamp = 0.0;
                }
                var total_to_be_paid = parseFloat(entire_total_exculding_vat) + parseFloat(entire_vat.toFixed(2)) +
                    parseFloat(tax_stamp.toFixed(
                        2));
                console.log('total flow', total_to_be_paid)
                console.log('total vat flow', total_vat)

                $('#total_to_be_paid').val(total_to_be_paid.toFixed(2));
            }
        }



        function calculateCartEntireTotal(product_ids_array) {
            var total_actual = 0.0;
            var total_vat = 0.0;
            var total_to_be_paid = 0.0;
            // console.log(product_ids_array)
            var cashType = "{{ $cart->cash_type }}";
            var id_array = [];
            id_array = product_ids_array.filter(onlyUnique);

            if (id_array.length > 0) {
                id_array.forEach(getActualProductCost);
                var cart_item_array = <?php echo json_encode($cart_items); ?>;
                function getActualProductCost(id, index) {
                    var qty = parseInt($("#item_qty" + id).val());
                    if (qty > 0) {
                        total_actual += parseFloat($('#total_excluding_vat_' + id).val());
                        if (cashType == "white") {
                            var vat = $('vat_'+id).val();
                            if (vat % 1 != 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'VAT must be Type of Integer',

                                });
                                $('#vat_' + id).val(cart_item_array[index].vat)
                                error = 1;
                            }
                            if (vat > 100) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'VAT must be less or equal to 100',
                                });
                                $('#vat_' + id).val(cart_item_array[index].vat)
                                error = 1;
                            }
                            vat = $('vat_'+id).val();
                            var add_cost_with_vat = $('#additional_cost_with_vat_' + id).val();
                            if (add_cost_with_vat == null || add_cost_with_vat == NaN) {
                                add_cost_with_vat = 0;
                                $('#additional_cost_with_vat_' + id).val(0)
                            }
                            total_vat = total_vat + parseFloat(vat / 100) + parseFloat(add_cost_with_vat);
                        }
                    }



                }
                var purchase_additional_cost = $('#purchase_additional_cost').val();
                if (!purchase_additional_cost) {
                    purchase_additional_cost = 0;
                }
                total_vat = total_vat + parseFloat(purchase_additional_cost);

                $('#entire_total_exculding_vat').val(total_actual.toFixed(2));
                $('#entire_vat').val(total_vat.toFixed(2));
                var tax_stamp = parseFloat($('#tax_stamp').val());
                console.log('stamp', tax_stamp)
                if (tax_stamp == null || tax_stamp == NaN) {
                    tax_stamp = 0;
                }
                total_to_be_paid = parseFloat(total_actual.toFixed(2)) + parseFloat(total_vat.toFixed(2)) +
                    parseFloat(
                        tax_stamp.toFixed(2));
                if (cashType == "white") {
                    $('#total_to_be_paid').val(total_to_be_paid.toFixed(2));
                } else if (cashType == "black") {
                    $('#total_to_be_paid').val(total_actual.toFixed(2));
                }

            }

        }

        function checkCartDiscount() {
            var id_array = [];
            var cart_item_array = <?php echo json_encode($cart_items); ?>;
            // console.log(cart_item_array);
            id_array = all_product_ids.filter(onlyUnique);
            if (id_array.length > 0) {
                id_array.forEach(checkDiscount);

                function checkDiscount(id, index) {
                    var discount = $('#discount_' + id).val();
                    if (discount % 1 != 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'discount must be Type of Integer',

                        });
                        $('#discount_' + id).val(cart_item_array[index].discount)
                        exit();
                    }
                    if (discount > 100) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount must be less or equal to 100',
                        });
                        $('#discount_' + id).val(cart_item_array[index].discount)
                        exit();
                    }
                }
            }
        }

        function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
        }
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
