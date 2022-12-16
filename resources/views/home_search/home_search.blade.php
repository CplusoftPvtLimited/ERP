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
        <input type="hidden" id="app_url" value="{{ env('APP_URL') }}">
        <input type="hidden" id="brand_count" value="{{ $brands_count }}">
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
                                                     <span>{{ __('Select Manufacturer') }} <i id="manufacturer_caret" class="fa fa-solid fa-caret-down"></i></span>

                                                </div>

                                                <div class="dropdown-content manufacturer_content form-control">
                                                    <input type="text" placeholder="" id="manufacturer_input_search"
                                                        onkeyup="filterManufacturer()">
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
                                                    {{ __('Select Model') }} <i id="model_caret" class="fa fa-solid fa-caret-down"></i></div>
                                                <div class="dropdown-content model_content form-control">
                                                    <input type="text" placeholder="" id="model_input_search"
                                                        onkeyup="filterModel()">
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
                                            <input type="hidden" id="engine_id" name="engine_id">
                                            <div class="dropdown">
                                                <div class="dropdown-header engine form-control">
                                                    {{ __('Select Engine') }} <i id="engine_caret" class="fa fa-solid fa-caret-down"></i></div>
                                                <div class="dropdown-content engine_content form-control">
                                                    <input type="text" placeholder="" id="engine_input_search"
                                                        onkeyup="filterEngine()">
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
                                            
                                            <input type="text" id="model_year" name="model_year" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fuel">{{ __('Fuel') }}</label>
                                            
                                            <input type="text" id="fuel" name="fuel" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cc">{{ __('CC') }}</label>
                                            
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
                        <div class="card-header article_view_tr_head">
                            <h3>Search By Brand</h3>
                        </div>
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
                                                    <input type="text" placeholder="" id="brand_input_search"
                                                        onkeyup="filterBrand()">
                                                    <div class="normal-option">
                                                        @foreach ($brands as $brand)
                                                            <div class="option" data-brand_id="{{ $brand->brandId }}">
                                                                {{ $brand->brandName }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if ($brands_count > 10)
                                                        <div class="option more" id="barnd_more"><span>Load More
                                                                &nbsp;&nbsp;<span> <span style="display:none;"
                                                                        id="brand_load_icon" class="loader4"></span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            {{-- <div class="ui-widget">
                                                <input id="automplete-1" class="form-control">
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sub_section_id">{{ __('Select Product Group') }} <span
                                                    style="color: red;">*</span></label>
                                            
                                            <div class="dropdown">
                                                <div class="dropdown-header product_group form-control">
                                                    {{ __('Select Product Group') }}</div>
                                                <div class="dropdown-content product_group_content form-control">
                                                    <input type="text" placeholder="" id="section_input_search"
                                                        onkeyup="filterSection()">
                                                    <div class="product_group_normal_option">

                                                    </div>
                                                    <div class="more product_group_more" id="section_more"><span>Load More
                                                        &nbsp;&nbsp;<span> <span style="display:none;"
                                                                id="section_load_icon" class="loader4"></span></div>
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
            <div class="row">
                
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-header article_view_tr_head">
                            <h3>VIN Search</h3>
                        </div>
                        <div class="card-body">
                            
                            <form action="{{ route('search_sections_by_engine') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 mt-3">
                                            <label for="">Plate Number</label>
                                            <input id="plate_number" class="form-control" placeholder="156-TU-2999">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-info search-btn"
                                                id="search-btn">{{ trans('file.Search') }} <span style="display:none;"
                                                    id="plate_load_icon" class="loader4"></span></button>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" name="type" id="plate_engine_type" readonly>
                                    <input type="hidden" class="form-control" name="sub_type" id="plate_engine_sub_type" readonly>
                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Model</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="model_name" id="model_name" readonly
                                                    aria-describedby="basic-addon2">
                                                {{-- <span class="input-group-text" id="basic-addon2"
                                                    style="    background-color: #6244A6;
                                                color: white;"><button
                                                        type="button" id="get_engines"
                                                        style="border: none;background:transparent;color:white">Get
                                                        Engines</button></span> --}}
                                            </div>
                                            <input type="hidden" class="form-control" name="model_id" id="model_id" readonly>
                                            <input type="hidden" class="form-control" name="manufacturer_id" id="manufacturer_id"
                                                readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="engine_id">{{ __('Select Engine') }}</label>
                                                <input type="hidden" id="engine_id" name="engine_id">
                                                <div class="dropdown">
                                                    <div class="dropdown-header plate_engine form-control">
                                                        {{ __('Select Engine') }}</div>
                                                    <div class="dropdown-content plate_engine_content form-control">
                                                        <input type="text" placeholder="" id="plate_engine_input_search"
                                                            onkeyup="filterPlateEngine()">
                                                        <div class="plate_engine_normal_option">
                
                                                        </div>
                                                        <div class="more plate_engine_more" id="plate_engine_more"> <span>Load More
                                                                &nbsp;&nbsp;<span> <span style="display:none;"
                                                                        id="plate_engine_load_icon" class="loader4"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="model_year">{{ __('Model Year') }}</label>
                                                
                                                <input type="text" id="plate_model_year" name="model_year" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fuel">{{ __('Fuel') }}</label>
                                                
                                                <input type="text" id="plate_fuel" name="fuel" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cc">{{ __('CC') }}</label>
                                                
                                                <input type="text" id="plate_cc" name="cc" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit"><i
                                                    class="fa fa-solid fa-magnifying-glass"></i> Search</button>
                                            </div>
                                        </div>
                            
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <form action="{{ route('exportData') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <button type="submit">Export</button>
        </form>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="{{ asset('js/home_manufacturer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_model.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_engine.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_brand_section.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_vin.js') }}"></script>

    <script>
        $(document).ready(function() {


            if ($(window).outerWidth() > 1199) {
                $('nav.side-navbar').toggleClass('shrink');
                $('.page').toggleClass('active');

            }
            // });
        })

        $(function() {
    var name = $('#automplete-1').val();
    $.ajax({
        method: "GET",
        url: "/get_home_brand_auto_complete",
        data: {
            name: name
        },

        success: function(data) {

            let response = data.data;

            var html = "";
            var brands = [];
            $.each(response, function(key, value) {
                if (value != null) {
                    brands.push(value.brandName)
                }

            });

            $("#automplete-1").autocomplete({
                source: brands
            });



        },
        error: function(error) {
            console.log(error);
        }
    });
    
});
    </script>
@endpush
