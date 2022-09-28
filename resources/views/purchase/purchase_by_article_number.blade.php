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
                            {{-- <select id="lims_productcodeSearch" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-show-tick="false" data-live-search-style="contains"> --}}
                            {{-- </select>   --}}
                            <input type="text" name="product_code_name" id="lims_productcodeSearch"  placeholder="Please type product code and select..." class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // var options = {
                    
                //     ajax: {
                //         method: "GET",
                //         url: "{{ url('articles') }}",
                //         data: {
                //             name: name
                //         },

                //         success: function(data) {
                //             return data;
                //         }
                //     },

                //     locale: {
                //         emptyTitle: 'Select and Begin Typing'
                //     },

                //     preprocessData: function (data) {
                //     var i, l = data.length, array = [];
                //     if (l) {
                //         for (i = 0; i < l; i++) {
                //             var curr = data[i];
                //             console.log(data[i]);
                //             array.push({
                //                 'value': curr.value,
                //                 'text': curr.text,
                //                 'disabled': curr.disabled,
                //                 'selected': curr.selected,
                //             });
                //         }
                //     }
                //     return array;
                //     },

                //     preserveSelected: false
                //     };
                //     $('#lims_productcodeSearch').selectpicker({liveSearch: true }).ajaxSelectPicker(options);

                    // $('#lims_productcodeSearch').selectpicker();
            $("#lims_productcodeSearch").on("input", function() {
                let name = $(this).val();
                $.ajax({
                    method: "GET",
                    url: "{{ url('articles') }}",
                    data: {
                        name: name
                    },

                    success: function(data) {
                        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
                        response($.grep(lims_product_code, function(item) {
                            return matcher.test(item);
                        }));
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
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

