@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid"><!-- Revenue, Hit Rate & Deals -->
        @if(Session::has('error'))
                <p class="bg-danger text-white p-2 rounded">{{Session::get('error')}}</p>
                @endif
                @if(Session::has('success'))
                <p class="bg-success text-white p-2 rounded">{{Session::get('success')}}</p>
                @endif
                    <div class="pull-left">
                        <h2>Form Management</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-success" href="{{ route('form.create') }}"> Create New Form</a>
                    </div>
    </div>
    <div class="table-responsive">
        <table class="table" id ="form-table" style="width:100%" >
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $i=0; @endphp
                @foreach ($form_all as $key => $form)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $form->form_name }}</td>
                    <td>
                        <div class="row">
                            <div class="col-2">
                                <a class="btn btn-primary btn-sm mx-1" href="{{ route('form.edit',$form->id) }}"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="col-2">
                                <form action="{{ route('form.destroy', $form->id) }}" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm mx-1"><i
                                    class="fa fa-trash text-white"></i></button>
                                </form>
                            </div>
                        
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#form-table').DataTable( {
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            "processing": true,
            "paging" : false,
            "searching": false
            // "serverSide": true,
        } );
    } );
    $('select').selectpicker();
</script>
@endpush

