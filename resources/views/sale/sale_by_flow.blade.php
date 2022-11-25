<div class="row">
    <div class="col-md-12">
        <div id="other_data"></div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engine Type</label>
                    <select name="linkageTargetType" id="linkageTarget" class="selectpicker form-control">
                        <option>Select Type</option>
                        <option value="P">Passenger</option>
                        <option value="O">Commercial Vehicle and Tractor</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engine Sub-Type</label>
                    <select name="subLinkageTargetType" data-href="{{ route('get_manufacturers_by_engine_type') }}"
                        id="subLinkageTarget" class="selectpicker form-control">
                        <option value="-2">Select One</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Manufacturers</label>
                    <input type="hidden" name="manufacturer_id" id="manufacturer_id">
                    <div class="dropdown">
                        <div class="dropdown-header manufacturer form-control">{{ __('Select Manufacturer') }}
                        </div>
                        <div class="dropdown-content manufacturer_content form-control">
                            <input type="text" placeholder="" id="manufacturer_input_search"
                                onkeyup="filterPurchaseManufacturer()">
                            <div class="normal-option">

                            </div>
                            <div class="more manufacturer_more" style="display: none;" id="manufacturer_more"><span>Load
                                    More
                                    &nbsp;&nbsp;<span> <span style="display:none;" id="manufacturer_load_icon"
                                            class="loader4"></span></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="model_id">Select Model</label>
                    <input type="hidden" id="model_id" name="model_id">
                    <div class="dropdown">
                        <div class="dropdown-header model form-control">
                            {{ __('Select Model') }}</div>
                        <div class="dropdown-content model_content form-control">
                            <input type="text" placeholder="" id="model_input_search" onkeyup="filterModel()">
                            <div class="model_normal_option">

                            </div>
                            <div class="more model_more" id="model_more"> <span>Load More
                                    &nbsp;&nbsp;<span> <span style="display:none;" id="model_load_icon"
                                            class="loader4"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="engine_id">Select Engine</label>
                    <input type="hidden" id="engine_id" name="engine_id">
                    <div class="dropdown">
                        <div class="dropdown-header engine form-control">
                            {{ __('Select Engine') }}</div>
                        <div class="dropdown-content engine_content form-control">
                            <input type="text" placeholder="" id="engine_input_search" onkeyup="filterEngine()">
                            <div class="engine_normal_option">

                            </div>
                            <div class="more engine_more" id="engine_more"> <span>Load More
                                    &nbsp;&nbsp;<span> <span style="display:none;" id="engine_load_icon"
                                            class="loader4"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="section_id">Select Section</label>
                    {{-- <select name="section_id" id="section_id" data-href="{{ route('get_section_parts_for_sale') }}"
                        class="form-control" required>
                    </select> --}}
                    <input type="hidden" id="section_id" name="section_id">
                    <div class="dropdown">
                        <div class="dropdown-header section form-control">
                            {{ __('Select Section') }}</div>
                        <div class="dropdown-content section_content form-control">
                            <input type="text" placeholder="" id="section_input_search" onkeyup="filterSection()">
                            <div class="section_normal_option">

                            </div>
                            <div class="more section_more" id="section_more"> <span>Load More
                                    &nbsp;&nbsp;<span> <span style="display:none;" id="section_load_icon"
                                            class="loader4"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="section_part_id">Select Section Part</label>
                    {{-- <select name="section_part_id" id="section_part_id" data-href="{{ route('check_product_stock') }}"
                        class="form-control" required>
                    </select> --}}
                    <input type="hidden" id="section_part_id" name="section_part_id">
                    <div class="dropdown">
                        <div class="dropdown-header section_part form-control">
                            {{ __('Select Section Part') }}</div>
                        <div class="dropdown-content section_part form-control">
                            <input type="text" placeholder="" id="section_part_input_search"
                                onkeyup="filterSectionPart()">
                            <div class="section_part_normal_option">

                            </div>
                            <div class="more section_part_more" id="section_part_more"> <span>Load More
                                    &nbsp;&nbsp;<span> <span style="display:none;" id="section_part_load_icon"
                                            class="loader4"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn" id="save-btn">Save</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
    function filterPurchaseManufacturer() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("manufacturer_input_search");
        filter = input.value.toUpperCase();
        if (input.value) {
            document.getElementById('manufacturer_more').style.display = "none";
        } else {
            document.getElementById('manufacturer_more').style.display = "block";
        }
        div = document.getElementsByClassName("normal-option");
        a = document.getElementsByClassName("option");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

    }

    function filterModel() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("model_input_search");
        filter = input.value.toUpperCase();
        if (input.value) {
            document.getElementById('model_more').style.display = "none";
        } else {
            document.getElementById('model_more').style.display = "block";
        }
        div = document.getElementsByClassName("model_normal_option");
        a = document.getElementsByClassName("model_option");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

    }

    function filterEngine() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("engine_input_search");
        filter = input.value.toUpperCase();
        if (input.value) {
            document.getElementById('engine_more').style.display = "none";
        } else {
            document.getElementById('engine_more').style.display = "block";
        }
        div = document.getElementsByClassName("engine_normal_option");
        a = document.getElementsByClassName("engine_option");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

    }

    function filterSection() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("section_input_search");
        filter = input.value.toUpperCase();
        if (input.value) {
            document.getElementById('section_more').style.display = "none";
        } else {
            document.getElementById('section_more').style.display = "block";
        }
        div = document.getElementsByClassName("section_normal_option");
        a = document.getElementsByClassName("section_option");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

    }

    function filterSectionPart() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("section_part_input_search");
        filter = input.value.toUpperCase();
        if (input.value) {
            document.getElementById('section_part_more').style.display = "none";
        } else {
            document.getElementById('section_part_more').style.display = "block";
        }
        div = document.getElementsByClassName("section_part_normal_option");
        a = document.getElementsByClassName("section_part_option");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

    }

    // ALL Filter fucntion end =================== 
    $('#linkageTarget').on('change', function() {
        var val = this.value;

        if (val == "P") {
            $('#subLinkageTarget').empty();
            $('#subLinkageTarget').append(`<option value="V">
                        Passenger Car
                          </option><option value="L">
                               LCV
                          </option><option value="B">
                                Motorcycle
                          </option>`);
            $('.selectpicker').selectpicker('refresh');
        } else if (val == "O") {
            $('#subLinkageTarget').empty();
            $('#subLinkageTarget').append(`<option value="C">
                    Commercial Vehicle
                          </option><option value="T">
                               Tractor
                          </option><option value="M">
                               Engine
                          </option><option value="A">
                               Axle
                          </option><option value="K">
                            CV Body Type
                          </option>`);
            $('.selectpicker').selectpicker('refresh');
        } else {
            $('#subLinkageTarget').empty();
            $('.selectpicker').selectpicker('refresh');
        }
        $('#manufacturer_id').html('<option value="">Select One</option>');
        $('#manufacturer_id').selectpicker("refresh");
        $('#section_id').html('<option value="">Select One</option>');
        $('#section_id').selectpicker("refresh");
        $('#section_part_id').html('<option value="">Select One</option>');
        $('#section_part_id').selectpicker("refresh");
        $('#engine_id').html('<option value="">Select One</option>');
        $('#engine_id').selectpicker("refresh");


    });

    // new code for dropdowns of sales

    // get manufacturers dropdown code
    var manufacturer_id_check_array = [];
    $('.dropdown-header.manufacturer').click(function(event) {
        $('.dropdown-content.manufacturer_content').toggle();
        event.stopPropagation();
    })
    $(document).on('change', '#subLinkageTarget', function() {
        let engine_sub_type = $(this).val();
        let url = $(this).attr('data-href');
        $.get(url + '?engine_sub_type=' + engine_sub_type, function(data) {

            let response = data.data;
            if (data.manu_more_value > data.total_count) {
                document.getElementById('manufacturer_more').style.display = "none";
            } else {
                document.getElementById('manufacturer_more').style.display = "block";
            }
            console.log(data.total_count, data.manu_more_value);
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    manufacturer_id_check_array.push(value.manuId);
                    $('.normal-option').append($(
                        '<div class="option" id="manu_id" data-manufacturer_id="' +
                        value.manuId + '">').html(value.manuName));
                });
            } else {
                console.log(data)
                $('.normal-option').append(
                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                );
            }


        })
    });
    $('.more.manufacturer_more').click(function(event) {
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        let url = "{{ route('get_manufacturers_by_engine_type') }}";
        document.getElementById('manufacturer_load_icon').style.display = "block";
        $.get(url + '?engine_sub_type=' + engine_sub_type, function(data) {

            let response = data.data;
            document.getElementById('manufacturer_load_icon').style.display = "none";

            if (data.manu_more_value > data.total_count) {
                document.getElementById('manufacturer_more').style.display = "none";
            } else {
                document.getElementById('manufacturer_more').style.display = "block";
            }
            console.log(data.total_count, data.manu_more_value);
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    manufacturer_id_check_array.push(value.manuId);
                    $('.normal-option').append($(
                        '<div class="option" id="manu_id" data-manufacturer_id="' +
                        value.manuId + '">').html(value.manuName));
                });
            } else {
                console.log(data)
                $('.normal-option').append(
                    "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                );
            }


        })
    });


    //get models==================     --------------------------------
    var manufacturer_id_set = 0;
    $(document.body).on('click', '.option:not(.manufacturer_more)', function(
        event) { // click on brand to get sections
        model_id_check_array = [];
        $('.dropdown-header.model').html("Select Model");
        $('.dropdown-header.engine').html("Select Engine");
        $('.model_normal_option').empty();
        $('.engine_normal_option').empty();
        // document.getElementById('engine_more').style.display = "none";
        $('.dropdown-content.manufacturer_content').toggle();
        var manufacturer_id = $(this).data('manufacturer_id');
        document.getElementById('manufacturer_id').value = manufacturer_id;
        manufacturer_id_set = manufacturer_id;

        let engine_sub_type = $('#subLinkageTarget :selected').val();
        let url = "{{ route('get_models_by_manufacturer') }}";
        $('.dropdown-header.manufacturer').html($(this).html());
        $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type + "&main=1",
            function(
                data) {

                let response = data.data;
                if (data.load_more_model_value > data.total_count) {
                    document.getElementById('model_more').style.display = "none";
                } else {
                    document.getElementById('model_more').style.display = "block";
                }

                if (response.length > 0) {
                    $.each(response, function(key, value) {

                        model_id_check_array.push(value.modelId);
                        $('.model_normal_option').append($(
                            '<div class="model_option" data-model_id="' +
                            value.modelId + '">').html(value.modelname));
                    });
                } else {
                    $('.model_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px;'>No Record Found</span>"
                    );
                }

            })
    })

    $('.dropdown-header.model').click(function(event) {
        $('.dropdown-content.model_content').toggle();
        event.stopPropagation();
    })

    $('.more.model_more').click(function(event) {
        document.getElementById('model_load_icon').style.display = "block";
        var manufacturer_id = manufacturer_id_set;
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        let url = "{{ route('get_models_by_manufacturer') }}";

        $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type + '&load=1',
            function(data) {
                document.getElementById('model_load_icon').style.display = "none";
                let response = data.data;
                if (data.load_more_model_value > data.total_count) {
                    document.getElementById('model_more').style.display = "none";
                } else {
                    document.getElementById('model_more').style.display = "block";
                }

                if (response.length > 0) {
                    $.each(response, function(key, value) {

                        model_id_check_array.push(value.modelId);
                        $('.model_normal_option').append($(
                            '<div class="model_option" data-model_id="' +
                            value.modelId + '">').html(value.modelname));
                    });
                } else {
                    $('.model_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px;'>No Record Found</span>"
                    );
                }
            })
        event.stopPropagation();
    })
    ////// get engines==================
    var engine_id_check_array = [];
    $('.dropdown-header.engine').click(function(event) {
        $('.dropdown-content.engine_content').toggle();
        event.stopPropagation();
    })
    $(document.body).on('click', '.model_option:not(.model_more)', function(
        event) { // click on brand to get sections
        $('.dropdown-header.model').html($(this).html());

        var model_id = $(this).data('model_id');
        document.getElementById('model_id').value = model_id;
        model_id_set = model_id;
        let url = "{{ route('get_engines_by_model') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        $('.dropdown-content.model_content').toggle();
        $('.engine_normal_option').empty();
        $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + "&main=1",
            function(data) {
                let response = data.data;

                document.getElementById('engine_load_icon').style.display = "none";
                if (data.load_more_engine_value > data.total_count) {
                    document.getElementById('engine_more').style.display = "none";
                } else {
                    document.getElementById('engine_more').style.display = "block";
                }
                if (response.length > 0) {
                    $.each(response, function(key, value) {
                        engine_id_check_array.push(value.linkageTargetId);
                        $('.engine_normal_option').append($(
                            '<div class="engine_option" data-engine_id="' +
                            value.linkageTargetId + '">').html(value.description + "(" +
                            value
                            .beginYearMonth + " - " + value.endYearMonth));
                    });
                } else {
                    $('.engine_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                    );
                }

            })

    })
    $('.more.engine_more').click(function(event) {
        document.getElementById('engine_load_icon').style.display = "block";
        var model_id = model_id_set;
        let url = "{{ route('get_engines_by_model') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();

        $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type + "&load=1",
            function(data) {
                let response = data.data;

                document.getElementById('engine_load_icon').style.display = "none";
                if (data.load_more_engine_value > data.total_count) {
                    document.getElementById('engine_more').style.display = "none";
                } else {
                    document.getElementById('engine_more').style.display = "block";
                }
                $.each(response, function(key, value) {
                    if (!engine_id_check_array.includes(value.linkageTargetId)) {
                        engine_id_check_array.push(value.linkageTargetId)
                        $('.engine_normal_option').append($(
                            '<div class="engine_option" data-engine_id="' +
                            value.linkageTargetId + '">').html(value.description + "(" +
                            value
                            .beginYearMonth + " - " + value.endYearMonth));
                    }

                });


            })
    });


    ///// get sections==================
    var engine_id_set = 0;
    $('.dropdown-header.section').click(function(event) {
        $('.dropdown-content.section_content').toggle();
        event.stopPropagation();
    })
    $(document.body).on('click', '.engine_option:not(.engine_more)', function(
        event) { // click on brand to get sections
        $('.dropdown-header.engine').html($(this).html());

        var engine_id = $(this).data('engine_id');
        document.getElementById('engine_id').value = engine_id;
        engine_id_set = engine_id;
        let url = "{{ route('get_sections_by_engine') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        $('.dropdown-content.engine_content').toggle();
        $('.section_normal_option').empty();
        $.get(url + '?engine_id=' + engine_id + '&engine_sub_type=' + engine_sub_type + "&main=1",
            function(data) {
                let response = data.data;

                document.getElementById('section_load_icon').style.display = "none";
                if (data.load_more_section_value > data.total_count) {
                    document.getElementById('section_more').style.display = "none";
                } else {
                    document.getElementById('section_more').style.display = "block";
                }
                if (response.length > 0) {
                    $.each(response, function(key, value) {
                        // engine_id_check_array.push(value.linkageTargetId);
                        $('.section_normal_option').append($(
                            '<div class="section_option" data-section_id="' +
                            value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                    });
                } else {
                    $('.section_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                    );
                }

            })

    })

    $('.more.section_more').click(function(event) { // click on brand to get sections

        var engine_id = engine_id_set;
        document.getElementById('section_load_icon').style.display = "block";
        let url = "{{ route('get_sections_by_engine') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        $('.dropdown-content.engine_content').toggle();
        $.get(url + '?engine_id=' + engine_id + '&engine_sub_type=' + engine_sub_type + "&load=1",
            function(data) {
                let response = data.data;

                document.getElementById('section_load_icon').style.display = "none";
                if (data.load_more_section_value > data.total_count) {
                    document.getElementById('section_more').style.display = "none";
                } else {
                    document.getElementById('section_more').style.display = "block";
                }
                $.each(response, function(key, value) {
                    // engine_id_check_array.push(value.linkageTargetId);
                    $('.section_normal_option').append($(
                        '<div class="section_option" data-section_id="' +
                        value.assemblyGroupNodeId + '">').html(value.assemblyGroupName));
                });


            })

    })

    ///// get section parts============

    var section_id_set = 0;
    $('.dropdown-header.section_part').click(function(event) {
        $('.dropdown-content.section_part').toggle();
        event.stopPropagation();
    })

    $(document.body).on('click', '.section_option:not(.section_more)', function(
        event) { // click on brand to get sections
        $('.dropdown-header.section').html($(this).html());

        var section_id = $(this).data('section_id');
        document.getElementById('section_id').value = section_id;
        section_id_set = section_id;
        let url = "{{ route('get_section_parts_for_sale') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();

        $('.dropdown-content.section_content').toggle();
        // $('.engine_normal_option').empty();
        $.get(url + '?section_id=' + section_id + '&engine_sub_type=' + engine_sub_type + "&main=1",
            function(data) {
                let response = data.data;
                // new 
                document.getElementById('section_part_load_icon').style.display = "none";
                if (data.load_more_section_part_value > data.total_count) {
                    document.getElementById('section_part_more').style.display = "none";
                } else {
                    document.getElementById('section_part_more').style.display = "block";
                }
                if (data.message == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Your Stock is empty',

                    });
                    document.getElementById('section_part_more').style.display = "none";
                    exit();
                } else if (data.message == 1 && data.data.length <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You dont have any product against this section in your stock',

                    });
                    document.getElementById('section_part_more').style.display = "none";
                    exit();
                }

                //end
                
                $('.section_part_normal_option').empty();
                if (response.length > 0) {

                    $.each(response, function(key, value) {
                        // engine_id_check_array.push(value.linkageTargetId);
                        $('.section_part_normal_option').append($(
                            '<div class="section_part_option" data-section_part_id="' +
                            value.dataSupplierId + "-" + value.legacyArticleId + '">').html(
                            value.genericArticleDescription + "-" + value.articleNumber));
                    });
                } else {
                    $('.section_part_normal_option').append(
                        "<span style='color:red;text-align:center;font-size:13px'>No Record Found</span>"
                    );
                }

            })

    })

    $('.more.section_part_more').click(function(event) { 

        var section_id = section_id_set;
        document.getElementById('section_part_load_icon').style.display = "block";
        let url = "{{ route('get_section_parts') }}";
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        $.get(url + '?section_id=' + section_id + '&engine_sub_type=' + engine_sub_type + "&load=1",
            function(data) {
                let response = data.data;
                // new 
                document.getElementById('section_part_load_icon').style.display = "none";
                if (data.load_more_section_part_value > data.total_count) {
                    document.getElementById('section_part_more').style.display = "none";
                } else {
                    document.getElementById('section_part_more').style.display = "block";
                }
                if (data.message == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Your Stock is empty',

                    });
                    document.getElementById('section_part_more').style.display = "none";
                    exit();
                } else if (data.message == 1 && data.data.length <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You dont have any product against this section in your stock',

                    });
                    document.getElementById('section_part_more').style.display = "none";
                    exit();
                }

                //end
                

                    $.each(response, function(key, value) {
                        // engine_id_check_array.push(value.linkageTargetId);
                        $('.section_part_normal_option').append($(
                            '<div class="section_part_option" data-section_part_id="' +
                            value.dataSupplierId + "-" + value.legacyArticleId + '">').html(
                            value.genericArticleDescription + "-" + value.articleNumber));
                    });
            })

    })


    // check product stock
   
    $(document.body).on('click', '.section_part_option:not(.section_part_more)', function(
        event) { // click on brand to get sections
            var section_part_id = $(this).data('section_part_id');
            document.getElementById('section_part_id').value = section_part_id;
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            var cashType = $('#cash_type').find(":selected").val();
            var url = "{{ route('check_product_stock') }}";
            $('.dropdown-header.section_part').html($(this).html());
            $('.dropdown-content.section_part').toggle();
            $.get(url + '?section_part_id=' + section_part_id + '&engine_sub_type=' + engine_sub_type + '&cash_type=' +
            cashType,
            function(data) {
                if (data.message == "no_white_items") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Items are availble for this White Cash',

                    });
                    exit();
                    // $('#section_part_id').html('<option value="">Select One</option>');
                } else if (data.message == "no_black_items") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Items are availble for this Black Cash',

                    });
                    exit();
                    // $('#section_part_id').html('<option value="">Select One</option>');
                }

            })

    })
    



    $("#product_purchase_date").on('change', function() {
        var selectedDate = this.value;
        var currentDate = new Date();
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '-' + mm + '-' + yyyy;
        if (selectedDate > today) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select the current date! currently you are not be able to add the purchase on future date',
            });
            $('#product_purchase_date').val('');
            exit();

        }
    });

    //// global veriables that we were use in the save functionality ///
    var supplier_ids_array = [],
        article_ids_array = [],
        selected_cash_type = [],
        all_product_ids = [];
    // var article_ids_array = [];
    var total_quantity = $('#total-quantity');
    var total_amount = $('#total-amount');
    // var all_product_ids = [];
    $("#save-btn").click(function() {
        var id = $('#section_part_id').val();
        var engine_type = $('#linkageTarget').find(":selected").val();
        var engine_sub_type = $('#subLinkageTarget').find(":selected").val();
        var manufacturer_id = $('#manufacturer_id').val();
        var model_id = $('#model_id').val();
        var engine_id = $('#engine_id').val();
        var section_id = $('#section_id').val();
        var section_part_id = $('#section_part_id').val();
        // var status = $('#status').find(":selected").val();
        var date = $('#product_sale_date').val();
        var cashType = $('#cash_type').find(":selected").val();

        // new code
        

        checkIfExists(date, engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
            section_part_id, cashType);

        $.ajax({
            method: "GET",
            url: "{{ url('show_section_parts_in_table_for_sale') }}",
            data: {
                id: id,
                engine_type: engine_type,
                engine_sub_type: engine_sub_type,
                manufacturer_id: manufacturer_id,
                model_id: model_id,
                engine_id: engine_id,
                section_id: section_id,
                section_part_id: section_part_id,
                cash_type: cashType
            },

            success: function(data) {
                // alert(data);
                // $('#myTable').DataTable( {
                //     "processing": true,
                //     "searching" : true,
                // });
                $('#submit-button').css("display", "block");
                $('#order-table-header').text(`{{ trans('file.Order Table') }} *`);
                var tableBody = $("table tbody");
                var tableHead = $("table thead");
                var tableHeadRow = $("table thead tr");
                var other_data_div = $('#other_data');

                var total_calculations = $('#total_sale_calculations');
                $('#total_sale_calculations').css('display', 'block');
                var white_cash_head = "";
                var black_cash_head = "";
                var white_cash_calculations_head = "";
                white_cash_calculations_head += `
                       <div class="col-md-12">
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Total Exculding VAT (Before Discount)</h5>    
                                </div>
                                <div class="col-md-3">
                                   <div class="input-group mb-3">     
                                        <input type="number" name="sale_entire_total_exculding_vat" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_entire_total_exculding_vat" 
                                            class="form-control" min="0" step="any" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Discount <span style="font-size:10px;color:#98AFC7">(value)</span></h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="sale_discount" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_discount" 
                                            class="form-control" min="0" step="any" max="100000000" onkeyup="calculateFlowSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>VAT <span style="font-size:10px;color:#98AFC7">(value)</span></5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="entire_vat" value="1" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_entire_vat" 
                                            class="form-control" min="1" step="any" max="100000000" onkeyup="calculateFlowSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Tax Stamp</h5>    
                                </div> 
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="tax_stamp" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="sale_tax_stamp" 
                                            class="form-control" min="0" step="any" max="100000000" onkeyup="calculateFlowSaleTotal()">
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="row total-calculations"> 
                                <div class="col-md-4">
                                   <h5>Total To Be Paid</h5>    
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">     
                                        <input type="number" name="total_to_be_paid" value="0" class="form-control"
                                            aria-label="Amount (to the nearest dollar)" id="total_to_be_paid" 
                                            class="form-control" min="0" step="any" max="100000000" readonly>
                                        <span class="input-group-text"><b>TND</b></span>
                                    </div>
                                </div> 
                            </div>
                       </div>
                `;

                white_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }} <span>Optional</span></th>
                    
                    <th style="width:200px">{{ trans('file.VAT %') }}</th>
                    
                    <th>{{ trans('file.Total (With Discount) Excluding Vat') }} </th>
                    
                    <th>Action</th>
                </tr>`;
                // sale price => editable for white but non-editable for black
                black_cash_head += `<tr id="">
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Quantity') }}</th>
                    <th>{{ trans('file.Sale Price (Excluding VAT)') }}</th>
                    <th>{{ trans('file.Discount (%)') }}</th>
                    
                    <th>{{ trans('file.Total (Without Discount)') }}</th>
                    <th>{{ trans('file.Total (With Discount)') }}</th>
                    <th>Action</th>
                </tr>`;

                var length = document.getElementById("myTable").rows.length;

                var html = '';

                html += '<input type="hidden" name="article_number[]" value="' + data.data
                    .articleNumber + '">';
                calculateFlowSaleTotal(all_product_ids);


                if (selected_cash_type.length > 0) {
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

                } else {
                    selected_cash_type.push(cashType);

                }

                if (data.cash_type == "white" && tableHeadRow.length <= 0) {
                    tableHead.append(white_cash_head);
                    total_calculations.html(white_cash_calculations_head);


                } else if (data.cash_type == "black" && tableHeadRow.length <= 0) {
                    tableHead.append(black_cash_head);
                }
                $('#total_sale_calculations').css('display', 'block')
                markup = '<tr id="article_' + data.data.legacyArticleId + '"><td>' + data.data
                    .genericArticleDescription + '-' + data.data.articleNumber +
                    '</td>';

                if (data.cash_type == "white") {
                    markup += '<input type="hidden" value="' + data.stock.white_items +
                        '" id="stock_items_' + data.data.legacyArticleId + '">';
                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterFlowSaleQty(' +
                        data.data.legacyArticleId + ')" id="sale_item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="0" max="' + data.stock.white_items +
                        '" name="item_qty[]" required></td>';
                    var white_price = 1;
                    if (data.stock.unit_sale_price_of_white_cash != null) {
                        white_price = data.stock.unit_sale_price_of_white_cash;
                    }
                    markup +=
                        '<td><input style="width:100px" onkeyup="alterFlowSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + white_price +
                        '" step="any" class="form-control"  id="sale_sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]"></td>';
                } else if (data.cash_type == "black") {
                    markup += '<input type="hidden" value="' + data.stock.black_items +
                        '" id="stock_items_' + data.data.legacyArticleId + '">';
                    var black_price = 1;
                    if (data.stock.unit_sale_price_of_black_cash != null) {
                        black_price = data.stock.unit_sale_price_of_black_cash;
                    }
                    markup +=
                        '<td><input type="number" style="width:100px" class="form-control" onkeyup="alterFlowSaleQty(' +
                        data.data.legacyArticleId + ')" id="sale_item_qty' + data.data
                        .legacyArticleId +
                        '" value="1" min="0" max="' + data.stock.black_items +
                        '" name="item_qty[]" required></td>';
                    markup +=
                        '<td><input style="width:150px" onkeyup="alterFlowSaleQty(' +
                        data.data.legacyArticleId + ')" type="number" value="' + black_price +
                        '" step="any" class="form-control"  id="sale_sale_price_' +
                        data.data.legacyArticleId +
                        '" name="sale_price[]" readonly></td>';
                }

                markup +=
                    '<td><input type="number" onkeyup="alterFlowSaleQty(' +
                    data.data.legacyArticleId +
                    ')" class="form-control" value="0" min="0" max="100" step="any" id="sale_discount_' +
                    data.data.legacyArticleId +
                    '" name="discount[]"></td>';

                if (data.cash_type == "white") {
                    markup +=
                        '<td><input style="width:100px" type="number" class="form-control" value="0" min="0" step="any" id="vat_' +
                        data.data.legacyArticleId +
                        '" name="vat[]" required></td>';
                }

                if (data.cash_type == "black") {
                    markup +=
                        '<td><input style="width:200px" type="number" step="any" class="form-control" min="0"   id="sale_total_without_discount' +
                        data.data.legacyArticleId +
                        '" name="sale_total_without_discount[]" readonly></td>';
                }

                markup +=
                    '<td><input style="width:200px" type="number" step="any" class="form-control" min="0"   id="sale_total_with_discount' +
                    data.data.legacyArticleId +
                    '" name="sale_total_with_discount[]" readonly></td>';

                markup += '<td><button type="button" id="article_delete_' +
                    data.data.legacyArticleId + '" onclick="deleteFlowSaleArticle(' + data.data
                    .legacyArticleId +
                    ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>';

                markup += '<td style="display:none;">' + html +
                    '</td></tr>';

                if (length <= 1) {
                    tableBody.append(markup);
                    $('#myTable tr').each(function() {
                        if (this.id != '') {
                            article_ids_array.push(this.id)
                        }
                    });

                } else {
                    if (!article_ids_array.includes("article_" + data.data.legacyArticleId)) {
                        tableBody.append(markup);
                        article_ids_array.push("article_" + data.data.legacyArticleId)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'This product is already added...you can update its quantity',
                        })
                    }
                }
                if ($('#myTable tr').length <= 1) {
                    selected_cash_type = [];
                }
                all_product_ids.push(data.data.legacyArticleId);

                var sale_price = parseFloat($("#sale_sale_price_" + data.data.legacyArticleId)
                    .val());
                var discount = parseFloat($("#sale_discount_" + data.data.legacyArticleId).val());
                var item_qty = parseInt($("#sale_item_qty" + data.data.legacyArticleId).val());

                var sale_total_with_discount = (item_qty * sale_price) - discount;
                var sale_total_without_discount = (item_qty * sale_price);

                $('#sale_total_with_discount' + data.data.legacyArticleId).val(
                    sale_total_with_discount.toFixed(2));
                $('#sale_total_without_discount' + data.data.legacyArticleId).val(
                    sale_total_without_discount.toFixed(2));
            }
        });
    });

    function checkIfExists(date, engine_type, engine_sub_type, manufacturer_id, model_id, engine_id, section_id,
        section_part_id, cashType) {
        if (!engine_type) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an Engine Type',

            });
            exit();
        }
        if (!engine_sub_type) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an Engine Type',
            });
            exit();
        }
        if (!manufacturer_id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a manufacturer',

            });
            exit();
        }
        if (!model_id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a Model',

            });
            exit();
        }
        if (!engine_id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an engine',

            });
            exit();
        }
        if (!section_id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a section',

            });
            exit();
        }
        if (!section_part_id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a section part',

            });
            exit();
        }

        // if (!status) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'Please select a status',

        //     });
        //     exit();
        // }
        if (!date) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a date',

            });
            exit();
        }
        if (!cashType) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select Cash Type',

            });
            exit();
        }

    }
    var t_qty = 0;
    let w_qty = 0;
    let b_qty = 0;
    var id_array = [];
    var total_quantity_of_all_row_products = 0;

    function alterFlowSaleQty(id) {

        var error = 0;
        var item_qty = parseInt($("#sale_item_qty" + id).val());
        if (!item_qty) {
            item_qty = 1;
        }

        var stock = parseInt($("#stock_items_" + id).val());


        var sale_price = parseFloat($("#sale_sale_price_" + id).val());
        if (!sale_price) {
            sale_price = 1;
        }
        var discount_check = $("#sale_discount_" + id).val();
        if (discount_check % 1 != 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Discount must be Type of Integer',

            });
            $('#sale_discount_' + id).val(0)
            error = 1;
        }
        if (discount_check > 100) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Discount must be less or equal to 100',
            });
            $('#sale_discount_' + id).val(0)
            error = 1;
        }
        var discount = (parseFloat(1) - (parseFloat($("#sale_discount_" + id).val() / 100)));
        if (!discount) {
            discount = (parseFloat(1) - (0 / 100));
        }

        if (error == 0) {
            var sale_total_with_discount = (item_qty * sale_price) * discount;
            var sale_total_without_discount = (item_qty * sale_price);
            if (sale_total_with_discount <= 0) {
                $('#sale_total_with_discount' + id).val(0);
            } else {
                $('#sale_total_with_discount' + id).val(sale_total_with_discount.toFixed(2))
            }

            if (sale_total_without_discount <= 0) {
                $('#sale_total_without_discount' + id).val(0);
            } else {
                $('#sale_total_without_discount' + id).val(sale_total_without_discount.toFixed(2))
            }
            calculateFlowSaleTotal(all_product_ids);
        }
    }


    function deleteFlowSaleArticle(id) {
        $('#article_' + id).remove();
        for (var i = 0; i < all_product_ids.length; i++) {

            if (all_product_ids[i] === id) {

                all_product_ids.splice(i, 1);
            }

        }
        for (var i = 0; i < article_ids_array.length; i++) {

            if (article_ids_array[i] === "article_" + id) {
                console.log("article_4444444444444idsssssss", article_ids_array);

                article_ids_array.splice(i, 1);
            }

        }
        if (all_product_ids.length <= 0) {
            $('#total_sale_calculations').css('display', 'none');
            $('#submit-button').css('display', 'none');
        }
        calculateEntireTotal(all_product_ids);
        // article_ids_array = [];
        if ($('#myTable tr').length == 0) {
            selected_cash_type = [];
        }
    }

    function calculateEntireSaleTotal(product_ids_array) {
        var total_before_discount = 0.0;
        var total_to_be_paid = 0.0;
        // console.log(product_ids_array)
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = product_ids_array.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {

                total_before_discount += (parseInt($('#sale_item_qty' + id).val()) * parseFloat($('#sale_sale_price_' +
                    id).val()));
                var discount = parseFloat($('#sale_discount').val());

                if (discount > parseFloat($('#sale_sale_price_' + id).val())) {
                    $('#sale_discount').val(discount - 1);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'discount can not be greater than sale price',

                    });
                    exit();
                }
            }


            var tax_stamp = parseFloat($('#sale_tax_stamp').val());
            var entire_vat = parseFloat($('#sale_entire_vat').val());
            var discount = parseFloat($('#sale_discount').val());
            $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));
            total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount.toFixed(2))) *
                parseFloat(entire_vat.toFixed(2)) + parseFloat(tax_stamp.toFixed(2));

            if (total_to_be_paid < 0) {
                $('#total_to_be_paid').val(0);
            } else {
                $('#total_to_be_paid').val(total_to_be_paid);
            }


        }
        // }
    }

    function calculateFlowSaleTotal() {
        var total_before_discount = 0.0;
        var total_to_be_paid = 0.0;
        var total_sale_price = 0.0;
        // console.log(product_ids_array)
        var cashType = $('#cash_type').find(":selected").val();
        var id_array = [];
        id_array = all_product_ids.filter(onlyUnique);

        if (id_array.length > 0) {
            id_array.forEach(getActualProductCost);

            function getActualProductCost(id, index) {
                var it_qty = $('#sale_item_qty' + id).val();
                if (!it_qty) {
                    it_qty = 1;
                }
                var sal_price = $('#sale_sale_price_' + id).val();
                if (!sal_price) {
                    sal_price = 1;
                }
                total_sale_price += parseFloat(sal_price);
                total_before_discount += (parseInt(it_qty) * parseFloat(sal_price));



            }


            var tax_stamp = $('#sale_tax_stamp').val();
            if (!tax_stamp) {
                tax_stamp = 0
            }
            var entire_vat = $('#sale_entire_vat').val();
            if (!entire_vat) {
                entire_vat = 1
            }
            var discount = $('#sale_discount').val();
            if (!discount) {
                discount = 0
            }

            if (discount > total_sale_price) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'discount can not be greater than sum of sale price',

                });
                $('#sale_discount').val(discount - parseInt(1))
                exit();

            }
            var discount = $('#sale_discount').val();
            if (!discount) {
                discount = 0
            }
            $('#sale_entire_total_exculding_vat').val(total_before_discount.toFixed(2));

            total_to_be_paid = (parseFloat(total_before_discount.toFixed(2)) - parseFloat(discount)) * parseFloat(
                entire_vat) + parseFloat(tax_stamp);
            console.log("Total to be paid", total_to_be_paid)
            if (total_to_be_paid < 0) {
                $('#total_to_be_paid').val(0);
            } else {
                $('#total_to_be_paid').val(total_to_be_paid);
            }


        }
    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
</script>
