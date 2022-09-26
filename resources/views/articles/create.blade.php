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
                <div class="card p-0">
                <div class="row">
                <div class="col-3" style="margin: 0px; padding:0px;">
                    <div class="card" style="margin: 0px; padding:0px;">
                        <div class="card-body" style="margin: 0px;">
                            <div class="tab article-tabs">
                                <button class="tablinks nav-link" onclick="switchTab(event, 'Articles')" id="defaultOpen">Articles</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleCrteria')">Article Criteria</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleCrosses')">Article Crosses</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleDocs')">Article Docs</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleEan')">Article Ean</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleLinks')">Article Links</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleMain')">Article Main</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticlePdfs')">Article Pdfs</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleVT')">Article Vehicle Trees</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleText')">Article Text</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleGeneric')">Generic Articles</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleGGroup')">Generic Article Groups</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleNew')">New Articles</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleRBy')">Replaced By Articles</button>
                                <button class="tablinks nav-link" onclick="switchTab(event, 'ArticleReplaces')">Replaces Articles</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9" style="margin: 0px; padding:0px;">
                    <div class="card tabcontent"  id="Articles" style="margin: 0px; height:100%;">
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
                        <div class="card-body" style="margin: 0px;">
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleCrteria" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Crteria') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleCrosses" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Crosses') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleDocs" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Docs') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleEan" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Ean') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleLinks" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Links') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleMain" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Main') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticlePdfs" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Pdfs') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleVT" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Vehicle Tree') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleText" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article Text') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleGeneric" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Generic Article') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleGGroup" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Generic Article Group') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleNew" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Article New') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleRBy" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Replaced By Article') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card tabcontent" id="ArticleReplaces" style="margin: 0px; height:100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ trans('file.Add Replaces Article') }}</h4>
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
                            <form action="{{ route('article.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Manufacturers</h6>
                                                    <select name="mfrId" id="" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
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
                                                            <option value="{{ $supplier->brandId }}">
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
                                                            <option value="{{ $section->assemblyGroupNodeId }}">
                                                                {{ $section->assemblyGroupName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Product Number</h6>
                                                <input type="number" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
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
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            function switchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Get the element with id="defaultOpen" and click on it
            document.getElementById("defaultOpen").click();
        </script>

    </section>
@endsection
