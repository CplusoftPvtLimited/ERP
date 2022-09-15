@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Engine') }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('engine.index') }}" class="btn btn-primary float-right">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('error'))
                                <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('engine.update',$engine->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Capacity (cc)</h6>
                                                    <input type="number" min="0" name="capacityCC" class="form-control" required
                                                        value="{{ $engine->capacityCC }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Capacity (liters)</h6>
                                                    <input type="number" min="0" name="capacityLiters" class="form-control" required
                                                        value="{{ $engine->capacityLiters }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Code</h6>
                                                    <input type="text" name="code" class="form-control" required
                                                        value="{{ $engine->code }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Kilowatt From</h6>
                                                    <input type="number" min="0" name="kiloWattsFrom" class="form-control" required
                                                        value="{{ $engine->kiloWattsFrom }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Kilowatt To</h6>
                                                    <input type="number" min="0" name="kiloWattsTo" class="form-control" required
                                                        value="{{ $engine->kiloWattsTo }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Horsepower To</h6>
                                                    <input type="number" min="0" name="horsePowerTo" class="form-control" required
                                                        value="{{ $engine->horsePowerTo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Horsepower From</h6>
                                                    <input type="number" min="0" name="horsePowerFrom" class="form-control" required
                                                        value="{{ $engine->horsePowerFrom }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Engine Type</h6>
                                                    <input type="text" name="engineType" class="form-control" required
                                                        value="{{ $engine->engineType }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Linkage Target Type</h6>
                                                    <select name="mfrId" id="" class="selectpicker form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}" {{ $engine->mfrId == $manufacturer->manuId ? 'selected' : '' }}>{{ $manufacturer->manuName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Linkage Target Type</h6>
                                                    <select name="linkageTargetType" id="linkageTarget" class="selectpicker form-control">

                                                        <option>Select Type</option>
                                                        <option value="P" {{ $engine->linkageTargetType == "P" ? 'selected' : '' }}>P</option>
                                                        <option value="O" {{ $engine->linkageTargetType == "O" ? 'selected' : '' }}>O</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Sub-Linkage Target Type</h6>
                                                    <select name="subLinkageTargetType" id="subLinkageTarget" class="selectpicker form-control">
                                                        <option value="-2">Select One</option>
                                                        <option value="{{ $engine->subLinkageTargetType }}" selected>{{ $engine->subLinkageTargetType }} (selected)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <h6>Engine Description</h6>
                                                    <textarea name="description" id="" class="form-control" required cols="30" rows="10">{{ $engine->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

            <script>
                $('#linkageTarget').on('change', function() {
                    var val = this.value;
                    
                    if(val == "P"){
                        $('#subLinkageTarget').empty();
                        $('#subLinkageTarget').append(`<option value="V">
                                       V
                                  </option><option value="L">
                                       L
                                  </option><option value="B">
                                       B
                                  </option>`);
                                  $('.selectpicker').selectpicker('refresh');
                    }else if(val == "O"){
                        $('#subLinkageTarget').empty();
                        $('#subLinkageTarget').append(`<option value="C">
                                       C
                                  </option><option value="T">
                                       T
                                  </option><option value="M">
                                       M
                                  </option><option value="A">
                                       A
                                  </option><option value="K">
                                       K
                                  </option>`);
                                  $('.selectpicker').selectpicker('refresh');
                    }else{
                        $('#subLinkageTarget').empty();
                        $('.selectpicker').selectpicker('refresh');
                    }
                    
                });
            </script>
    </section>
@endsection
