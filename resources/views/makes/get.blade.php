@extends('layout.main') @section('content')
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
@if(session()->has('message'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col" >
                <h2>Makes</h2>
                <p>List of all vehicle manufacturers.</p>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="make-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Manufacture Id</th>
                    <th>Manufacture Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($makes as $key => $item)
                   
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->mfrId }}</td>
                        <td>{{ $item->mfrName }}</td>

                        
                    </tr>
                @endforeach
            </tbody>

        </table>
        
        <div class="pull-right">
            {{$makes->links()}}
        </div>
    </div>

</section>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        var table = $('#make-data-table').DataTable( {
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            "processing": true,
            "paging" : false
            // "serverSide": true,
        } );

    } );
    $('select').selectpicker();

</script>
@endpush
