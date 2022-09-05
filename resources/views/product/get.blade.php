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
    </div>
    <div class="table-responsive">
        <table id="product-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>infoId</th>
                    <th>informationTypeKey</th>
                    <th>informationTypeDescription</th>
                    <th>text</th>
                    <th>assemblyGroupName</th>
                    {{-- <th>legacyArticleId</th> --}}
                    <th>isImmediateDisplay</th>
                    <th>mfrName</th>
                    <th>mfrId</th>
                    {{-- <th class="not-exported">{{trans('file.action')}}</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $item)
                    <tr>
                        <td>{{ $item->articleText->infoId }}</td>
                        <td>{{ $item->articleText->informationTypeKey }}</td>
                        <td>{{ $item->articleText->informationTypeDescription }}</td>
                        <td>{{ $item->articleText->text }}</td>
                        <td>{{ $item->assemblyGroupNodes->assemblyGroupName }}</td>
                        {{-- <td>{{ $item->assemblyGroupNodes->legacyArticleId }}</td> --}}
                        <td>{{ $item->articleText->isImmediateDisplay }}</td>
                        <td>{{ $item->articleText->mfrName }}</td>
                        <td>{{ $item->articleText->mfrName }}</td>
                        <td>{{ $item->articleText->mfrName }}</td>
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
        var table = $('#product-data-table').DataTable( {
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            "processing": true,
            "serverSide": true,
        } );

    } );
    $('select').selectpicker();

</script>
@endpush
