<div class="card-body m-0">
    <div class="container ">
        {{-- <div class="row ">
            <div class="col">
                <a href="" class="btn float-right"><i
                    class="fa fa-filter"></i></a>
            </div>
        </div> --}}
        <div class="table-responsive">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                        data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>{{ session()->get('message') }}
                </div>
            @endif
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col pl-4 pt-1">
                    <h2>{{ trans('file.Regulation') }}</h2>
                </div>
            </div>
            <table id="regulation-data-table" class="table table-striped" style="width: 100% !important">
                <thead style="border-bottom: 3px solid white;" class="article_view_tr_head">
                    <tr>
                        <th>Date</th>
                        <th>Label</th>
                        <th>Payment Mode</th>
                        <th>Debit/Expense</th>
                        <th>Credit/Recipe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($regulations as $item)
                        <tr>
                            <td>{{ substr($item->settlement_date, 0, 10) }}</td>
                            <td>{{ $item->balanceCategory->category }}</td>
                            <td>{{ $item->mode_payment }}</td>
                            <td style="color: red">
                                 {{ $item->transaction_type == 'debit' ? ' - ' . $item->amount . " TND " : '--' }}
                            </td>
                            <td style="color: green">
                                 {{ ($item->transaction_type == 'credit') ? ' + ' . $item->amount . " TND " : '--' }}</td>
                            <td><a href="{{ route('cash.managemnt.regulation', $item->id) }}"
                                    class="btn btn-primary text-white"><i class="fa fa-eye"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#regulation-data-table').DataTable({
            searching: false,
            ordering: false
        });
    });
</script>
