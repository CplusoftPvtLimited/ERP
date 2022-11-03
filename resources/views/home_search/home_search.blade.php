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

                                            <div class="dropdown">
                                                <div class="dropdown-header manufacturer form-control">
                                                    {{ __('Select Manufacturer') }}</div>
                                                <div class="dropdown-content manufacturer_content form-control">
                                                    <div class="manufacturer_normal_option">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <div class="manufacturer_option"
                                                                data-manufacturer_id="{{ $manufacturer->manuId }}">
                                                                {{ $manufacturer->manuName }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="more manufacturer_more" id="manufacturer_more"> <span>Load
                                                            More &nbsp;&nbsp;<span> <span style="display:none;"
                                                                    id="manufacturer_load_icon" class="loader4"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="model_id">{{ __('Select Model') }} <span
                                                    style="color: red;">*</span></label>
                                            {{-- <select name="model_id" id="model_id"
                                                data-href="{{ route('get_engines_by_model_home_search') }}"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select> --}}
                                            <div class="dropdown">
                                                <div class="dropdown-header model form-control">
                                                    {{ __('Select Model') }}</div>
                                                <div class="dropdown-content model_content form-control">
                                                    <div class="model_normal_option">

                                                    </div>
                                                    <div class="more model_more" id="model_more"> <span>Load More
                                                            &nbsp;&nbsp;<span> <span style="display:none;"
                                                                    id="model_load_icon" class="loader4"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="engine_id">{{ __('Select Engine') }} <span
                                                    style="color: red;">*</span></label>
                                            {{-- <select name="engine_id" id="engine_id"
                                                data-href="{{ route('get_data_of_engine_home_search') }}"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select> --}}
                                            <input type="hidden" id="engine_id" name="engine_id">
                                            <div class="dropdown">
                                                <div class="dropdown-header engine form-control">
                                                    {{ __('Select Engine') }}</div>
                                                <div class="dropdown-content engine_content form-control">
                                                    <div class="engine_normal_option">

                                                    </div>
                                                    <div class="more engine_more" id="engine_more"> <span>Load More
                                                            &nbsp;&nbsp;<span> <span style="display:none;"
                                                                    id="engine_load_icon" class="loader4"></span></div>
                                                </div>
                                            </div>
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
                                                <div class="dropdown-header brands form-control">{{ __('Select Brand') }}
                                                </div>
                                                <div class="dropdown-content brands_content form-control">
                                                    <div class="normal-option">
                                                        @foreach ($brands as $brand)
                                                            <div class="option" data-brand_id="{{ $brand->brandId }}">
                                                                {{ $brand->brandName }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if ($brands_count > 10)
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
                                            {{-- <select name="sub_section_id" id="sub_section_id" data-href="#"
                                                class="selectpicker form-control" data-live-search="true"
                                                data-live-search-style="begins" required>
                                            </select> --}}
                                            <div class="dropdown">
                                                <div class="dropdown-header product_group form-control">
                                                    {{ __('Select Product Group') }}</div>
                                                <div class="dropdown-content product_group_content form-control">
                                                    <div class="product_group_normal_option">

                                                    </div>
                                                    <div class="more product_group_more">Load More</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="dual_search" value="dual">
                                    <input type="hidden" name="sub_section_id" id="sub_section_id">
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
        $(document).ready(function() {
            // $('#search_home').on('click', function(e) {

            //     e.preventDefault();

            if ($(window).outerWidth() > 1199) {
                $('nav.side-navbar').toggleClass('shrink');
                $('.page').toggleClass('active');
                // window.location.href = "/home_search";
            }
            // });
        })
        // get manufacturers // load more script for get manufacturers
        var manufacturer_id_check_array = [];
        $('.dropdown-header.manufacturer').click(function(event) {
            $('.dropdown-content.manufacturer_content').toggle();
            event.stopPropagation();
        })

        $(window).click(function() {
            $('.dropdown-content').hide();
        })

        function selectEngineType() {
            manufacturer_id_check_array = [];
            $('.dropdown-header.manufacturer').html("Select Manufacturer");
            $('.manufacturer_normal_option').empty();
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
            $.get(url + '?type=' + type + '&sub_type=' + sub_type + '&main=1', function(data) {

                let response = data.data;
                if (data.manu_more_data['value'] > data.total_count) {
                    document.getElementById('manufacturer_more').style.display = "none";
                } else {
                    document.getElementById('manufacturer_more').style.display = "block";
                }
                $.each(response, function(key, value) {
                    manufacturer_id_check_array.push(value.manuId);
                    $('.manufacturer_normal_option').append($(
                        '<div class="manufacturer_option" id="manu_id" data-manufacturer_id="' +
                        value.manuId + '">').html(value.manuName));
                });



            })
        }
        $('.more.manufacturer_more').click(function(event) {
            document.getElementById('manufacturer_load_icon').style.display = "block";
            var sub_type = $('input[name="sub_type"]:checked').val();
            var main_type = $('input[name="type"]:checked').val();
            if (sub_type == "V" || sub_type == "L" || sub_type == "B") {
                $('#p_type').prop('checked', true);
                $('#o_type').prop('checked', false);
            } else if (sub_type == "C" || sub_type == "T" || sub_type == "M" || sub_type == "A" || sub_type ==
                "K") {
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
            $.get(url + '?type=' + type + '&sub_type=' + sub_type + '&load=1', function(data) {

                let response = data.data;
                console.log(data)
                document.getElementById('manufacturer_load_icon').style.display = "none";
                if (data.manu_more_data['value'] > data.total_count) {
                    document.getElementById('manufacturer_more').style.display = "none";
                } else {
                    document.getElementById('manufacturer_more').style.display = "block";
                }
                $.each(response, function(key, value) {
                    if (!manufacturer_id_check_array.includes(value.manuId)) {
                        manufacturer_id_check_array.push(value.manuId);
                        $('.manufacturer_normal_option').append($(
                            '<div class="manufacturer_option" id="manu_id" data-manufacturer_id="' +
                            value.manuId + '">').html(value.manuName));
                    }

                });
            })
            event.stopPropagation();


        })
        var model_id_check_array = [];
        $('.dropdown-header.model').click(function(event) {
            $('.dropdown-content.model_content').toggle();
            event.stopPropagation();
        })
        var manufacturer_id_set = 0;
        $(document.body).on('click', '.manufacturer_option:not(.manufacturer_more)', function(
            event) { // click on brand to get sections
            model_id_check_array = [];
            $('.dropdown-header.model').html("Select Model");
            $('.model_normal_option').empty();
            var manufacturer_id = $(this).data('manufacturer_id');
            manufacturer_id_set = manufacturer_id;
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            let url = "{{ route('get_models_by_manufacturer_home_search') }}";
            $('.dropdown-header.manufacturer').html($(this).html());
            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type +
                '&engine_type=' + engine_type + '&main=1',
                function(data) {

                    let response = data.data;
                    if (data.load_more_model['value'] > data.total_count) {
                        document.getElementById('model_more').style.display = "none";
                    } else {
                        document.getElementById('model_more').style.display = "block";
                    }
                    $.each(response, function(key, value) {

                        model_id_check_array.push(value.modelId);
                        $('.model_normal_option').append($('<div class="model_option" data-model_id="' +
                            value.modelId + '">').html(value.modelname));
                    });



                })
        })

        $('.more.model_more').click(function(event) {
            document.getElementById('model_load_icon').style.display = "block";
            var manufacturer_id = manufacturer_id_set;
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            let url = "{{ route('get_models_by_manufacturer_home_search') }}";

            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type +
                '&engine_type=' +
                engine_type + '&load=1',
                function(data) {

                    let response = data.data;
                    document.getElementById('model_load_icon').style.display = "none";
                    if (data.load_more_model['value'] > data.total_count) {
                        document.getElementById('model_more').style.display = "none";
                    } else {
                        document.getElementById('model_more').style.display = "block";
                    }
                    var error = [];
                    $.each(response, function(key, value) {
                        if (!model_id_check_array.includes(value.modelId)) {
                            $('.model_normal_option').append($(
                                '<div class="model_option" data-model_id="' +
                                value.modelId + '">').html(value.modelname));
                            error.push('data');
                        }
                    });
                })
            event.stopPropagation();
        })

        var engine_id_check_array = [];
        var model_id_set = 0;
        $('.dropdown-header.engine').click(function(event) {
            $('.dropdown-content.engine_content').toggle();
            event.stopPropagation();
        })
        $(document.body).on('click', '.model_option:not(.model_more)', function(
            event) { // click on brand to get sections
            $('.dropdown-header.model').html($(this).html());
            var model_id = $(this).data('model_id');
            model_id_set = model_id;
            let url = "{{ route('get_engines_by_model_home_search') }}";
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();

            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' +
                engine_type + "&main=1",
                function(data) {
                    let response = data.data;
                    console.log(response)

                    document.getElementById('engine_load_icon').style.display = "none";
                    if (data.load_more_engine['value'] > data.total_count) {
                        document.getElementById('engine_more').style.display = "none";
                    } else {
                        document.getElementById('engine_more').style.display = "block";
                    }

                    $.each(response, function(key, value) {
                        engine_id_check_array.push(value.linkageTargetId);
                        $('.engine_normal_option').append($(
                            '<div class="engine_option" data-engine_id="' +
                            value.linkageTargetId + '">').html(value.description + "(" + value
                            .beginYearMonth + " - " + value.endYearMonth));
                    });
                })

        })

        $('.more.engine_more').click(function(event) {
            document.getElementById('engine_load_icon').style.display = "block";
            var model_id = model_id_set;
            let url = "{{ route('get_engines_by_model_home_search') }}";
            let engine_sub_type = $('input[name="sub_type"]:checked').val();
            let engine_type = $('input[name="type"]:checked').val();
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + '&engine_type=' +
                engine_type + "&load=1",
                function(data) {
                    let response = data.data;


                    document.getElementById('engine_load_icon').style.display = "none";
                    if (data.load_more_engine['value'] > data.total_count) {
                        document.getElementById('engine_more').style.display = "none";
                    } else {
                        document.getElementById('engine_more').style.display = "block";
                    }

                    $.each(response, function(key, value) {
                        if (!engine_id_check_array.includes(value.linkageTargetId)) {
                            engine_id_check_array.push(value.linkageTargetId);
                            $('.engine_normal_option').append($(
                                '<div class="engine_option" data-engine_id="' +
                                value.linkageTargetId + '">').html(value.description + "(" +
                                value.beginYearMonth + " - " + value.endYearMonth));
                        }

                    });
                })
        })

        $(document.body).on('click', '.engine_option:not(.engine_more)', function(
            event) { // click on brand to get sections
            $('.dropdown-header.engine').html($(this).html());
            $('#engine_id').val($(this).data('engine_id'))
            var url = "{{ route('get_data_of_engine_home_search') }}";
            var engine_id = $(this).data('engine_id');
            $.get(url + '?engine_id=' + engine_id, function(data) {

                // $('#engine_id').html('<option value="">Select One</option>');
                // $('#engine_id').selectpicker("refresh");

                let response = data.data;

                $('#model_year').val(response.beginYearMonth != null ? response.beginYearMonth : 'N/A');
                $('#fuel').val(response.fuelType != null ? response.fuelType : 'N/A');
                $('#cc').val(response.capacityCC != null ? response.capacityCC : 'N/A');
            })
        })
        // get manufacturers // load more script for get manufacturers enddddddd





        // load more script for brands

        $('.dropdown-header.brands').click(function(event) {
            $('.dropdown-content.brands_content').toggle();
            event.stopPropagation();
        })

        $(window).click(function() {
            $('.dropdown-content').hide();
        })

        $('.option.more').click(function(event) {
            var count = "{{ $brands_count }}";
            $.ajax({
                url: "{{ route('load_more_brand') }}",
                method: "GET",
                success: function(data) {
                    let view_html = "";
                    $.each(data.brands, function(key, value) {
                        $('.normal-option').append($('<div class="option" data-brand_id="' +
                            value.brandId + '">').html(value.brandName));
                    });

                    if (data.count >= count) {
                        $('.option.more').hide();
                    }


                }
            });
            event.stopPropagation();


        })
        /// load more script for brands  end

        // load more script for sub section by brands
        var section_id_check_array = [];
        $('.dropdown-header.product_group').click(function(event) {
            $('.dropdown-content.product_group_content').toggle();
            event.stopPropagation();
        })
        var brand_id_save = "";
        $(document.body).on('click', '.option:not(.more)', function(event) { // click on brand to get sections
            var brand_id = $(this).data('brand_id');
            brand_id_save = $(this).data('brand_id');
            $('.dropdown-header.brands').html($(this).html());

            section_id_check_array = [];
            var url = "{{ route('get_sub_sections_by_brand') }}";
            $.get(url + '?brand_id=' + brand_id, function(data) {


                let response = data;

                if (response.length <= 0) {
                    $('.product_group_normal_option').empty();
                    $('.dropdown-header.product_group').html("Select Product Group");
                    // $('.more.product_group_more').hide();
                } else {
                    // $('.more.product_group_more').hide();
                }
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {

                    section_id_check_array.push(value.assemblyGroupNodeId);
                    $('.product_group_normal_option').append($(
                        '<div class="product_group_option" data-section_id="' +
                        value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                    // $.each(value.sub_section, function(key_2, value_2) {


                    //         $('.product_group_normal_option').append($('<div class="option" data-section_id="' +
                    //         value_2.assemblyGroupNodeId + '">').html(value_2.assemblyGroupName));
                    // });
                });

            })
        })

        $('.more.product_group_more').click(function(event) {
            var brand_id = brand_id_save
            // $('.dropdown-header.brands').html($(this).html());


            var url = "{{ route('get_sub_sections_by_brand') }}";
            $.get(url + '?brand_id=' + brand_id, function(data) {


                let response = data;

                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {

                    if (!section_id_check_array.includes(value.assemblyGroupNodeId)) {
                        $('.product_group_normal_option').append($(
                            '<div class="product_group_option" data-section_id="' +
                            value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                        section_id_check_array.push(value.assemblyGroupNodeId);
                    }

                    // $.each(value.sub_section, function(key_2, value_2) {


                    //         $('.product_group_normal_option').append($('<div class="option" data-section_id="' +
                    //         value_2.assemblyGroupNodeId + '">').html(value_2.assemblyGroupName));
                    // });
                });

            });
            event.stopPropagation();
        })

        $(document.body).on('click', '.product_group_option:not(.product_group_more)', function(
            event) { // click on brand to get sections
            var section_id = $(this).data('section_id');
            $('#sub_section_id').val(section_id);
            $('.dropdown-header.product_group').html($(this).html());
            event.stopPropagation();
        })

        // load more script for sub section by brands end =========================
    </script>
@endpush
