@extends('layout.main')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
@section('content')
@if(session()->has('create_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('create_message') }}</div>
@endif
@if(session()->has('edit_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div>
@endif
@if(session()->has('import_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('import_message') }}</div>
@endif
@if(session()->has('not_permitted'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif


<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12" >
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="d-flex flex-row-reverse mb-3 mr-4">
                            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Role')}} </a>
                            </div>
                            <div class="table-responsive">
                                @if(session()->has('message')) 
                                    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
                                @endif
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success"><p>{{ $message }}</p></div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @endif
                                <table id="data-table" class="table" style="width: 100% !important">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'role.store', 'method' => 'post']) !!}
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Role')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                <form>
                    <div class="form-group">
                    <label>{{trans('file.name')}} *</label>
                    {{Form::text('name',null,array('required' => 'required', 'class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>{{trans('file.Description')}}</label>
                        {{Form::textarea('description',null,array('rows'=> 5, 'class' => 'form-control'))}}
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="guard_name" value="web">
                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
            	</form>
        	</div>
        {{ Form::close() }}
    	</div>
	</div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
	<div role="document" class="modal-dialog">
		  <div class="modal-content">
		    {!! Form::open(['route' => ['role.update',1], 'method' => 'put']) !!}
		    <div class="modal-header">
		      <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Update Role')}}</h5>
		      <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
		    </div>
		    <div class="modal-body">
		      <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
		        <form>
		            <input type="hidden" name="role_id">
		            <div class="form-group">
		                <label>{{trans('file.name')}} *</label>
		                {{Form::text('name',null,array('required' => 'required', 'class' => 'form-control'))}}
		            </div>
		            <div class="form-group">
		                <label>{{trans('file.Description')}}</label>
		                {{Form::textarea('description',null,array('rows'=> 5, 'class' => 'form-control'))}}
		            </div>
		            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
		        </form>
		    </div>
		    {{ Form::close() }}
		  </div>
	</div>
</div>
<script type="text/javascript">
        $(document).ready(function () {
            console.log('here');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
                $('#data-table').DataTable({
                    "processing" : true,
                    "serverside" : true,
                    ajax: "{{ route('role.index') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {"data": "name",  name: 'name'},
                        {"data": "description",  name: 'description'},
                        {
                            "data": 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
           });
    </script>
    <script>
        function deleteRole(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                        $.ajax({
                            method: "post",
                            url: "{{ url('/deleteRole') }}",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                location.reload();
                            }

                        });

                    
                }
            });
        }
    </script>
    <script type="text/javascript">

    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #role-menu").addClass("active");

    $(document).ready(function() {
    $(document).on('click', '.open-EditroleDialog', function() {
        var url = "role/"
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");

        $.get(url, function(data) {
            $("input[name='name']").val(data['name']);
            $("textarea[name='description']").val(data['description']);
            $("input[name='role_id']").val(data['id']);
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    });
    </script>
    
@endsection