<div class="card-header">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ trans('file.Edit Product Crosses') }}</h4>
        </div>
        <div class="col-md-6">
            <a href="{{ route('article.index') }}" class="btn btn-primary float-right">Back</a>
        </div>
    </div>
</div>
<div class="card-body">
    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
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
    <form action="{{ route('articleCrosses.store') }}" method="post" id="crossesForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="other_data"></div>
                <div class="row">
                    <div class="col-4">
                        <h6>Oem Number *</h6>
                        <input type="text" name="oemNumber" id="crossesOemNumber" maxlength="255" class="form-control" value="{{$art_crosses->oemNumber}}" required>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Manufacturer</h6>
                            <select name="mfrId" id="crossmfrId" class="form-control">
                                @foreach ($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->manuId }}" {{$art_crosses->mfrId ==  $manufacturer->manuId ? 'selected' : ""}}>
                                    {{ $manufacturer->manuName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Assembly Group Node</h6>
                            <select name="assemblyGroupNodeId" id="crossesAssemblyGroupNodeId" class="form-control">
                                @foreach ($sections as $section)
                                <option value="{{ $section->assemblyGroupNodeId }}" {{$art_crosses->assemblyGroupNodeId ==  $section->assemblyGroupNodeId ? 'selected' : ""}}>
                                    {{ $section->assemblyGroupName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h6>Brand Name</h6>
                        <select name="brandName" id="crossesBrandName" class="form-control">
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->brandName }}" {{$supplier->brandName == $art_crosses->brandName ? 'selected' : ""}}>
                                {{ $supplier->brandName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Article Id</h6>
                            <input type="text" name="legacyArticleId" id="crosses_articleId" class="form-control" value="{{$art_crosses->legacyArticleId}}" readonly required>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="button" class="btn btn-success" id="saveCrosses" style="width:100px">Update</button>
                    <button type="button" class="btn btn-primary mr-2" style="width:100px" id="nxtCrosses">Next</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#saveCrosses').on('click', function() {
            var crosses_articleId = $('#crosses_articleId').val();
            if (crosses_articleId == "") {
                Swal.fire({
                    title: 'Error',
                    text: "Please Add a Product First",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else {
                document.getElementById("crossesForm").submit();
            }
        });
        $('#nxtCrosses').on('click', function() {
            var legacyArticleId = $('#crosses_articleId').val();
            var oemNumber = $('#crossesOemNumber').val();
            var brandName = $('#crossesBrandName').val();
            var assemblyGroupNodeId = $('#crossesAssemblyGroupNodeId').val();
            var mfrId = $('#crossmfrId').val();
            var ajax = 1;
            if (legacyArticleId == "") {
                Swal.fire({
                    title: 'Error',
                    text: "Please Add a Product First",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else if (oemNumber == "") {
                Swal.fire({
                    title: 'Error',
                    text: "Oem Number is Required",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else {
                $.ajax({
                    url: "{{route('articleCrosses.store')}}",
                    type: "POST",
                    data: {
                        legacyArticleId: legacyArticleId,
                        oemNumber: oemNumber,
                        brandName: brandName,
                        assemblyGroupNodeId: assemblyGroupNodeId,
                        mfrId: mfrId,
                        ajax: ajax,
                        "_token": "{{ csrf_token() }}"
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
                            if (legacy_id != null) {
                                $('#ean_articleId').val(response.data.legacyArticleId)
                                document.getElementById('ArticleCrosses').style.display = "none";
                                var tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    if (tablinks[i].id != "eanTab") {
                                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                                        tablinks[i].disabled = true;
                                    }
                                }
                                var tablink = document.getElementById("eanTab");
                                tablink.className = tablink.className += " active"
                                document.getElementById('ArticleEan').style.display = "block";
                            }
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                showCancelButton: true,
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
            }
        });
    });
</script>