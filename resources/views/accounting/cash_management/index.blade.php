@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header article_view_tr_head">
                        <h3 class="text-center">{{trans('file.Primary balance')}}</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>
                            <strong>{{$primary_revenue}} TND</strong>
                        </p>
                        <button data-href="{{ route('cash.management.balance') }}" class="btn btn-primary primary-details">
                            <i class="fa fa-eye"></i> {{trans('file.Detail')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header article_view_tr_head">
                        <h3 class="text-center">{{trans('file.Secondary balance')}}</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>
                            <strong>{{$secondary_revenue}} TND</strong>
                        </p>
                        <button data-href="{{ route('cash.management.balance') }}" class="btn btn-primary secoundry-details">
                            <i class="fa fa-eye"></i> {{trans('file.Detail')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
       
        
    </div>
</section>

@endsection

@push('scripts')
<script type="text/javascript">
    $('.primary-details').click(function() {
        console.log('here');
        let url = $(this).attr('data-href');
        let balance_type = "primary";
        let operation = 0;
            // alert(url);
        window.location = url + '?balance_type=' + balance_type + '&operation=' + operation;
    });
    $('.secoundry-details').click(function() {
        console.log('here');
        let url = $(this).attr('data-href');
        let balance_type = "secondary";
        let operation = 0;
        window.location = url + '?balance_type=' + balance_type + '&operation=' + operation;
    });
</script>
@endpush
