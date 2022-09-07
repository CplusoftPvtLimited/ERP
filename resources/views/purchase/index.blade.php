@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">{{trans('file.Purchase List')}}</h3>
            </div>
            {!! Form::open(['route' => 'purchases.index', 'method' => 'get']) !!}
            <!-- <div class="row mb-3">
                <div class="col-md-4 offset-md-2 mt-3">
                    <div class="form-group row">
                        <label class="d-tc mt-2"><strong>{{trans('file.Choose Your Date')}}</strong> &nbsp;</label>
                        <div class="d-tc">
                            <div class="input-group">
                                <input type="text" class="daterangepicker-field form-control" value="{{$starting_date}} To {{$ending_date}}" required />
                                <input type="hidden" name="starting_date" value="{{$starting_date}}" />
                                <input type="hidden" name="ending_date" value="{{$ending_date}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3 @if(\Auth::user()->role_id > 2){{'d-none'}}@endif">
                    <div class="form-group row">
                        <label class="d-tc mt-2"><strong>{{trans('file.Choose Warehouse')}}</strong> &nbsp;</label>
                        <div class="d-tc">
                            <select id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" >
                                <option value="0">{{trans('file.All Warehouse')}}</option>
                                @foreach($lims_warehouse_list as $warehouse)
                                    @if($warehouse->id == $warehouse_id)
                                        <option selected value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @else
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mt-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="filter-btn" type="submit">{{trans('file.submit')}}</button>
                    </div>
                </div>
            </div> -->
            {!! Form::close() !!}
        </div>
        @if(in_array("purchases-add", $all_permission))
            <a href="{{route('purchases.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Purchase')}}</a>&nbsp;
            <!-- <a href="{{url('purchases/purchase_by_csv')}}" class="btn btn-primary"><i class="dripicons-copy"></i> {{trans('file.Import Purchase')}}</a> -->
        @endif
    </div>
    <div class="card p-3 mt-3">
    <div class="table-responsive">
        <table id="purchase-table" class="table purchase-list" style="width: 100%">
            <thead>
                <tr>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}}</th>
                    <th>{{trans('file.Supplier')}}</th>
                    <th>{{trans('file.Purchase Status')}}</th>
                    <th>{{trans('file.grand total')}}</th>
                    <!-- <th>{{trans('file.Paid')}}</th> -->
                    <!-- <th>{{trans('file.Due')}}</th> -->
                    <!-- <th>{{trans('file.Payment Status')}}</th> -->
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                @php
                    $supplier = App\Supplier::find($purchase->supplier_id);
                @endphp
                <tr>
                    <td>{{$purchase->created_at->format('d-m-Y')}}</td>
                    <td>{{$purchase->reference_no}}</td>
                    <td>{{$supplier->name}}</td>
                    @if($purchase->status == 1)
                    <td>{{trans('file.Recieved')}}</td>
                    @elseif($purchase->status == 4)
                    <td>{{trans('file.Ordered')}}</td>
                    @endif
                    <td>{{$purchase->grand_total}}</td>
                    
                    <td>
                        <a class="btn" href="{{route('purchases.edit',$purchase->id)}}"><i class="dripicons-document-edit"></i></a>
                        <!-- <a class="btn" href="">$</a> -->
                        <a class="btn" href="{{route('purchases.generatePurchasePDF',$purchase->id)}}"><i class="fa fa-file-pdf-o"></i></a>
                        <a class="btn" href="{{route('purchases.purchasePreview',$purchase->id)}}"><i class="fa fa-eye"></i></a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready( function () {
    $('#purchase-table').DataTable();
} );
</script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
