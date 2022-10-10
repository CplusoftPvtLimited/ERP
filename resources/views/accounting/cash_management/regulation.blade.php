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
            <table id="data-table" class="table table-striped" style="width: 100% !important">
                <thead style="border-bottom: 3px solid white;">
                    <tr>
                        <th>#</th>
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
                        <td>{{++$i}}</td>
                        <td>{{substr($item->settlement_date, 0, 10)}}</td>
                        <td>{{$item->balanceCategory->category}}</td>
                        <td>{{$item->mode_payment}}</td>
                        <td style="color: red">{{$item->transaction_type == "debit" ? "- ".$item->amount : "---"}}</td>
                        <td style="color: green">{{$item->transaction_type == "credit" ? "+ ".$item->amount : "---"}}</td>
                        <td><button class= "btn btn-primary"><i class="fa fa-eye"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
    })
</script>