<div class="card">
    <div class="card-header d-flex align-items-center">
        <h4><b>{{ trans('file.Add Purchase') }}</b></h4>
    </div>

    <div class="card-body">
        {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ trans('file.Date') }}</label>
                            <input type="text" id="product_purchase_date" name="created_at" class="form-control date"
                                placeholder="Choose date" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ trans('file.After Markit Supplier') }}</label>
                            <select name="supplier_id" id="supplier_id" data-href="#" class="selectpicker form-control"
                                data-live-search="true" data-live-search-style="begins" title="Select supplier...">
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ trans('file.Cash Type') }}</label>
                            <select name="status" id="cash_type" class="form-control">
                                <option value="white">{{ trans('file.White Cash') }}</option>
                                <option value="black">{{ trans('file.Black Cash') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ trans('file.Purchase Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="received">{{ trans('file.Recieved') }}</option>
                                <option value="ordered">{{ trans('file.Ordered') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ trans('file.Attach Document') }}</label> <i class="dripicons-question"
                                data-toggle="tooltip"
                                title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                            <input type="file" name="document" class="form-control">
                            @if ($errors->has('extension'))
                                <span>
                                    <strong>{{ $errors->first('extension') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-4">
                            <label>{{ trans('file.Additional Cost') }}</label>
                            <input type="number" name="purchase_additional_cost" value="0"
                                id="purchase_additional_cost" onkeyup="calculateSalePrice()" class="form-control"
                                min="0" max="100000000">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>