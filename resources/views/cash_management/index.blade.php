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
                    <div class="card-header">
                        <h3 class="text-center">{{trans('file.Primary balance')}}</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>
                            <strong>0,000 TND  TTC</strong>
                        </p>
                        <button class="btn btn-primary primary-details">
                            <i class="fa fa-eye"></i> {{trans('file.Detail')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">{{trans('file.Secondary balance')}}</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>
                            <strong>0,000 TND  TTC</strong>
                        </p>
                        <button class="btn btn-primary secoundry-details">
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
        window.location = `{{ route('cash.management.cheque')}}`;
    });
    $('.secoundry-details').click(function() {
        console.log('here');
        window.location = `{{ route('cash.management.cheque')}}`;
    });
</script>
@endpush
