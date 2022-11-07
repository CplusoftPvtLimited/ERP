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
                                                    {{ __('Select Manufacturer') }}
                                                    
                                                </div>
                                                
                                                <div class="dropdown-content manufacturer_content form-control">
                                                    {{-- <div class="input-main-class">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id=""><i
                                                                        class="fa fa-duotone fa-magnifying-glass"></i></span>
                                                            </div> --}}
                                                            <input type="text" placeholder="  Search Manufacturer"
                                                                id="manufacturer_input_search" onkeyup="filterManufacturer()">
                                                        {{-- </div> --}}
                                                    {{-- </div> --}}
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
                                                        <div class="option more"><span>Load More
                                                                &nbsp;&nbsp;<span> <span style="display:none;"
                                                                        id="brand_load_icon" class="loader4"></span></div>
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
    <script type="text/javascript" src="{{ asset('js/home_manufacturer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_model.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_engine.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home_brand_section.js') }}"></script>

    <script>
        $(document).ready(function() {


            if ($(window).outerWidth() > 1199) {
                $('nav.side-navbar').toggleClass('shrink');
                $('.page').toggleClass('active');

            }
            // });
        })

  

            </script>
@endpush
