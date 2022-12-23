@extends('layout.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="{{ asset('/css/dropdown.css') }}">

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Engine') }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('engine.index') }}" class="btn btn-primary float-right">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('error'))
                                <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('engine.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Capacity (cc)</h6>
                                                    <input type="number" min="0" name="capacityCC"
                                                        class="form-control" required value="{{ old('capacityCC') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Capacity (liters)</h6>
                                                    <input type="number" min="0" name="capacityLiters"
                                                        class="form-control" required value="{{ old('capacityLiters') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Code</h6>
                                                    <input type="text" name="code" class="form-control" required
                                                        value="{{ old('code') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Kilowatt From</h6>
                                                    <input type="number" min="0" name="kiloWattsFrom"
                                                        class="form-control" required value="{{ old('kiloWattsFrom') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Kilowatt To</h6>
                                                    <input type="number" min="0" name="kiloWattsTo"
                                                        class="form-control" required value="{{ old('kiloWattsTo') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Horsepower To</h6>
                                                    <input type="number" min="0" name="horsePowerTo"
                                                        class="form-control" required value="{{ old('horsePowerTo') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Horsepower From</h6>
                                                    <input type="number" min="0" name="horsePowerFrom"
                                                        class="form-control" required value="{{ old('horsePowerFrom') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Engine Type</h6>
                                                    <input type="text" name="engineType" class="form-control" required
                                                        value="{{ old('engineType') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturer</h6>
                                                    {{-- <select name="mfrId" id="" class="selectpicker form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">{{ $manufacturer->manuName }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="hidden" name="mfrId" id="manufacturer_id">
                                                    <div class="dropdown">
                                                        <div class="dropdown-header manufacturer form-control">
                                                            {{ __('Select Manufacturer') }}
                                                        </div>
                                                        <div class="dropdown-content manufacturer_content">
                                                            <input type="text" class="search_input"
                                                                placeholder="search more manufacturers"
                                                                id="manufacturer_input_search"
                                                                onkeyup="filterPurchaseManufacturer()">
                                                            <span style="display: none;"
                                                                id="manufacturer_searching">Searching <span
                                                                    class="loading"></span></span>
                                                            <div class="normal-option">

                                                                @foreach ($manufacturers as $manufacturer)
                                                                    <div class="option"
                                                                        data-manufacturer_id="{{ $manufacturer->manuId }}">
                                                                        {{ $manufacturer->manuName }}</div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Model</h6>
                                                    {{-- <select name="vehicleModelSeriesId" id="" class="selectpicker form-control">
                                                        @foreach ($models as $model)
                                                            <option value="{{ $model->modelId }}">{{ $model->modelname }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="hidden" name="vehicleModelSeriesId" id="model_id">
                                                    <div class="dropdown">
                                                        <div class="dropdown-header model form-control">
                                                            {{ __('Select Model') }}
                                                        </div>
                                                        <div class="dropdown-content model_content">
                                                            <input type="text" class="search_input"
                                                                placeholder="search more models" id="model_input_search"
                                                                onkeyup="filterModel()">
                                                            <span style="display: none;" id="model_searching">Searching
                                                                <span class="loading"></span></span>
                                                            <div class="model_normal_option">

                                                                @foreach ($models as $model)
                                                                    <div class="model_option"
                                                                        data-model_id="{{ $model->modelId }}">
                                                                        {{ $model->modelname }}</div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Linkage Target Type</h6>
                                                    <select name="linkageTargetType" id="linkageTarget"
                                                        class="selectpicker form-control">

                                                        <option>Select Type</option>
                                                        <option value="P">Passenger + Motorcycle + LCV</option>
                                                        <option value="O">Commercial Vehicle + Tractor</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Sub-Linkage Target Type</h6>
                                                    <select name="subLinkageTargetType" id="subLinkageTarget"
                                                        class="selectpicker form-control">
                                                        <option value="-2">Select One</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <h6>Engine Description</h6>
                                                    <textarea name="description" id="" class="form-control" required cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="mfrShortName" value="0">
                                        <input type="hidden" name="beginYearMonth" value="0000">
                                        <input type="hidden" name="endYearMonth" value="0000">
                                        <input type="hidden" name="rmiTypeId" value="0">
                                        <input type="hidden" name="imageURL50" value="0">
                                        <input type="hidden" name="imageURL100" value="0">
                                        <input type="hidden" name="imageURL200" value="0">
                                        <input type="hidden" name="imageURL400" value="0">
                                        <input type="hidden" name="imageURL800" value="0">
                                        {{-- <input type="hidden" name="'0'" value="0"> --}}
                                        <input type="hidden" name="fuelMixtureFormationTypeKey" value="0">
                                        <input type="hidden" name="fuelMixtureFormationType" value="0">
                                        <input type="hidden" name="driveTypeKey" value="0">
                                        <input type="hidden" name="driveType" value="0">
                                        <input type="hidden" name="bodyStyleKey" value="0">
                                        <input type="hidden" name="bodyStyle" value="0">
                                        <input type="hidden" name="fuelTypeKey" value="0">
                                        <input type="hidden" name="fuelType" value="0">
                                        <input type="hidden" name="engineTypeKey" value="0">
                                        <input type="hidden" name="cylinders" value="0">
                                        <input type="hidden" name="axleStyleKey" value="0">
                                        <input type="hidden" name="axleStyle" value="0">
                                        <input type="hidden" name="axleTypeKey" value="0">
                                        <input type="hidden" name="axleType" value="0">
                                        <input type="hidden" name="axleBodyKey" value="0">
                                        <input type="hidden" name="axleBody" value="0">
                                        <input type="hidden" name="wheelMountingKey" value="0">
                                        <input type="hidden" name="wheelMounting" value="0">
                                        <input type="hidden" name="axleLoadToKg" value="0">
                                        <input type="hidden" name="brakeTypeKey" value="0">
                                        <input type="hidden" name="brakeType" value="0">
                                        <input type="hidden" name="hmdMfrModelId" value="0">
                                        <input type="hidden" name="hmdMfrModelName" value="0">
                                        <input type="hidden" name="aspirationKey" value="0">
                                        <input type="hidden" name="aspiration" value="0">
                                        <input type="hidden" name="cylinderDesignKey" value="0">
                                        <input type="hidden" name="cylinderDesign" value="0">
                                        <input type="hidden" name="coolingTypeKey" value="0">
                                        <input type="hidden" name="coolingType" value="0">
                                        <input type="hidden" name="tonnage" value="0">
                                        <input type="hidden" name="axleConfigurationKey" value="0">
                                        <input type="hidden" name="axleConfiguration" value="0">
                                        <input type="hidden" name="axleLoadFromKg" value="0">
                                        <input type="hidden" name="lang" value="0">
                                        <input type="hidden" name="valves" value="0">
                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary"
                                                style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <script>
            var currentRequest = null;
            $('#linkageTarget').on('change', function() {
                var val = this.value;

                if (val == "P") {
                    $('#subLinkageTarget').empty();
                    $('#subLinkageTarget').append(`
                        <option value="V">Passenger Car</option>
                        <option value="L">LCV</option>
                        <option value="B">Motorcycle</option>`);
                    $('.selectpicker').selectpicker('refresh');
                } else if (val == "O") {
                    $('#subLinkageTarget').empty();
                    $('#subLinkageTarget').append(`
                        <option value="C">Commercial Vehicle</option>
                        <option value="T">Tractor</option>
                        <option value="M">Engine</option>
                        <option value="A">Axle</option>
                        <option value="K">CV Body Type</option>`);
                    $('.selectpicker').selectpicker('refresh');
                } else {
                    $('#subLinkageTarget').empty();
                    $('.selectpicker').selectpicker('refresh');
                }

            });

            // ---------- For Manufacturer ---------------------
            $('.dropdown-header.manufacturer').click(function(event) {
                $('.dropdown-content.manufacturer_content').toggle();
                event.stopPropagation();
            })

            function filterPurchaseManufacturer() {
                var input, filter, ul, li, a, i;
                input = document.getElementById("manufacturer_input_search").value;
                document.getElementById('manufacturer_searching').style.display = "block";
                $('.normal-option').empty();



                if (currentRequest != null) {
                    currentRequest.abort();
                }

                currentRequest = $.ajax({
                    url: '/get_all_manufacturers_by_autocomplete',
                    method: "GET",
                    data: {
                        name: input,
                    },
                    success: function(data) {

                        if (data.autocomplete == 1) {
                            $('.normal-option').empty();
                            document.getElementById('manufacturer_searching').style.display = "none";
                            if (data.manufacturers.length > 0) {
                                $.each(data.manufacturers, function(key, value) {
                                    $('.normal-option').append($(
                                        '<div class="option" id="manu_id" data-manufacturer_id="' +
                                        value.manuId + '">').html(value.manuName));
                                });
                            } else {
                                $('.normal-option').append(
                                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                                );
                            }

                        } else if (data.autocomplete == 0) {
                            $('.normal-option').empty();
                            document.getElementById('manufacturer_searching').style.display = "none";
                            $.each(data.manufacturers, function(key, value) {
                                $('.normal-option').append($(
                                    '<div class="option" id="manu_id" data-manufacturer_id="' +
                                    value.manuId + '">').html(value.manuName));
                            });
                        }





                    }
                });

            }
            $(document.body).on('click', '.option:not(.manufacturer_more)', function(
                event) {
                var manufacturer_id = $(this).data('manufacturer_id');
                $('.dropdown-header.manufacturer').html($(this).html());
                $('.dropdown-content.manufacturer_content').toggle();
                document.getElementById('manufacturer_id').value = manufacturer_id;
            });



            // ---------- For model ---------------------
            $('.dropdown-header.model').click(function(event) {
                $('.dropdown-content.model_content').toggle();
                event.stopPropagation();
            })

            function filterModel() {
                var input, filter, ul, li, a, i;
                input = document.getElementById("model_input_search").value;
                document.getElementById('model_searching').style.display = "block";
                $('.model_normal_option').empty();



                if (currentRequest != null) {
                    currentRequest.abort();
                }

                currentRequest = $.ajax({
                    url: '/get_all_models_by_autocomplete',
                    method: "GET",
                    data: {
                        name: input,
                    },
                    success: function(data) {

                        if (data.autocomplete == 1) {
                            $('.model_normal_option').empty();
                            document.getElementById('model_searching').style.display = "none";
                            if (data.models.length > 0) {
                                $.each(data.models, function(key, value) {
                                    $('.model_normal_option').append($(
                                        '<div class="model_option" data-model_id="' +
                                        value.modelId + '">').html(value.modelname));
                                });
                            } else {
                                $('.model_normal_option').append(
                                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                                );
                            }

                        } else if (data.autocomplete == 0) {
                            $('.model_normal_option').empty();
                            document.getElementById('model_searching').style.display = "none";
                            $.each(data.manufacturers, function(key, value) {
                                $('.model_normal_option').append($(
                                    '<div class="model_option" data-model_id="' +
                                    value.modelId + '">').html(value.modelname));
                            });
                        }





                    }
                });

            }
            $(document.body).on('click', '.model_option:not(.model_more)', function(
                event) {
                var model_id = $(this).data('model_id');
                $('.dropdown-header.model').html($(this).html());
                $('.dropdown-content.model_content').toggle();
                document.getElementById('model_id').value = model_id;
            });
        </script>
    </section>
@endsection
