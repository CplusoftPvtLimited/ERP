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
                            {!! Form::open(['route' => 'sales.store', 'method' => 'post', 'files' => true, 'class' => 'payment-form']) !!}
                            {{-- {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!} --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Date') }}</label>
                                                <input type="text" id="product_sale_date" name="created_at"
                                                    class="form-control date" placeholder="Choose date" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Customer') }}</label>
                                                <select name="customer" id="customer_id" class="form-control">
                                                    <option value="" disabled selected>select customer</option>
                                                    <option value="walk_in">walk In</option>
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
                                                <label>{{ trans('file.Order Tax') }}</label>
                                                <select class="form-control" name="order_tax_rate">
                                                    <option value="" disabled selected>select one</option>
                                                        <option value="{{ $lims_tax_list->rate }}">{{ $lims_tax_list->meta }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Order Discount Type') }}</label>
                                                <select id="order_discount_type" name="order_discount_type"
                                                    class="form-control">
                                                    <option value="Flat">{{ trans('file.Flat') }}</option>
                                                    <option value="Percentage">{{ trans('file.Percentage') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Value') }}</label>
                                                <input type="text" name="order_discount_value"
                                                    class="form-control numkey" id="order_discount_val">
                                                <input type="hidden" name="order_discount" class="form-control"
                                                    id="order_discount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('file.Shipping Cost') }}
                                                </label>
                                                <input type="number" name="shipping_cost" id="shipping_cost" class="form-control"
                                                    step="any" />
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
                            <div class="row" id="submit-button" style="display: none;">
                                <div class="col-md-12 form-group text-right">
                                    <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
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
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
