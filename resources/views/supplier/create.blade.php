@extends('layout.main') 
@section('content')
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
                                        <input type="text" name="name" id="name" required class="form-control">
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
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Image')}}</label>
                                        <input type="file" name="image" class="form-control">
                                        @if ($errors->has('image'))
                                       <span>
                                           <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div> --}}
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
            
        </div>
    </section>
    <section class="form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  d-flex align-items-center">
                            After Market suppliers
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="purchase-table" class="table purchase-list" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            {{-- <th>Image</th> --}}
                                            <th>Phone Number</th>
                                            <th>Shop Name</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Country</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit_supplier" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    {!! Form::open(['route' => 'supplier.update', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <h3>Edit supplier</h3>
                        <h5 class="modal-title" id="exampleModalLabel">
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="supplier-id" id="supplier_id" value="">
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Name</label>
                                <input type="text"
                                    class="form-control" name="supplier-name" id="supplier-name"
                                    value="">
                            </div>
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Email</label>
                                <input type="text"
                                    class="form-control" id="supplier-email" name="supplier-email"
                                    value=""
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Shop Name</label>
                                <input type="text"
                                    class="form-control" id="supplier-shop_name" name="supplier-shop_name"
                                    value="">
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Phone Number
                                    Price</label>
                                <input type="text"
                                    class="form-control" id="supplier-phone" name="supplier-phone"
                                    value="">
                            </div>
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Address
                                    Price</label>
                                <input type="text"
                                    class="form-control" id="supplier-address" name="supplier-address"
                                    value="">
                            </div>
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">City
                                    Details</label>
                                <input type="text"
                                    class="form-control" id="supplier-city" name="supplier-city"
                                    value="">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="" class="view-edit-purchase">Country
                                    Details</label>
                                <input type="text"
                                    class="form-control" id="supplier-country" name="supplier-country"
                                    value="">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    {!! Form::close() !!}
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

<script type="text/javascript">

    $(document).ready(function() {
        $('#name').val('12');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#purchase-table').DataTable({
            "ordering": true,
            "processing": true,
            "serverside": true,
            ajax: "{{ route('supplier.create') }}",
            columns: [
                {
                    "data": "name",
                    name: 'name'
                },
                {
                    "data": "email",
                    name: 'email'
                },
                {
                    "data": "phone",
                    name: 'phone'
                },
                {
                    "data": "shop_name",
                    name: 'shop_name'
                },
                {
                    "data": "address",
                    name: 'address'
                },
                {
                    "data": "city",
                    name: 'city'
                },
                {
                    "data": "country",
                    name: 'country'
                },
                // {
                //     "data": 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // }
            ],
        });
        setInterval(() => {
            $('.edit_supplier').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let shop_name = $(this).data('shop_name');
                let address = $(this).data('address');
                let city = $(this).data('city');
                let country = $(this).data('country');
                $('#supplier_id').val(id);
                $('#supplier-name').val(name);
                $('#supplier-email').val(email);
                $('#supplier-phone').val(phone);
                $('#supplier-shop_name').val(shop_name);
                $('#supplier-address').val(address);
                $('#supplier-city').val(city);
                $('#supplier-country').val(country);
                $('#edit_supplier').modal('show');
        });
        }, 1000);
       
    });
</script>
@endpush
