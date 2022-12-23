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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css">

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Model') }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('modelseries.index') }}" class="btn btn-primary float-right">Back</a>
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
                            <form action="{{ route('modelseries.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Model Name:</h6>
                                                    <input type="text" name="tags" placeholder="" class="form-control"
                                                        data-role="tagsinput" />
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Year of Construction From:</h6>
                                                    <select class="selectpicker form-control" name="yearOfConstrFrom"
                                                        id="fromYear">
                                                        <option value="-2">Select Year</option>
                                                        @foreach (range($latest_year, $earliest_year) as $i)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Year of Construction To:</h6>
                                                    <select class="selectpicker form-control" name="yearOfConstrTo"
                                                        id="toYear">
                                                        <option value="-2">Select Year</option>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturer:</h6>
                                                    {{-- <select name="manuId" id="" class="form-control" required>
                                                        <option value="" selected disabled>--Select One--</option>
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
                                                                {{ $manufacturer->manuName }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="hidden" name="manuId" id="manufacturer_id">
                                                    <div class="dropdown">
                                                        <div class="dropdown-header manufacturer form-control">
                                                            {{ __('Select Manufacturer') }}
                                                        </div>
                                                        <div class="dropdown-content manufacturer_content">
                                                            <input type="text" class="search_input"
                                                                placeholder="search more manufacturer"
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
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Engine Type</h6>
                                                    <select name="" id="linkageTarget"
                                                        class="selectpicker form-control">

                                                        <option>Select Type</option>
                                                        <option value="P">Passenger + Motorcycle + LCV</option>
                                                        <option value="O">Commercial Vehicle + Tractor</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Engine Sub-Type</h6>
                                                    <select name="linkingTargetType" id="subLinkageTarget"
                                                        class="selectpicker form-control">
                                                        <option value="-2">Select One</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
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
            $('#fromYear').on('change', function() {
                var year = this.value;

                // var startYear = 
                var currentYear = new Date().getFullYear(),
                    years = [];
                year = year || 1980;
                $('#toYear').empty();
                while (year <= currentYear) {
                    //console.log(year)
                    $('#toYear').append(`
                        
                        <option value="` + year + `">` + year + `</option>`);
                    year++;

                }
                $('.selectpicker').selectpicker('refresh');

                //                 startYear = startYear || 1980;  
                // while ( startYear <= currentYear ) {
                //     years.push(startYear++);
                // }   


            });
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
        </script>
    </section>
@endsection
