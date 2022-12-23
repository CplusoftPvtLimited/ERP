@extends('layout.main')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@section('content')
    @if (session()->has('create_message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('create_message') }}</div>
    @endif
    @if (session()->has('edit_message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div>
    @endif
    @if (session()->has('import_message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('import_message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif


    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card p-0">
                        <div class="row m-0">
                            <div class="col-3" style="margin: 0px; padding:0px; ">
                                <div class="card" style="margin: 0px; padding:0px; height: 100%;">
                                    <div class="card-body" style="margin: 0px;">
                                        <div class="tab search-tabs">
                                            <button class="searchLinks" onclick="switchTab(event, 'generalSearch')" id="defaultOpen">Filter</button>
                                            <button class="searchLinks" onclick="switchTab(event, 'searchByProductNumber')" id="searchByProductNoTab">Search By Product Number</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9" style="margin: 0px; padding:0px;">
                                <div class="card" style="margin: 0px; height:100%;">
                                    <div class="card-body searchcontent" id="generalSearch">
                                        @include('articles.general_search')
                                    </div>
                                    <div class="card-body searchcontent" id="searchByProductNumber">
                                        @include('articles.search_product_number')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <div class="container"> -->
                                <div class="d-flex flex-row-reverse mb-3 mr-4">
                                    <a href="{{ route('article.create') }}" class="btn btn-info mb-1"><i
                                            class="dripicons-plus"></i> {{ trans('file.Add Product') }}</a>
                                    <div class="col pl-4 pt-1">
                                        <h2>Products</h2>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    @if (session()->has('message'))
                                        <div class="alert alert-success alert-dismissible text-center"><button
                                                type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}
                                        </div>
                                    @endif
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @endif
                                    @if ($message = Session::get('error'))
                                        <div class="alert alert-danger">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @endif
                                    <table id="model-data-table" class="table" style="width: 100% !important">
                                        <thead class="site-color">
                                            <tr>
                                                <th>#</th>
                                                <th>Product ID</th>
                                                <th>Product Number</th>
                                                <th>Manufacturer</th>
                                                <th>Section</th>
                                                <th>Additional Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            console.log('here');
            var article_id = engine_sub_type = section_id = null;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var oTable = $('#model-data-table').DataTable({
                "ordering": false,
                "processing": true,
                "serverside": true,
                ajax: {
                    "url": "{{ route('article.index') }}",
                    "data": (d) => {
                        d.article_id =  article_id,
                        d.engine_sub_type = engine_sub_type,
                        d.section_id = section_id
                    }
                },
                columns: [{
                        data: 'index',
                        name: 'index'
                    },
                    {
                        "data": 'legacyArticleId',
                        name: 'legacyArticleId'
                    },
                    {
                        "data": "articleNumber",
                        name: 'articleNumber'
                    },
                    {
                        "data": "manufacturer",
                        name: 'manufacturer'
                    },
                    {
                        "data": "section",
                        name: 'section'
                    },
                    {
                        "data": "additionalDescription",
                        name: 'additionalDescription'
                    },
                    {
                        "data": 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
            $('.purchase-save-btn').on('click', (e) => {
                article_id =  $('#automplete-1').val(),
                engine_sub_type = null;
                section_id = null;
                oTable.ajax.reload();
                
            });
            $('#save-btn').on('click', (e) => {
                engine_sub_type = $('#subLinkageTarget').val();
                section_id = $('#section_id').val();
                article_id = null;
                oTable.ajax.reload();
            });
        });
    </script>
    <script>
        function switchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("searchcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("searchlinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
    
@endsection