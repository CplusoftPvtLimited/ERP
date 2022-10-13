<div class="container">
    <form action="{{ route('article.index') }}">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engine Type</label>
                    <select name="linkageTargetType" id="linkageTarget" class="selectpicker form-control"
                        data-live-search="true" data-live-search-style="begins">
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
                        id="subLinkageTarget" class="selectpicker form-control" data-live-search="true"
                        data-live-search-style="begins">
                        <option value="-2">Select One</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{ trans('file.Manufacturers') }}</label>
                    <select name="manufacture_id" id="manufacturer_id" class="selectpicker form-control"
                        data-live-search="true" data-live-search-style="begins" title="Select Manufacturer..."
                        data-href="{{ route('search_models_by_manufacturer') }}">
                    </select>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="model_id">{{ __('Select Model') }}</label>
                    <select name="model_id" id="model_id" data-href="{{ route('search_engines_by_model') }}"
                        class="selectpicker form-control" data-live-search="true" data-live-search-style="begins"
                        required>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="engine_id">{{ __('Select Engine') }}</label>
                    <select name="engine_id" id="engine_id" data-href="{{ route('search_sections_by_engine') }}"
                        class="selectpicker form-control" data-live-search="true" data-live-search-style="begins"
                        required>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="section_id">{{ __('Select Section') }}</label>
                    <select name="section_id" id="section_id" data-href="{{ route('get_section_parts') }}"
                        class="selectpicker form-control" data-live-search="true" data-live-search-style="begins"
                        required>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-group">
                    <button type="button" class="btn btn-info purchase-save-btn"
                        id="save-btn">{{ trans('file.Search') }}</button>
                </div>
            </div>

        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
    $('#linkageTarget').on('change', function(e) {
        e.preventDefault();
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

    // get manufacturers
    $(document).on('change', '#subLinkageTarget', function() {

        let engine_sub_type = $(this).val();
        // alert(manufacture_id)
        let url = $(this).attr('data-href');

        getManufacturer(url, engine_sub_type);
    });

    function getManufacturer(url, engine_sub_type) {
        $.get(url + '?engine_sub_type=' + engine_sub_type, function(data) {
            $('#section_id').html('<option value="">Select One</option>');
            $('#section_id').selectpicker("refresh");
            $('#section_part_id').html('<option value="">Select One</option>');
            $('#section_part_id').selectpicker("refresh");
            $('#engine_id').html('<option value="">Select One</option>');
            $('#engine_id').selectpicker("refresh");
            let response = data.data;
            if (response.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No Manufacturer Found Against the selected Engine Sub-Type. Please Change the Engine Sub-Type',
                });
            }
            let view_html = `<option value="">Select One</option>`;
            $.each(response, function(key, value) {
                view_html += `<option value="${value.manuId}">${value.manuName}</option>`;
            });
            $('#manufacturer_id').html(view_html);
            $("#manufacturer_id").selectpicker("refresh");


        })
    }

    //get models==================
    $(document).on('change', '#manufacturer_id', function(e) {
        e.preventDefault();
        let manufacturer_id = $(this).val();
        // alert(manufacture_id)
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        let url = $(this).attr('data-href');
        getModels(url, manufacturer_id, engine_sub_type);
    });

    function getModels(url, manufacturer_id, engine_sub_type) {
        $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type, function(data) {
            // $('#model_id').html(`<option value="">Select Model</option>`);
            $('#section_id').html('<option value="">Select One</option>');
            $('#section_id').selectpicker("refresh");
            $('#section_part_id').html('<option value="">Select One</option>');
            $('#section_part_id').selectpicker("refresh");
            $('#engine_id').html('<option value="">Select One</option>');
            $('#engine_id').selectpicker("refresh");


            let response = data.data;
            if (response.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Selected Manufacturer do not have any Model. Please Change the Manufacturer',
                });
            }
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

    ////// get engines==================
    $(document).on('change', '#model_id', function(e) {
        e.preventDefault();
        let model_id = $(this).val();
        let url = $(this).attr('data-href');
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        getEngines(url, model_id, engine_sub_type);
    });

    function getEngines(url, model_id, engine_sub_type) {
        $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type, function(data) {
            $('#section_id').html('<option value="">Select One</option>');
            $('#section_id').selectpicker("refresh");
            $('#section_part_id').html('<option value="">Select One</option>');
            $('#section_part_id').selectpicker("refresh");
            $('#engine_id').html('<option value="">Select One</option>');
            $('#engine_id').selectpicker("refresh");

            let response = data.data;
            if (response.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Selected Model do not have any engine. Please Change the Model',
                });
            }
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

    ///// get sections==================
    $(document).on('change', '#engine_id', function(e) {
        e.preventDefault();
        let engine_id = $(this).val();
        let url = $(this).attr('data-href');
        let engine_sub_type = $('#subLinkageTarget :selected').val();
        getSections(url, engine_id, engine_sub_type);
    });

    function getSections(url, engine_id, engine_sub_type) {
        $.get(url + '?engine_id=' + engine_id + '&engine_sub_type=' + engine_sub_type, function(data) {

            $('#section_part_id').html('<option value="">Select One</option>');
            $('#section_part_id').selectpicker("refresh");
            let response = data.data;
            if (response.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Selected Engine do not have any Section. Please Change the Engine',
                });
            }
            let view_html = `<option value="">Select One</option>`;
            $.each(response, function(key, value) {
                view_html +=
                    `<option value="${value.assemblyGroupNodeId}">${value.assemblyGroupName}</option>`;
            });
            // console.log(data, view_html);
            $('#section_id').html(view_html);
            $("#section_id").val(4);
            $("#section_id").selectpicker("refresh");
        })
    }
</script>
