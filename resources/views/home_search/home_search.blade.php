@extends('layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <link rel="stylesheet" href="{{ asset('css/purchase_create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home_search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/load_more_dropdown.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <section>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-0">
                        <form action="{{ route('search_sections_by_engine') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-header article_view_tr_head" style="padding: 9px !important;">
                                <div class="box">
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="home" onclick="selectEngineType()"
                                            checked>
                                        <span class="custom-radio-button designer">
                                            <i class="dripicons-home"></i> Home
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="V" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-car"></i> <span>PC</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="L" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-truck"></i> <span>LCV</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="B" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-motorcycle"></i> <span>Motorcycle</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="C" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-bus"></i> <span>CV</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="T" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-thin fa-tractor"></i> <span>Tractor</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="M" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-sharp fa-solid fa-gears"></i> <span>Engine</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="A" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-arrows-left-right"></i> <span>Axels</span>
                                        </span>
                                    </label>
                                    <label class="custom-radio-button__container">
                                        <input type="radio" name="sub_type" value="K" onclick="selectEngineType()">
                                        <span class="custom-radio-button">
                                            <i class="fa fa-solid fa-van-shuttle"></i> <span>CV Body Type</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="box">

                                    <label class="custom-radio-button__container">
                                        <input type="radio" id="p_type" name="type" value="P"
                                            onclick="selectEngineType()" checked>
                                        <span class="custom-radio-button main-type">
                                            <i class="fa fa-solid fa-car"></i> <span>Pessenger</span>
                                        </span>
                                    </label>

                                    <label class="custom-radio-button__container">
                                        <input type="radio" id="o_type" name="type" value="O"
                                            onclick="selectEngineType()">
                                        <span class="custom-radio-button main-type">
                                            <i class="fa fa-solid fa-bus"></i> <span>Commercial Vehicle & Tractor</span>
                                        </span>
                                    </label>

                                </div>
                                <div class="row home-search-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="manufacturer_id">{{ __('Select Manufacturer') }} <span
                                                    style="color: red;">*</span></label>
                                            <select name="manufacturer_id" id="manufacturer_id"
                                                data-href="{{ route('get_models_by_manufacturer_home_search') }}"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                                <option value="">Select One</option>
                                                @foreach ($manufacturers as $manufacturer)
                                                    <option value="{{ $manufacturer->manuId }}">
                                                        {{ $manufacturer->manuName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="model_id">{{ __('Select Model') }} <span
                                                    style="color: red;">*</span></label>
                                            <select name="model_id" id="model_id"
                                                data-href="{{ route('get_engines_by_model_home_search') }}"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="engine_id">{{ __('Select Engine') }} <span
                                                    style="color: red;">*</span></label>
                                            <select name="engine_id" id="engine_id"
                                                data-href="{{ route('get_data_of_engine_home_search') }}"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="model_year">{{ __('Model Year') }}</label>
                                            {{-- <select name="model_year" id="model_year" data-href="#"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>

                                            </select> --}}
                                            <input type="text" id="model_year" name="model_year" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fuel">{{ __('Fuel') }}</label>
                                            {{-- <select name="fuel" id="fuel" data-href="#"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select> --}}
                                            <input type="text" id="fuel" name="fuel" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cc">{{ __('CC') }}</label>
                                            {{-- <select name="cc" id="cc" data-href="#"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select> --}}
                                            <input type="text" id="cc" name="cc" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-right">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fa fa-solid fa-magnifying-glass"></i> Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-body">
                            <form action="{{ route('get_article_by_sub_sections') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brand_id">{{ __('Select Brand') }} <span
                                                    style="color: red;">*</span></label>
                                           
                                            <div class="dropdown">
                                                <div class="dropdown-header form-control">{{ __('Select Brand') }}</div>
                                                <div class="dropdown-content form-control">
                                                    <div class="normal-option">
                                                        @foreach ($brands as $brand)
                                                            <div class="option" data-brand_id="{{ $brand->brandId }}">
                                                                {{ $brand->brandName }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($brands_count > 10)
                                                    <div class="option more">Load More</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sub_section_id">{{ __('Select Product Group') }} <span
                                                    style="color: red;">*</span></label>
                                            <select name="sub_section_id" id="sub_section_id" data-href="#"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="dual_search" value="dual">
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" style="margin-top: 33px;" type="submit"><i
                                                class="fa fa-solid fa-magnifying-glass"></i> Search</button>
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
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>

    <script>
        // get manufacturers
        function selectEngineType() {
            var sub_type = $('input[name="sub_type"]:checked').val();
            var main_type = $('input[name="type"]:checked').val();
            if (sub_type == "V" || sub_type == "L" || sub_type == "B") {
                $('#p_type').prop('checked', true);
                $('#o_type').prop('checked', false);
            } else if (sub_type == "C" || sub_type == "T" || sub_type == "M" || sub_type == "A" || sub_type == "K") {
                $('#o_type').prop('checked', true);
                $('#p_type').prop('checked', false);
            } else {
                if (main_type == "O" && sub_type == "home") {
                    $('#p_type').prop('checked', false);
                    $('#o_type').prop('checked', true);
                } else if (main_type == "P" && sub_type == "home") {
                    $('#p_type').prop('checked', true);
                    $('#o_type').prop('checked', false);
                }

            }
            var type = $('input[name="type"]:checked').val();
            var url = "{{ url('get_home_manufacturers') }}";
            $.get(url + '?type=' + type + '&sub_type=' + sub_type, function(data) {

                let response = data.data;
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html += `<option value="${value.manuId}">${value.manuName}</option>`;
                });
                $('#manufacturer_id').html(view_html);
                $("#manufacturer_id").selectpicker("refresh");


            })
        }


        //get models==================
        $(document).on('change', '#manufacturer_id', function() {
            let manufacturer_id = $(this).val();
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            let url = $(this).attr('data-href');
            getModels(url, manufacturer_id, engine_type, engine_sub_type);
        });

        function getModels(url, manufacturer_id, engine_type, engine_sub_type) {
            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' +
                engine_type,
                function(data) {
                    $('#model_id').html('<option value="">Select One</option>');
                    $('#model_id').selectpicker("refresh");
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

        // get engines
        $(document).on('change', '#model_id', function() {
            let model_id = $(this).val();
            let url = $(this).attr('data-href');
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            getEngines(url, model_id, engine_sub_type, engine_type);
        });

        function getEngines(url, model_id, engine_sub_type, engine_type) {
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' + engine_type,
                function(data) {

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

        // get engine Data
        $(document).on('change', '#engine_id', function() {
            let engine_id = $(this).val();
            let url = $(this).attr('data-href');

            getEngineData(url, engine_id);
        });

        function getEngineData(url, engine_id) {
            $.get(url + '?engine_id=' + engine_id, function(data) {

                // $('#engine_id').html('<option value="">Select One</option>');
                // $('#engine_id').selectpicker("refresh");

                let response = data.data;

                $('#model_year').val(response.beginYearMonth != null ? response.beginYearMonth : 'N/A');
                $('#fuel').val(response.fuelType != null ? response.fuelType : 'N/A');
                $('#cc').val(response.capacityCC != null ? response.capacityCC : 'N/A');
            })
        }


        // load more script

        $('.dropdown-header').click(function(event) {
            $('.dropdown-content').toggle();
            event.stopPropagation();
        })

        $(window).click(function() {
            $('.dropdown-content').hide();
        })

        $(document.body).on('click', '.option:not(.more)', function(event) {
           var brand_id =$(this).data('brand_id');
           $('.dropdown-header').html($(this).html());
           
            
           var url = "{{ route('get_sub_sections_by_brand') }}";
            $.get(url + '?brand_id=' + brand_id, function(data) {


                let response = data;
                console.log(response);
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html +=
                        `<option value="${value.assemblyGroupNodeId}">${value.assemblyGroupName}</option>`;
                    $.each(value.sub_section, function(key_2, value_2) {
                        view_html +=
                            `<option value="${value_2.assemblyGroupNodeId}">${value_2.assemblyGroupName}</option>`;
                    });
                });
                // console.log(data, view_html);
                $('#sub_section_id').html(view_html);
                $("#sub_section_id").val(4);
                $("#sub_section_id").selectpicker("refresh");
            })
        })

        $('.option.more').click(function(event) {
            var count = "{{ $brands_count }}";
            $.ajax({
                url: "{{ route('load_more_brand') }}",
                method: "GET",
                success: function(data) {
                    let view_html = "";
                    $.each(data.brands, function(key, value) {
                        $('.normal-option').append($('<div class="option" data-brand_id="'+value.brandId+'">').html(value.brandName));
                    });

                    if (data.count >= count) {
                        $('.option.more').hide();
                    }
                    

                }
            });
            event.stopPropagation();
           
           
        })

        /// load more script end

        
    </script>
@endpush
