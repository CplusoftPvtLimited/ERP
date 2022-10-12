<div class="card-header">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ trans('file.Edit Product Ean') }}</h4>
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
    <form action="{{ isset($art_ean->id) ? route('articleEan.update', $art_ean->id) : route('articleEan.store') }}" method="post" id="eanForm" enctype="multipart/form-data">
        @csrf
        @if (isset($art_ean->id))
            @method('PUT')
        @else
            @method('POST')
        @endif
        <div class="row">
            <div class="col-12">
                <div class="other_data"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <h6>Article Id *</h6>
                            <input type="text" name="legacyArticleId" id="ean_articleId" class="form-control" value="{{isset($article->id) ? $article->legacyArticleId : ""}}" readonly required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <h6>EAN Code *</h6>
                            <input type="text" name="eancode" id="eancode" class="form-control" maxlength="25" value="{{isset($art_ean->id) ? $art_ean->eancode : ""}}" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" id="saveEan" class="btn btn-success" style="width:100px">Update</button>
                    <button type="button" id="nextEan" class="btn btn-primary mr-2" style="width:100px">Next</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#nextEan').on('click', function() {
            var legacyArticleId = $('#ean_articleId').val();
            var eancode = $('#eancode').val();
            var ajax = 1;
            if (eancode == "") {
                Swal.fire({
                    title: 'Error',
                    text: "Ean Code is Required",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else {
                var method = "{{isset($art_ean->id) ? 'PUT' : 'POST' }}";
                $.ajax({
                    url: "{{ isset($art_ean->id) ? route('articleEan.update', $art_ean->id) : route('articleEan.store') }}",
                    type: method,
                    data: {
                        legacyArticleId: legacyArticleId,
                        eancode: eancode,
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
                                document.getElementById('editArticleEan').style.display = "none";
                                var tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    if (tablinks[i].id != "editlinkstab") {
                                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                                    }
                                }
                                var tablink = document.getElementById("editlinkstab");
                                tablink.className = tablink.className += " active"
                                document.getElementById('editArticleLinks').style.display = "block";
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