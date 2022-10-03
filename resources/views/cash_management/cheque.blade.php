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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{trans('file.Filtered')}}</h3>
                        {{-- <button class="btn btn-success"><i class="fa fa-plus"></i>abc</button> --}}
                    </div>
                    <div class="card-body">
                        <div class="collapse show">
                            <div class="flex-column d-flex flex-md-row p-2">
                                <div class="d-flex p-2 flex-column">
                                    <label for="" class="text-secondary">Type date</label>
                                    <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="radiodueDate" id="inlineRadio1" value="date" checked>
                                            <label class="form-check-label" for="inlineRadio1">Date</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="radiodueDate" id="inlineRadio2" value="dueDate" checked>
                                            <label class="form-check-label" for="inlineRadio2">Due Date</label>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="form-group mb-0 mr-2">
                                            <label for="" class="text-secondary">Start date</label>
                                            <input type="date" mwlflatpickr="" placeholder="" aria-describedby="helpId" class="form-control input-date ng-untouched ng-pristine ng-valid flatpickr-input">
                                        </div>
                                        <div class="form-group p-l-10">
                                            <label for="" class="text-secondary">Date fin</label>
                                            <input type="date" name="" id="" mwlflatpickr="" placeholder="" aria-describedby="helpId" class="form-control input-date ng-untouched ng-pristine ng-valid flatpickr-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex p-2 flex-column">
                                    <label for="" class="text-secondary">
                                        Method of payment
                                    </label>
                                    <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input ng-untouched ng-pristine ng-valid" type="radio" name="radio1" id="radioinline2" value="in" checked>
                                            <label class="form-check-label" for="inlineRadio1">Receipts</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input ng-untouched ng-pristine ng-valid" type="radio" name="radio1" id="radioinline2" value="out">
                                            <label class="form-check-label" for="inlineRadio2">Issued</label>
                                        </div>
                                    </div>
                                    <label for="" class="text-secondary">Payment</label>
                                    <div class="form-group p-2 m-checkbox-inline mb-0 custom-radio-ml">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input ng-untouched ng-pristine ng-valid" type="radio" name="all" id="t1" value="all" checked>
                                            <label class="form-check-label" for="inlineRadio1">All</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input ng-untouched ng-pristine ng-valid" type="radio" name="retard" id="t2" value="true">
                                            <label class="form-check-label" for="inlineRadio2">in delay</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex p-2 flex-column">
                                    <label for="" class="text-secondary">Type of payment</label>
                                    <div class="form-group mb-0">
                                        <select class="form-control" name="payment" id="payment">
                                            <option value="check">check</option>
                                            <option value="treaty">Treaty</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Type de transaction</label>
                                        <select class="form-control" name="transaction" id="transaction">
                                            <option value="all">All</option>
                                            <option value="paid">Paid</option>
                                            <option value="paid">Unpaid</option>
                                            <option value="in_progress">In progress</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="d-flex p-2 flex-column">
                                    <div class="form-group mb-0">
                                        <label for="" class="text-secondary">Choose account</label>
                                        <select class="form-control" name="transaction" id="transaction">
                                            <option disabled>Select account</option>
                                        </select>  
                                    </div>
                                    <div class="form-group p">
                                        <label for="" class="text-secondary">Keyword</label>
                                        <input type="text" name="regelement" id="" placeholder="" aria-describedby="helpId" class="form-control input-filtre ng-untouched ng-pristine ng-valid">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex p-2 justify-content-end">
                                <div class="d-flex flex-wrap p-2">
                                    <button type="button" name="" id="" class="btn btn-outline-primary btn-lg btn-block float-left" style="font-family: Arial; font-size: 14px; text-align: center;">Reset</button>
                                </div>
                                <div class="d-flex flex-wrap p-2">
                                    <button type="button" name="" id="" class="btn btn-primary btn-lg btn-block float-left" style="color: #fff; font-family: Arial; font-size: 14px; text-align: center;">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h3>{{trans('file.Cheque And Draft')}}</h3> --}}
                        {{-- <button class="btn btn-success"><i class="fa fa-plus"></i>abc</button> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="purchase-table" class="table purchase-list" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="not-exported"></th>
                                        <th>{{trans('file.NUMBER')}}</th>
                                        <th>{{trans('file.CONTACT')}}</th>
                                        <th>{{trans('file.CARRIER')}}</th>
                                        <th>{{trans('file.BANQUE')}}</th>
                                        <th>{{trans('file.SETTLEMENT DATE')}}</th>
                                        <th>{{trans('file.DUE DATE')}}</th>
                                        <th>{{trans('file.AMOUNT')}}</th>
                                        <th>{{trans('file.ÉTAT')}}</th>
                                        <th class="not-exported">{{trans('file.action')}}</th>
                                    </tr>
                                </thead>
                    
                                <tfoot class="tfoot active">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        
    </div>
</section>

@endsection

@push('scripts')
<script type="text/javascript">

$('#purchase-table').DataTable( {
            // "processing": true,
            // "serverSide": true,
        } );
    $('.primary-details').click(function() {
        console.log('here');
        window.location = '/';
    });
    $('.secoundry-details').click(function() {
        console.log('here');
        window.location = '/';
    });
</script>
@endpush
