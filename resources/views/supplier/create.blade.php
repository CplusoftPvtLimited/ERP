@extends('layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Add Supplier') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            {!! Form::open(['route' => 'supplier.store', 'method' => 'post', 'files' => true]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.name') }} *</strong> </label>
                                        <input type="text" name="name" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Email') }} *</label>
                                        <input type="email" name="email" placeholder="example@example.com" required
                                            class="form-control">
                                        @if ($errors->has('email'))
                                            <span>
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {{-- <div class="form-group">
                                    <label>{{trans('file.Image')}}</label>
                                    <input type="file" name="image" class="form-control">
                                    @if ($errors->has('image'))
                                   <span>
                                       <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                </div> --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Image')}}</label>
                                        <input type="file" name="image" class="form-control">
                                        @if ($errors->has('image'))
                                       <span>
                                           <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Shop Name') }} *</label>
                                        <input type="text" name="shop_name" required class="form-control">
                                        @if ($errors->has('shop_name'))
                                            <span>
                                                <strong>{{ $errors->first('shop_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.VAT Number') }}</label>
                                        <input type="text" name="vat_number" class="form-control">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Email') }} *</label>
                                        <input type="email" name="email" placeholder="example@example.com" required
                                            class="form-control">
                                        @if ($errors->has('email'))
                                            <span>
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Phone Number') }} *</label>
                                        <input type="text" name="phone" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Address') }} *</label>
                                        <input type="text" name="address" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.City') }} *</label>
                                        <input type="text" name="city" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>{{ trans('file.Country') }}</label>
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Postal Code') }}</label>
                                        <input type="text" name="postal_code" class="form-control">
                                    </div>
                                </div> --}}
                                
                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
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
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Shop Name</th>
                                            <th>Phone No</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Country</th>
                                            <th class="not-exported">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="tfoot active">
                                        @foreach ($suppliers as $item => $supplier)
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
                                        @endforeach
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
        $("ul#people").siblings('a').attr('aria-expanded', 'true');
        $("ul#people").addClass("show");
        $("ul#people #supplier-create-menu").addClass("active");
    </script>
@endpush
