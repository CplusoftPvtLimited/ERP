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
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col" >
            <a href="{{route('manufacturer.create')}}" class="btn btn-info mb-1"><i class="dripicons-plus"></i> {{trans('file.Add Manufacturer')}}</a>
                <h2>Manufacturers</h2>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="manufacturer-data-table" class="table" style="width: 100% !important">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Manufacturer Id</th>
                    <th>Manufacturer Name</th>
                    <th>Linking Target Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($manufacturers as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->manuId }}</td>
                        <td>{{ $item->manuName }}</td>
                        <td>{{ $item->linkingTargetType }}</td>
                        <td>
                        <div class="row">
                            <div class="col-2 mr-1">
                                <a class="btn btn-primary btn-sm mx-1" href="{{ route('manufacturer.edit',$item) }}"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="col-2">
                                <form action="{{ route('manufacturer.destroy', $item) }}" method="POST">
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
        <div class="pull-right">
            {{$manufacturers->links()}}
        </div>
    </div>

</section>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        var table = $('#manufacturer-data-table').DataTable( {
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
