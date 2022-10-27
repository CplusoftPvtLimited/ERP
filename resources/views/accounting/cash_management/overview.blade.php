<div class="card-body m-0">
    @php
        $date = Carbon\Carbon::now();
        $current_date = $date->format('Y-m-d');
        // dd($current_date);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-6 pl-4 pt-1">
                <h2>{{ trans('file.Over View') }}</h2>
            </div>
            <div class="col-6 mb-1 mt-0 pt-0">
                <button data-toggle="modal" data-target="#createModal" class="btn btn-info float-right">+ Add
                    Expense</button>
            </div>

        </div>
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
            <table id="data-table" class="table" style="width: 100% !important">
                <thead class="article_view_tr_head">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Recovery</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Cash</td>
                        <td class="{{ $cash_revenue >= 0 ? 'text-success' : 'text-danger' }}"><strong>TND
                                {{ $cash_revenue }}</strong></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Cheque</td>
                        <td class="{{ $cheque_revenue >= 0 ? 'text-success' : 'text-danger' }}"><strong>TND
                                {{ $cheque_revenue }}</strong></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Draft</td>
                        <td class="{{ $draft_revenue >= 0 ? 'text-success' : 'text-danger' }}"><strong>TND
                                {{ $draft_revenue }}</strong></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Withholding</td>
                        <td class="{{ $withholding_revenue >= 0 ? 'text-success' : 'text-danger' }}"><strong>TND
                                {{ $withholding_revenue }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>Total = <span
                                    class="{{ $withholding_revenue + $draft_revenue + $cash_revenue + $cheque_revenue >= 0 ? 'text-success' : 'text-danger' }}">TND
                                    {{ $withholding_revenue + $draft_revenue + $cash_revenue + $cheque_revenue }}</span></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header article_view_tr_head">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add an Expense') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body pr-5 pl-5">
                <p class="italic">
                    <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                </p>
                <form action="{{ route('balanceSheet.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Mode Payment') }} *</label>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <input type="radio" id="cash" name="mode_payment" checked="checked"
                                    value="cash" class="overview_mode_payment">
                                <label for="html">Cash</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="radio" id="check" name="mode_payment" value="cheque"
                                    class="overview_mode_payment">
                                <label for="css">Cheque</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Category') }}*</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <select name="category_id" id="overview_category_id" class="form-control">
                                    <option value="">--Select One--</option>
                                    @foreach ($bal_categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Amount') }}*</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text">TND</div>
                                    <input type="number" name="amount" id="overview_amount" step="any"
                                        maxlength="191" min="0.1" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Supplier') }}</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">--Select One--</option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="checknumber">
                        <div class="col-3">
                            <label>{{ trans('file.Cheque Number') }} *</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <input type="text" maxlength="20" name="cheque_number" id="overview_cheque_number"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="source">
                        <div class="col-3">
                            <label>{{ trans('file.Account Source') }} *</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <select name="account_source" id="overview_account_source" class="form-control">
                                    <option value="">--Select One--</option>
                                    @foreach ($source_accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->account_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Settelement Date') }}*</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <input type="date" name="settlement_date" id="overview_settlement_date"
                                    max={{ $current_date }} class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="duedate">
                        <div class="col-3">
                            <label>{{ trans('file.Due Date') }}</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <input type="date" name="due_date" id="overview_due_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>{{ trans('file.Note') }}</label>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <textarea name="note" id="overview_note" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="transaction_type" value="debit">
                    <input type="hidden" name="balance_type" value="{{ $balance_type }}">

                    <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        var type = $('.overview_mode_payment:checked').val();
        document.getElementById("duedate").style.display = "none";
        document.getElementById("checknumber").style.display = "none";
        document.getElementById("source").style.display = "none";
        $('.overview_mode_payment').on("click", function() {
            console.log('jdhjhd' + type);
            var type = $('.overview_mode_payment:checked').val();
            console.log(type);
            if (type == "cash") {
                document.getElementById("duedate").style.display = "none";
                document.getElementById("checknumber").style.display = "none";
                document.getElementById("source").style.display = "none";
            }
            if (type == "cheque") {
                document.getElementById("duedate").style.display = "";
                document.getElementById("checknumber").style.display = "";
                document.getElementById("source").style.display = "";
            }
        });
    })
</script>
