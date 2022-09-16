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
                                    <h4>{{ trans('file.Add Product') }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('article.index') }}" class="btn btn-primary float-right">Back</a>
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
                            <form action="{{ route('article.update',$article->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}" {{ $manufacturer->manuId == $article->mfrId ? 'selected' : ''}}>
                                                                {{ $manufacturer->manuName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="dataSupplierId" id="" class="form-control">
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->brandId }}" {{ $supplier->brandId == $article->dataSupplierId ? 'selected' : ''}}>
                                                                {{ $supplier->brandName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Sections</h6>
                                                    <select name="assemblyGroupNodeId" id="" class="form-control">
                                                        @foreach ($sections as $section)
                                                            <option value="{{ $section->assemblyGroupNodeId }}" {{ $section->assemblyGroupNodeId == $article->assemblyGroupNodeId ? 'selected' : ''}}>
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" value="{{ $article->articleNumber }}" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" value="{{ $article->quantityPerPackage }}"  class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage" value="{{ $article->quantityPerPartPerPackage }}"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control">{{ $article->additionalDescription }}</textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control">{{ $article->genericArticleDescription }}</textarea>
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

    </section>
@endsection
