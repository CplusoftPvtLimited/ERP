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
                <h3 class="">{{trans('file.Section Parts')}}</h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="product-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>Article Id</th>
                    <th>Article Number</th>
                    <th>Generic Article Description</th>
                    <th>Article Country</th>
                    <th>Brand Name</th>
                    <th>Assembly Group Node ID</th>
                    <th>Linkage Target ID</th>
                    <th>Linkage Target Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($getSectionParts) > 0)
                @foreach ($getSectionParts as $key => $item)

                    <tr>
                        <td>{{ $item->article->legacyArticleId }}</td>
                        <td>{{ $item->article->articleNumber }}</td>
                        <td>{{ $item->article->genericArticleDescription }}</td>
                        <td>{{ isset($item->article->brand) ?  $item->article->brand->brandName : "not given" }}</td>
                        <td>{{ isset($item->article->brand) ?  $item->article->brand->articleCountry : "not given" }}</td>
                        <td>{{ $item->assemblyGroupNodeId }}</td>
                        <td>{{ $item->linkingTargetId }}</td>
                        <td>{{ $item->linkingTargetType }}</td>
                        @if (!empty($item->article->brand))
                        <td>  
                            <a href="{{ route('get_language',$item->article->brand->id) }}" class="btn btn-primary">Get Brand lang</a>
                        </td>                            
                        @else
                        <td>  
                            <button class="btn btn-warning">brand not Available</button>
                        </td> 
                        @endif
                       
                    </tr>
                @endforeach
                @endif
            </tbody>

        </table>
        
        <div class="pull-right">
            {{$getSectionParts->links()}}
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
