<div class="row">
    <div class="col-md-12">
        <div id="other_data"></div>
        <div class="row">
            {{-- <div class="col-md-4">
                <div class="form-group">
                    <label>{{ trans('file.Date') }}</label>
                    <input type="text" id="product_purchase_date" name="created_at" class="form-control date"
                        placeholder="Choose date" />
                </div>
            </div> --}}
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12 mt-3">
                        <label>{{trans('file.Select Product')}}</label>
                        <div class="search-box input-group">
                            <button class="btn btn-secondary"><i class="fa fa-barcode"></i></button>
                            <input type="text" name="product_code_name" id="lims_productcodeSearch"  placeholder="Please type product code and select..." class="form-control" />
                        </div>
                        
                    </div>
                </div>
            </div>
            <script>
            $("#lims_productcodeSearch").on("input", function() {
                alert($(this).val()); 
                // $.ajax({
                //     method: "GET",
                //     url: "{{ url('show_section_parts_in_table') }}",
                //     data: {
                //         id: id,
                //         engine_type: engine_type,
                //         engine_sub_type: engine_sub_type,
                //         manufacturer_id: manufacturer_id,
                //         model_id: model_id,
                //         engine_id: engine_id,
                //         section_id: section_id,
                //         section_part_id: section_part_id,
                //         supplier_id: supplier_id,
                //         brand_id: brandId,
                //         status: status,
                //         date: date,
                //         cash_type: cashType
                //     },

                //     success: function(data) {
                //         // alert(data);
                //         // $('#myTable').DataTable( {
                //         //     "processing": true,
                //         //     "searching" : true,
                //         // });
                //         $('#submit-button').css("display", "block");
                //         $('#order-table-header').text(`{{ trans('file.Order Table') }} *`);
                //         var tableBody = $("table tbody");
                //         var tableHead = $("table thead");
                //         var tableHeadRow = $("table thead tr");
                //         var other_data_div = $('#other_data');

                //         var white_cash_head = "";
                //         var black_cash_head = "";

                //         white_cash_head += `<tr id="">
                //         <th>{{ trans('file.name') }}</th>
                //         <th>{{ trans('file.Quantity') }}</th>
                //         <th>{{ trans('file.Purchase Price') }}</th>
                //         <th>{{ trans('file.Sale Price') }}</th>
                //         <th>{{ trans('file.Discount') }}</th>
                //         <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                //         <th>{{ trans('file.Additional Cost With VAT') }}</th>
                //         <th style="width:200px">{{ trans('file.VAT %') }}</th>
                //         <th>{{ trans('file.Profit Margin') }}</th>
                //         <th>{{ trans('file.Total Excluding Vat') }}</th>
                //         <th>{{ trans('file.Actual Cost Per Product') }}</th>
                //         <th><i class="dripicons-trash"></i></th>
                //     </tr>`;

                //         black_cash_head += `<tr id="">
                //         <th>{{ trans('file.name') }}</th>
                //         <th>{{ trans('file.Quantity') }}</th>
                //         <th>{{ trans('file.Purchase Price') }}</th>
                //         <th>{{ trans('file.Sale Price') }}</th>
                //         <th>{{ trans('file.Discount') }}</th>
                //         <th>{{ trans('file.Additional Cost Without VAT') }}</th>
                //         <th>{{ trans('file.Profit Margin') }}</th>
                //         <th>{{ trans('file.Total Excluding Vat') }}</th>
                //         <th>{{ trans('file.Actual Cost Per Product') }}</th>
                //         <th><i class="dripicons-trash"></i></th>
                //     </tr>`;

                //         var length = document.getElementById("myTable").rows.length;

                //         var html = '';
                //         html += '<input type="hidden" name="manufacturer_id[]" value="' + data
                //             .manufacturer_id + '">';
                //         html += '<input type="hidden" name="linkage_target_type[]" value="' + data
                //             .linkage_target_type + '">';
                //         html += '<input type="hidden" name="linkage_target_sub_type[]" value="' + data
                //             .linkage_target_sub_type + '">';
                //         html += '<input type="hidden" name="modell_id[]" value="' + data.model_id + '">';
                //         html += '<input type="hidden" name="enginee_id[]" value="' + data.engine_id + '">';
                //         html += '<input type="hidden" name="sectionn_id[]" value="' + data.section_id +
                //             '">';
                //         html += '<input type="hidden" name="sectionn_part_id[]" value="' + data
                //             .section_part_id + '">';
                //         html += '<input type="hidden" name="statuss[]" value="' + data.status + '">';
                //         html += '<input type="hidden" name="datee[]" value="' + data.date + '">';
                //         html += '<input type="hidden" name="cash_type" value="' + data.cash_type + '">';
                //         html += '<input type="hidden" name="brand_id[]" value="' + data.brand_id + '">';

                //         $('#myTable tr').each(function() {
                //             if (this.id != '') {
                //                 article_ids_array.push(this.id)
                //             }
                //         })

                //         if (supplier_ids_array.length > 0) {
                //             supplier_ids_array.forEach(checkSupplier);

                //             function checkSupplier(item, index) {
                //                 if (item != data.supplier) {
                //                     Swal.fire({
                //                         icon: 'error',
                //                         title: 'Oops...',
                //                         text: 'You have already selected a supplier , you are not to allowed to change the supplier during one purchase',
                //                     });
                //                     exit();
                //                 }
                //             }
                //         } else {
                //             supplier_ids_array.push(data.supplier);
                //         }
                //         if (selected_cash_type.length > 0) {
                //             selected_cash_type.forEach(checkCashType);

                //             function checkCashType(element, index, data) {
                //                 console.log(element, index, data, )
                //                 if (element != cashType) {
                //                     Swal.fire({
                //                         icon: 'error',
                //                         title: 'Oops...',
                //                         text: 'you can not able to change the cash type for a purchase once you selected',
                //                     });
                //                     exit();
                //                 }
                //             }

                //         } else {
                //             selected_cash_type.push(cashType);

                //         }
                //         if (data.cash_type == "white" && tableHeadRow.length <= 0) {
                //             tableHead.append(white_cash_head);
                //         } else if (data.cash_type == "black" && tableHeadRow.length <= 0) {
                //             tableHead.append(black_cash_head);
                //         }

                //         markup = '<tr id="article_' + data.data.legacyArticleId + '"><td>' + data.data
                //             .genericArticleDescription + '-' + data.data.articleNumber +
                //             '</td>';

                //         markup +=
                //             '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterQty(' +
                //             data.data.legacyArticleId + ')" id="item_qty' + data.data
                //             .legacyArticleId +
                //             '" value="1" min="1" name="item_qty[]" required></td>';

                //         markup +=
                //             '<td><input style="width:100px" type="number" class="form-control" onkeyup="alterQty(' +
                //             data.data.legacyArticleId +
                //             ')" value="1" min="1" step="any" id="purchase_price_' +
                //             data.data.legacyArticleId +
                //             '" name="purchase_price[]" required></td>';

                //         markup +=
                //             '<td><input style="width:100px" type="number" class="form-control"  id="sale_price_' +
                //             data.data.legacyArticleId +
                //             '" name="sale_price[]" readonly></td>';

                //         markup +=
                //             '<td><input type="number" class="form-control" value="0" min="1" step="any" id="discount_' +
                //             data.data.legacyArticleId +
                //             '" name="discount[]"></td>';

                //         markup +=
                //             '<td><input type="number" class="form-control" value="0" min="1" step="any"  onkeyup="alterQty(' +
                //             data.data.legacyArticleId + ')" id="additional_cost_without_vat_' + data.data
                //             .legacyArticleId +
                //             '" name="additional_cost_without_vat[]"></td>';
                //         if (data.cash_type == "white") {
                //             markup +=
                //                 '<td><input type="number" class="form-control" value="0" min="1" step="any" id="additional_cost_with_vat_' +
                //                 data.data.legacyArticleId +
                //                 '" name="additional_cost_with_vat[]"></td>';
                //         }

                //         if (data.cash_type == "white") {
                //             markup +=
                //                 '<td><input style="width:100px" type="number" class="form-control" value="0" min="1" step="any" id="vat_' +
                //                 data.data.legacyArticleId +
                //                 '" name="vat[]" required></td>';
                //         }

                //         markup +=
                //             '<td><input type="number" style="width:100px" class="form-control" value="0" min="1" step="any"   onkeyup="alterQty(' +
                //             data.data.legacyArticleId + ')" id="profit_margin_' + data.data
                //             .legacyArticleId +
                //             '" name="profit_margin[]" required></td>';

                //         markup +=
                //             '<td><input style="width:100px" type="number" class="form-control" value="0" min="0"   id="total_excluding_vat_' +
                //             data.data.legacyArticleId +
                //             '" name="total_excluding_vat[]" readonly></td>';

                //         markup +=
                //             '<td><input type="number" style="width:100px" class="form-control" value="0" min="0"   id="actual_cost_per_product_' +
                //             data.data.legacyArticleId +
                //             '" name="actual_cost_per_product[]" readonly></td>';

                //         markup += '<td><i id="article_delete_' +
                //             data.data.legacyArticleId + '" onclick="deleteArticle(' + data.data
                //             .legacyArticleId +
                //             ')" class="fa fa-trash"></i></td>';

                //         markup += '<td style="display:none;">' + html +
                //             '</td></tr>';

                //         if (length <= 1) {
                //             tableBody.append(markup);
                //             $('#myTable tr').each(function() {
                //                 if (this.id != '') {
                //                     article_ids_array.push(this.id)
                //                 }
                //             });

                //         } else {
                //             if (!article_ids_array.includes("article_" + data.data.legacyArticleId)) {
                //                 tableBody.append(markup);
                //             } else {
                //                 Swal.fire({
                //                     icon: 'error',
                //                     title: 'Oops...',
                //                     text: 'This product is already added...you can update its quantity',
                //                 })
                //             }
                //         }
                //         if ($('#myTable tr').length <= 1) {
                //             selected_cash_type = [];
                //         }
                //         all_product_ids.push(data.data.legacyArticleId);
                //     }
                // });
             });
             </script>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn"
                        id="save-btn">{{ trans('file.Save') }}</button>
                </div>
            </div>
          
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

