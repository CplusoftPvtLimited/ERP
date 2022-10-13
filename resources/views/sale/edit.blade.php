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
                            <h3>Edit Sale</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <span></span>
                            </div>

                            <form action="{{ route('sales.update', $sale->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                {{-- {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!} --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('file.Date') }}</label>
                                                    <input type="text" id="product_sale_date" name="date"
                                                        class="form-control" value="{{ $sale->date }}" readonly />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('file.Customer') }}</label>
                                                    {{-- <select name="customer_id" id="customer_id" class="form-control">
                                                    <option value="" disabled selected>select customer</option>
                                                    <option value="1">walk In</option>
                                                </select> --}}
                                                    @if ($sale->customer_id == 1)
                                                        <input type="text" class="form-control" value="Walkin" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('file.Cash Type') }}</label>
                                                    @if ($sale->cash_type == 'white')
                                                        <input type="text" class="form-control" value="White Cash"
                                                            readonly>
                                                    @else
                                                        <input type="text" class="form-control" value="Black Cash"
                                                            readonly>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.VAT') }}</label>
                                                <input type="number" min="0" value="0" name="entire_vat"
                                                    class="form-control numkey" id="vat">
                                                <input type="hidden" name="vat" class="form-control"
                                                    id="">
                                            </div>
                                        </div> --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>
                                                        {{ trans('file.Shipping Cost') }}
                                                    </label>
                                                    <input type="number" name="shipping_cost" min="0"
                                                        value="{{ $sale->shipping_cost }}" id="shipping_cost"
                                                        class="form-control" step="any" />
                                                </div>
                                            </div>
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
                                        </div>
                                        {{-- <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Sale Discount') }}</label>
                                                <input type="number" step="any" value="0" min="0" id="sale_discount" name="sale_discount"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Tax Stamp') }}</label>
                                                <input type="number" step="any" value="0" min="0" id="tax_stamp" name="tax_stamp"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        
                                    </div> --}}

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ trans('file.Sale Note') }}</label>
                                                    <textarea rows="3" class="form-control" name="sale_note" id="sale_note">{{ $sale->sale_note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ trans('file.Staff Note') }}</label>
                                                    <textarea rows="3" class="form-control" name="staff_note" id="staff_note">{{ $sale->staff_note }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4" id="sale_order_table">
                                    <div class="col-md-12">
                                        <h5 id="order-table-header"></h5>
                                        <div class="table-responsive mt-3">
                                            <table id="myTable" class="table table-hover order-list table-responsive">
                                                <thead>
                                                    @if ($sale->cash_type == 'white')
                                                        <tr id="">
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Quantity') }}</th>

                                                            <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                                                            <th>{{ trans('file.Discount (%)') }} <span>Optional</span></th>

                                                            <th style="width:200px">{{ trans('file.VAT %') }}</th>

                                                            <th>{{ trans('file.Total (With Discount) Excluding Vat') }}
                                                            </th>
                                                            <th>Action</th>

                                                        </tr>
                                                    @else
                                                        <tr id="">
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Quantity') }}</th>
                                                            <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                                                            <th>{{ trans('file.Discount (%)') }}</th>

                                                            <th>{{ trans('file.Total (With Discount)') }}</th>
                                                            <th>{{ trans('file.Total (Without Discount)') }}</th>
                                                            <th><i class="dripicons-trash"></i></th>
                                                        </tr>
                                                    @endif
                                                </thead>
                                                <tbody>
                                                    @foreach ($sale_products as $sale_product)
                                                        @if ($sale->cash_type == 'white')
                                                            <tr id="item_row_"{{ $sale_product->id }}>
                                                                <td>
                                                                    {{ $sale_product->reference_no }}
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        id="sale_item_qty_{{ $sale_product->id }}"
                                                                        name="sale_item_qty[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->quantity }}">
                                                                </td>
                                                                <td width="150px">
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="sale_price_{{ $sale_product->id }}"
                                                                        name="sale_price[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->sale_price }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="discount_{{ $sale_product->id }}"
                                                                        name="discount[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->discount }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any" id="vat_{{ $sale_product->id }}"
                                                                        name="vat[]" value="{{ $sale_product->vat }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="sale_total_with_discount_{{ $sale_product->id }}"
                                                                        name="sale_total_with_discount[]"
                                                                        value="{{ $sale_product->total_with_discount }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                        <button class="btn btn-danger btn-sm"><i id="article_delete" onclick="deleteSaleArticleItem({{ $sale_product->id }})" class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr id="item_row_"{{ $sale_product->id }}>
                                                                <td>
                                                                    {{ $sale_product->reference_no }}
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        id="sale_item_qty_{{ $sale_product->id }}"
                                                                        name="sale_item_qty[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->quantity }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="sale_price_{{ $sale_product->id }}"
                                                                        name="sale_price[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->sale_price }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="discount_{{ $sale_product->id }}"
                                                                        name="discount[]"
                                                                        onkeyup="editSaleQty({{ $sale_product->id }})"
                                                                        value="{{ $sale_product->discount }}">
                                                                </td>
                                                               
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="sale_total_with_discount_{{ $sale_product->id }}"
                                                                        name="total_with_discount[]"
                                                                        value="{{ $sale_product->total_with_discount }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        step="any"
                                                                        id="sale_total_without_discount_{{ $sale_product->id }}"
                                                                        name="sale_total_without_discount[]"
                                                                        value="{{ $sale_product->total_without_discount }}"
                                                                        readonly>
                                                                </td>
                                                                <td><i id="article_delete"
                                                                        onclick="deleteSaleArticleItem({{ $sale_product->id }})"
                                                                        class="fa fa-trash"></i></td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if($sale->cash_type == 'white')
                                <div class="row p-5" id="total_sale_calculations">
                                    <div class="col-md-12">
                                        <div class="row total-calculations">
                                            <div class="col-md-4">
                                                <h5>Total Exculding VAT (Before Discount)</h5>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="sale_entire_total_exculding_vat"
                                                    id="sale_entire_total_exculding_vat"
                                                    value="{{ $sale->sale_entire_total_exculding_vat }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row total-calculations">
                                            <div class="col-md-4">
                                                <h5>Discount <span style="font-size:10px;color:#98AFC7">(value)</span></h5>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="sale_discount" id="sale_discount"
                                                    onkeyup="calculateEditSaleTotal()" value="{{ $sale->discount }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row total-calculations">
                                            <div class="col-md-4">
                                                <h5>VAT <span style="font-size:10px;color:#98AFC7">(value)</span></h5>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="entire_vat" value="{{ $sale->entire_vat }}"
                                                    min="1" onkeyup="calculateEditSaleTotal()"
                                                    id="sale_entire_vat" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row total-calculations">
                                            <div class="col-md-4">
                                                <h5>Tax Stamp</h5>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="tax_stamp" id="sale_tax_stamp"
                                                    onkeyup="calculateEditSaleTotal()" class="form-control"
                                                    min="0" value="{{ $sale->tax_stamp }}" step="any">
                                            </div>
                                        </div>
                                        <div class="row total-calculations">
                                            <div class="col-md-4">
                                                <h5>Total To Be Paid</h5>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" value="{{ $sale->total_bill }}"
                                                    name="total_to_be_paid" id="total_to_be_paid" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row" id="submit-button">
                                    <div class="col-md-12 form-group text-right">
                                        <button type="submit" id="sale_submit_button"
                                            class="btn btn-primary">{{ trans('file.submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        var ids = <?php echo json_encode($sale_products); ?>;
        var product_ids = [];
        for (var i = 0; i < ids.length; i++) {
            product_ids.push(ids[i].id);
        }
        console.log(product_ids)



        function editSaleQty(id) {

            var item_qty = parseInt($("#sale_item_qty_" + id).val());
            // var stock = parseInt($("#stock_items_" + id).val());
            // if (item_qty > stock) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: 'Quantity must not be greater than Stock',

            //     });
            //     $("#sale_item_qty" + id).val(stock - parseInt(1));
            //     exit();
            // }

            var sale_price = parseFloat($("#sale_price_" + id).val());
            var discount = (parseFloat(1) - (parseFloat($("#discount_" + id).val() / 100)));
           
            var sale_total_with_discount = (item_qty * sale_price) * discount;
            var sale_total_without_discount = (item_qty * sale_price);
            if (sale_total_with_discount <= 0) {
                $('#sale_total_with_discount_' + id).val(0);
            } else {
                $('#sale_total_with_discount_' + id).val(sale_total_with_discount.toFixed(2))
            }

            if (sale_total_without_discount <= 0) {
                $('#sale_total_without_discount_' + id).val(0);
            } else {
                $('#sale_total_without_discount_' + id).val(sale_total_without_discount.toFixed(2))
            }
            calculateEditEntireSaleTotal(product_ids);
        }

        function calculateEditEntireSaleTotal(product_ids) {
            var total_before_discount = 0.0;
            var total_to_be_paid = 0.0;


            if (product_ids.length > 0) {
                product_ids.forEach(getActualProductCost);

                function getActualProductCost(id, index) {

                    total_before_discount += (parseInt($('#sale_item_qty_' + id).val()) * parseFloat($('#sale_price_' +
                        id).val()));
                }


                var tax_stamp = parseFloat($('#sale_tax_stamp').val());
                var entire_vat = parseFloat($('#sale_entire_vat').val());
                var discount = parseFloat($('#sale_discount').val());
                $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));
                total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount.toFixed(2))) *
                    parseFloat(entire_vat.toFixed(2)) + parseFloat(tax_stamp.toFixed(2));

                $('#total_to_be_paid').val(total_to_be_paid.toFixed(2));


            }
            // }
        }

        function calculateEditSaleTotal() {
            var total_before_discount = 0.0;
            var total_to_be_paid = 0.0;
            // console.log(product_ids_array)


            if (product_ids.length > 0) {
                product_ids.forEach(getActualProductCost);

                function getActualProductCost(id, index) {

                    total_before_discount += (parseInt($('#sale_item_qty_' + id).val()) * parseFloat($('#sale_price_' + id)
                        .val()));
                }


                var tax_stamp = parseFloat($('#sale_tax_stamp').val());
                var entire_vat = parseFloat($('#sale_entire_vat').val());
                var discount = parseFloat($('#sale_discount').val());
                $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));
                total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount.toFixed(2))) *
                    parseFloat(entire_vat.toFixed(2)) + parseFloat(tax_stamp.toFixed(2));

                $('#total_to_be_paid').val(total_to_be_paid.toFixed(2));


            }
        }

        // delete sale product

        function deleteSaleArticleItem(id) {


            $.ajax({
                url: '{{ url('sale_product_delete') }}',
                method: "get",
                data: {
                    id: id,
                },
                success: function(data) {
                    if (data == 1) {
                        // window.location = "sales";
                        // alert('jghghgh')
                        window.location.href = "{{ url('sales') }}";
                    } else if (data == 2) {

                        Swal.fire({
                            icon: 'success',

                            text: "Item Deleted successfully",
                        });
                    } else if (data == 3) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Item not found",
                        });
                    }
                }
            });
            // article_ids_array = [];
            if ($('#myTable tr').length == 0) {
                selected_cash_type = [];
            }
        }
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
