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
                            <h3>Add Sale</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <span></span>
                            </div>
                            {!! Form::open(['route' => 'sales.store', 'method' => 'post','id' => 'sale_form', 'files' => true, 'class' => 'payment-form']) !!}
                            {{-- {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!} --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Date') }}</label>
                                                <input type="text" id="product_sale_date" name="date"
                                                    class="form-control date" placeholder="Choose date" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Customer') }}</label>
                                                <select name="customer_id" id="customer_id" class="form-control">
                                                    {{-- <option value="" disabled selected>select customer</option> --}}
                                                    <option value="1">Walkin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Cash Type') }}</label>
                                                <select name="cash_type" id="cash_type" class="form-control">
                                                    <option value="white">{{ trans('file.Primary Cash') }}</option>
                                                    <option value="black">{{ trans('file.Secondary Cash') }}</option>
                                                </select>
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
                                                
                                                    <div class="input-group mb-3">     
                                                        <input type="number" name="shipping_cost" value="0" min="0" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" id="shipping_cost"
                                                            class="form-control">
                                                        <span class="input-group-text"><b>TND</b></span>
                                                    </div>
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
                                                <textarea rows="3" class="form-control" name="sale_note" id="sale_note"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ trans('file.Staff Note') }}</label>
                                                <textarea rows="3" class="form-control" name="staff_note" id="staff_note"></textarea>
                                            </div>
                                        </div>
                                    </div>
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
                                                @include('sale.sale_by_flow')
                                            </div>

                                            <div id="Paris" class="tabcontent">
                                                
                                                
                                                @include('sale.sale_by_article_number')
                                            </div>

                                            <div id="Tokyo" class="tabcontent">
                                                @include('sale.sale_by_plate_number')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('purchase.order-table')
                            <div class="row p-5"  id="total_sale_calculations" >
                            
                            </div>
                            <div class="row" id="submit-button" style="display: none;">
                                <div class="col-md-12 form-group text-right">
                                    <button type="button" id="sale_submit_button" class="btn btn-primary">{{ trans('file.submit') }}</button>
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
        $('#sale_submit_button').on('click', function() {
            if (selected_cash_type.length > 0) {
                var cashType = $('#cash_type').find(":selected").val();
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

                }
            document.getElementById("sale_form").submit();
        });

        $("#product_sale_date").on('change', function() {
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
            if(selected_date_2 > today_date_2)
                {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select the current date! currently you are not be able to add the purchase on future date',
                });
                $('#product_sale_date').val('');
                exit();
            }
        });
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
