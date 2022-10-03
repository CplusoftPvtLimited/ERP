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
        <div class="card">
            <div class="card-header">
                <h3 class="">{{trans('file.Brand Languages')}}</h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="product-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                
                    <th>#</th>
                    <th>Language Code</th>
                    <th>Language Name</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($languages as $key => $item)

                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->languageCode }}</td>
                        <td>{{ $item->languageName }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        
        <div class="pull-right">
            {{$languages->links()}}
        </div>
    </div>

    <div class="mt-5">
        <div class="col-md-2">
            <a href="{{ route('assembly_group_nodes.index') }}" class="btn btn-primary">Back</a>
        </div>
        
    </div>

</section>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        var table = $('#product-data-table').DataTable( {
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
