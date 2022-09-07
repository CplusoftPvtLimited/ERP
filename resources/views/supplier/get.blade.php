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
                <h2>Suppliers</h2>
                <p>List of manufacturers of spare parts in the catalog.</p>
            </div>
        </div>
    </div>
    </div>
    <div class="table-responsive">
        <table id="supplier-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Brand Id</th>
                    <th>Brand Logo Id</th>
                    <th>Brand Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $key => $item)
                   
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->brandId }}</td>
                        <td>{{ $item->brandLogoID }}</td>
                        <td>{{ $item->brandName }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        
        <div class="pull-right">
            {{$suppliers->links()}}
        </div>
    </div>

</section>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        var table = $('#supplier-data-table').DataTable( {
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
