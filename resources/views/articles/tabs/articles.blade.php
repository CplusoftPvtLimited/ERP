<div class="card-header">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ trans('file.Add Product') }}</h4>
        </div>
        <div class="col-md-6">
            <a href="{{ route('article.index') }}" class="btn btn-primary float-right">Back</a>
        </div>
    </div>
</div>
<div class="card-body" style="margin: 0px;">
    <p class="italic"><small>{{ trans('file.The field labels marked with * are required input fields') }}.</small></p>
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
    <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="other_data">
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6><label>Engine Type</label></h6>
                            <select name="linkageTargetType" id="linkageTarget" class="selectpicker form-control"
                                data-live-search="true" data-live-search-style="begins">

                                <option>Select Type</option>
                                <option value="P">Passenger</option>
                                <option value="O">Commercial Vehicle and Tractor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6><label>Engine Sub-Type</label></h6>
                            <select name="subLinkageTargetType"
                                data-href="{{ route('get_manufacturers_by_engine_type') }}" id="subLinkageTarget"
                                class="selectpicker form-control" data-live-search="true"
                                data-live-search-style="begins">
                                <option value="-2">Select One</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Manufacturers *</h6>
                            <select name="mfrId" id="mfrId" class="selectpicker form-control"
                                data-live-search="true" data-live-search-style="begins" title="Select Manufacturer..."
                                data-href="{{ route('get_models_by_manufacturer') }}" required>
                                @foreach ($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->manuId }}">
                                        {{ $manufacturer->manuName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Model *</h6>
                            <select name="modelSeries" id="modelSeries"
                                data-href="{{ route('search_engines_by_model') }}" class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Engine *</h6>
                            <select name="linkingTargetId" id="linkingTargetId"
                                data-href="{{ route('get_sections_by_engine') }}" class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Sections *</h6>
                            <select name="assemblyGroupNodeId" id="assemblyGroupNodeId" class="form-control" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Supplier *</h6>
                            <select name="dataSupplierId" id="dataSupplierId" class="form-control" required>
                                <option value="" selected disabled>--Select One--</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->brandId }}">
                                        {{ $supplier->brandName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6>Product Number *</h6>
                        <input type="text" name="articleNumber" id="articleNumber" maxlength="150"
                            pattern="([^\s\-+=!@#$%^&*_|][0-9\s\-+=!@#$%^&*_|]+)" class="form-control" required>
                        <p class="italic text-info">
                            <small>{{ trans('file.Only numbers and spaces are allowed') }}.</small>
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Quantity per Package</h6>
                            <input type="number" name="quantityPerPackage" id="quantityPerPackage" min="0"
                                max="9999999999999999999" class="form-control" required>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Quantity/Package/Package</h6>
                            <input type="number" id="quantityPerPartPerPackage" name="quantityPerPartPerPackage"
                                min="0" max="9999999999999999999" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6>Additional Description</h6>
                        <textarea name="additionalDescription" id="additionalDescription" cols="10" rows="5"
                            class="form-control"></textarea>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Generic Product Description</h6>
                            <textarea name="genericArticleDescription" id="genericArticleDescription" cols="10" rows="5"
                                class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-success" style="width:100px">Save</button>
                    <button type="button" class="btn btn-primary mr-2" style="width:100px"
                        id="nxtProduct">Next</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
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
            $('#mfrId').html('<option value="">Select One</option>');
            $('#mfrId').selectpicker("refresh");
            $('#modelSeries').html('<option value="">Select One</option>');
            $('#modelSeries').selectpicker("refresh");
            $('#linkingTargetId').html('<option value="">Select One</option>');
            $('#linkingTargetId').selectpicker("refresh");
        });

        $(document).on('change', '#subLinkageTarget', function() {

            let engine_sub_type = $(this).val();
            // alert(manufacture_id)
            let url = $(this).attr('data-href');

            getManufacturer(url, engine_sub_type);
        });

        function getManufacturer(url, engine_sub_type) {
            $.get(url + '?engine_sub_type=' + engine_sub_type, function(data) {
                // $('#model_id').html(`<option value="">Select Model</option>`);
                $('#modelSeries').html('<option value="">Select One</option>');
                $('#modelSeries').selectpicker("refresh");
                $('#linkingTargetId').html('<option value="">Select One</option>');
                $('#linkingTargetId').selectpicker("refresh");

                let response = data.data;
                if (response.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Manufacturer Found Against the selected Engine Sub-Type. Please Change the Engine Sub-Type',
                    });
                }
                // console.log(response)
                let view_html = `<option value="">Select One</option>`;
                $.each(response, function(key, value) {
                    view_html += `<option value="${value.manuId}">${value.manuName}</option>`;
                });
                // console.log(data, view_html);
                $('#mfrId').html(view_html);
                // $("#model_id").val(4);
                $("#mfrId").selectpicker("refresh");


            })
        }
        $('#mfrId').on('change', function() {
            let manufacturer_id = $(this).val();
            // alert(manufacturer_id);
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            let url = $(this).attr('data-href');
            getModels(url, manufacturer_id, engine_sub_type);
        });

        function getModels(url, manufacturer_id, engine_sub_type) {
            $.get(url + '?manufacturer_id=' + manufacturer_id + '&engine_sub_type=' + engine_sub_type, function(
                data) {
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
                $('#modelSeries').html(view_html);
                $("#modelSeries").selectpicker("refresh");
            })
        }
        $('#modelSeries').on('change', function() {
            let model_id = $(this).val();
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            let url = $(this).attr('data-href');
            getEngines(url, model_id, engine_sub_type);
        });

        function getEngines(url, model_id, engine_sub_type) {
            $.get(url + '?model_id=' + model_id + '&engine_sub_type=' + engine_sub_type, function(data) {
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
                        `<option value="${value.linkageTargetId}">${value.description + "(" + value.beginYearMonth+ " - "+ value.endYearMonth + ")"}</option>`;
                });
                $('#linkingTargetId').html(view_html);
                $("#linkingTargetId").val(4);
                $("#linkingTargetId").selectpicker("refresh");
            })
        }
        $('#linkingTargetId').on('change', function() {
            let engine_id = $(this).val();
            let engine_sub_type = $('#subLinkageTarget :selected').val();
            let url = $(this).attr('data-href');
            getSections(url, engine_id, engine_sub_type);
        });

        function getSections(url, engine_id, engine_sub_type) {
            $.get(url + '?engine_id=' + engine_id + '&engine_sub_type=' + engine_sub_type, function(data) {
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
                $('#assemblyGroupNodeId').html(view_html);
                $("#assemblyGroupNodeId").val(4);
                $("#assemblyGroupNodeId").selectpicker("refresh");
            })
        }
        $('#nxtProduct').on('click', function() {
            var mfrId = $('#mfrId').val();
            var model_id = $('#modelSeries').val();
            var linkingTargetId = $('#linkingTargetId').val();
            var dataSupplierId = $('#dataSupplierId').val();
            var assemblyGroupNodeId = $('#assemblyGroupNodeId').val();
            var articleNumber = $('#articleNumber').val();
            var quantityPerPackage = $('#quantityPerPackage').val();
            var quantityPerPartPerPackage = $('#quantityPerPartPerPackage').val();
            var additionalDescription = $('#additionalDescription').val();
            var genericArticleDescription = $('#genericArticleDescription').val();
            var ajax = 1;
            console.log(articleNumber);
            if (/[a-z]/i.test(articleNumber)) {
                Swal.fire({
                    title: 'Error',
                    text: "Product Number Must Not Contain any Alphabet",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else if (/[-,+,!,@,#,$,%,^,&,*,(,),=,|,{,},_]/i.test(articleNumber)) {
                Swal.fire({
                    title: 'Error',
                    text: "Product Number can't contain any special character",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else if ((quantityPerPackage != "" && quantityPerPackage < 0) || (
                    quantityPerPartPerPackage != "" && quantityPerPartPerPackage < 0)) {
                Swal.fire({
                    title: 'Error',
                    text: "Quantity can't be in negative number",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });

            } else if (mfrId != "" && dataSupplierId != "" && assemblyGroupNodeId != "" &&
                articleNumber != "" && model_id != "" && linkingTargetId != "") {
                $.ajax({
                    url: "{{ route('article.store') }}",
                    type: "POST",
                    data: {
                        mfrId: mfrId,
                        dataSupplierId: dataSupplierId,
                        assemblyGroupNodeId: assemblyGroupNodeId,
                        linkingTargetId: linkingTargetId,
                        articleNumber: articleNumber,
                        quantityPerPackage: quantityPerPackage,
                        quantityPerPartPerPackage: quantityPerPartPerPackage,
                        additionalDescription: additionalDescription,
                        genericArticleDescription: genericArticleDescription,
                        ajax: ajax,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.data.id) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            });
                            var legacy_id = response.data.legacyArticleId;
                            var product_name = response.data;
                            $('#criteria_articleId').val(response.data.legacyArticleId)
                            if (legacy_id != null) {
                                document.getElementById('Articles').style.display = "none";
                                var tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    if (tablinks[i].id != "criteriaTab") {
                                        tablinks[i].className = tablinks[i].className
                                            .replace(" active", "");
                                        tablinks[i].disabled = true;
                                    }
                                }
                                var tablink = document.getElementById("criteriaTab");
                                tablink.className = tablink.className += " active"
                                document.getElementById('ArticleCrteria').style.display =
                                    "block";
                                // event.currentTarget.className += " active";
                            }
                            // console.log(response.data.id) //Message come from controller
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: "Something Went Wrong",
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: "Please Fill Out the Required Fields",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }
        });

    });
</script>
