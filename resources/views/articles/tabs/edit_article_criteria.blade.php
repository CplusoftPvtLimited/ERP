<div class="card-header">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ trans('file.Edit Product Criteria') }}</h4>
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
    <form action="{{route('articleCriteria.update', $art_criteria->id)}}" method="post" id="editCriteriaForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="other_data"></div>
                <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <h6>Criteria Type</h6>
                            <input type="text" name="criteriaType" id="criteriaType" class="form-control" value="{{$art_criteria->criteriaType}}" maxlength="5" required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Assembly Group Node</h6>
                            <select name="assemblyGroupNodeId" id="assemblyGroupNodeId" class="form-control">
                                <option value="">--Select One--</option>
                                @foreach ($sections as $section)
                                <option value="{{ $section->assemblyGroupNodeId }}" {{$section->assemblyGroupNodeId == $art_criteria->assemblyGroupNodeId ? "selected" : ""}}>
                                    {{ $section->assemblyGroupName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Article Id</h6>
                            <input type="text" name="legacyArticleId" id="criteria_articleId" class="form-control" value="{{$art_criteria->legacyArticleId}}" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <h6>Criteria Description</h6>
                            <textarea name="criteriaDescription" id="criteriaDescription" cols="10" rows="5 " class="form-control">{{$art_criteria->criteriaDescription}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Criteria Abbr. Description</h6>
                            <textarea name="criteriaAbbrDescription" id="criteriaAbbrDescription" cols="10" rows="5" class="form-control">{{$art_criteria->criteriaAbbrDescription}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6>Criteria Unit Description</h6>
                        <textarea name="criteriaUnitDescription" id="criteriaUnitDescription" cols="10" rows="5" class="form-control">{{$art_criteria->criteriaUnitDescription}}</textarea>
                    </div>

                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Raw Value</h6>
                            <textarea name="rawValue" id="rawValue" cols="10" rows="5" class="form-control">{{$art_criteria->rawValue}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group pt-3 pl-3">
                            <input class="form-check-input" type="checkbox" value="1" name="isInterval" id="isInterval" {{$art_criteria->isInterval == 1 ? 'checked' : ""}}>
                            <label class="form-check-label" for="flexCheckDefault">
                                <h6>Is Interval</h6>
                            </label>
                            <br>
                            <input class="form-check-input" type="checkbox" value="1" name="isMandatory" id="isMandatory" {{$art_criteria->isMandatory == 1 ? 'checked' : ""}}>
                            <label class="form-check-label" for="flexCheckDefault">
                                <h6>Is Mandatory</h6>
                            </label>
                            <br>
                            <input class="form-check-input" type="checkbox" value="1" name="immediateDisplay" id="immediateDisplay" {{$art_criteria->immediateDisplay == 1 ? 'checked' : ""}}>
                            <label class="form-check-label" for="flexCheckDefault">
                                <h6>Immediate Display</h6>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-success" id="submitCriteria" style="width:100px">Update</button>
                    <button type="button" class="btn btn-primary mr-2" style="width:100px" id="nxtCriteria">Next</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#nxtCriteria').on('click', function() {
            var legacyArticleId = $('#criteria_articleId').val();
            var criteriaType = $('#criteriaType').val();
            var criteriaDescription = $('#criteriaDescription').val();
            var criteriaAbbrDescription = $('#criteriaAbbrDescription').val();
            var criteriaUnitDescription = $('#criteriaUnitDescription').val();
            var rawValue = $('#rawValue').val();
            var isInterval = ($('#isInterval:checked').val() == undefined) ? 0 : $('#isInterval:checked').val();
            var isMandatory = ($('#isMandatory:checked').val() == undefined) ? 0 : $('#isMandatory:checked').val();
            var immediateDisplay = ($('#immediateDisplay:checked').val() == undefined) ? 0 : $('#immediateDisplay:checked').val();
            var ajax = 1;
            if (legacyArticleId == "") {
                Swal.fire({
                    title: 'Error',
                    text: "Please Add a Product First",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else {
                $.ajax({
                    url: "{{route('articleCriteria.update', $art_criteria->id)}}",
                    type: "PUT",
                    data: {
                        legacyArticleId: legacyArticleId,
                        criteriaType: criteriaType,
                        criteriaDescription: criteriaDescription,
                        criteriaAbbrDescription: criteriaAbbrDescription,
                        criteriaUnitDescription: criteriaUnitDescription,
                        rawValue: rawValue,
                        isInterval: isInterval,
                        isMandatory: isMandatory,
                        immediateDisplay: immediateDisplay,
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
                                document.getElementById('editArticleCrteria').style.display = "none";
                                var tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    if (tablinks[i].id != "editcrossesTab") {
                                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                                    }
                                }
                                var tablink = document.getElementById("editcrossesTab");
                                tablink.className = tablink.className += " active"
                                document.getElementById('editArticleCrosses').style.display = "block";
                                // event.currentTarget.className += " active";
                            }
                            // console.log(response.data.id) //Message come from controller
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
    })
</script>