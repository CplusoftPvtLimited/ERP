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
                                <button class="tablinks" onclick="switchTab(event, 'Articles')" id="defaultOpen">Product</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleCrteria')" id ="criteriaTab">Product Criteria</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleCrosses')">Product Crosses</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleDocs')">Product Docs</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleEan')">Product Ean</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleLinks')">Product Links</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleMain')">Product Main</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticlePdfs')">Product Pdfs</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleVT')">Product Vehicle Trees</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleText')">Product Text</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleGeneric')">Generic Product</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleGGroup')">Generic Product Groups</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleNew')">New Product</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleRBy')">Replaced By Product</button>
                                <button class="tablinks" onclick="switchTab(event, 'ArticleReplaces')">Replaces Product</button>
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
                                                    <h6>Manufacturers *</h6>
                                                    <select name="mfrId" id="mfrId" class="form-control">
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->manuId }}">
                                                                {{ $manufacturer->manuName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Supplier *</h6>
                                                    <select name="dataSupplierId" id="dataSupplierId" class="form-control">
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->brandId }}">
                                                                {{ $supplier->brandName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Sections *</h6>
                                                    <select name="assemblyGroupNodeId" id="assemblyGroupNodeId" class="form-control">
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
                                                <h6>Product Number *</h6>
                                                <input type="number" name="articleNumber"  id="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity per Package</h6>
                                                    <input type="number" name="quantityPerPackage" id="quantityPerPackage" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Quantity/Package/Package</h6>
                                                    <input type="number" id="quantityPerPartPerPackage" name="quantityPerPartPerPackage"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Additional Description</h6>
                                                <textarea name="additionalDescription" id="additionalDescription" cols="10" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Generic Product Description</h6>
                                                    <textarea name="genericArticleDescription" id="genericArticleDescription" cols="10" rows="10" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-success" style="width:100px">Save</button>
                                            <button type="button" class="btn btn-primary mr-2" style="width:100px" id="nxtProduct">Next</button>
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
                                    <h4>{{ trans('file.Add Product Criteria') }}</h4>
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
                            <form action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="other_data"></div>
                                        <div class="row">
                                            
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Criteria Type</h6>
                                                    <input type="text" name="articleNumber" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                    <h6>Value Key Id</h6>
                                                    <input type="text" name="articleNumber" class="form-control" required>
                                            </div>
                                            <div class="col-4">
                                                <h6>Successor Criteria</h6>
                                                <input type="text" name="articleNumber" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Assembly Group Node</h6>
                                                    <input type="text" name="articleNumber" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Article Number</h6>
                                                    <input type="text" name="articleNumber" id ="criteria_articleId" class="form-control" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Criteria Description</h6>
                                                    <textarea name="additionalDescription" id="" cols="10" rows="5 " class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Criteria Abbr. Description</h6>
                                                    <textarea name="additionalDescription" id="" cols="10" rows="5" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <h6>Criteria Unit Description</h6>
                                                <textarea name="additionalDescription" id="" cols="10" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <h6>Raw Value</h6>
                                                    <textarea name="additionalDescription" id="" cols="10" rows="5" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group pt-3 pl-3">
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault"><h6>Is Interval</h6></label>
                                                    <br>
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault"><h6>Is Mandatory</h6></label>
                                                    <br>
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault"><h6>Immediate Display</h6></label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group pt-3 pl-4">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault"><h6>Is Mandatory</h6></label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group pt-3 pl-4">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault"><h6>Immediate Display</h6></label>
                                                </div>
                                            </div>
                                            
                                        </div>


                                        <div class="d-flex flex-row-reverse">
                                            <button type="submit" class="btn btn-primary" style="width:100px">Save</button>
                                            <button type="button" class="btn btn-primary mr-2" style="width:100px" id="nxtCriteria">Next</button>
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
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <script>
            $(document).ready(function(){
                $('#nxtProduct').on('click', function(){
                    
                    var mfrId = $('#mfrId').val();
                    var dataSupplierId = $('#dataSupplierId').val();
                    var assemblyGroupNodeId = $('#assemblyGroupNodeId').val();
                    var articleNumber = $('#articleNumber').val();
                    var quantityPerPackage = $('#quantityPerPackage').val();
                    var quantityPerPartPerPackage = $('#quantityPerPartPerPackage').val();
                    var additionalDescription = $('#additionalDescription').val();
                    var genericArticleDescription = $('#genericArticleDescription').val();
                    var ajax = 1;
                    
                    console.log(articleNumber);
                    if(mfrId != "" && dataSupplierId != "" &&  assemblyGroupNodeId != "" && articleNumber != ""){
                    $.ajax({
                        url: "{{ route('article.store') }}",
                        type: "POST",
                        
                        data:{
                            mfrId : mfrId,
                            dataSupplierId : dataSupplierId,
                            assemblyGroupNodeId : assemblyGroupNodeId,
                            articleNumber : articleNumber,
                            quantityPerPackage : quantityPerPackage,
                            quantityPerPartPerPackage : quantityPerPartPerPackage,
                            additionalDescription : additionalDescription,
                            genericArticleDescription : genericArticleDescription,
                            ajax : ajax,
                            "_token": "{{ csrf_token() }}",
                        },

                        success:function(response){
                            if(response.data.id){
                                Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                    });
                                var legacy_id = response.data.legacyArticleId;
                                var product_name = response.data;
                                $('#criteria_articleId').val(response.data.articleNumber)
                                if(legacy_id !=null)
                                {
                                    document.getElementById('Articles').style.display = "none";
                                    var tablinks = document.getElementsByClassName("tablinks");
                                    for (i = 0; i < tablinks.length; i++) {
                                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                                    }
                                    document.getElementById("defaultOpen").disabled = true;
                                    var tablink = document.getElementById("criteriaTab");
                                    tablink.className = tablink.className += " active"
                                    document.getElementById('ArticleCrteria').style.display = "block";
                                    // event.currentTarget.className += " active";
                                }
                                // console.log(response.data.id) //Message come from controller
                            }else{
                                Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                                });
                            }
                        },
                        error:function(error){
                            Swal.fire({
                                title: 'Error',
                                text: "Something Went Wrong",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                                });
                        }
                    });
                }else{
                    Swal.fire({
                    title: 'Error',
                    text: "Please Fill Out the Required Fields",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                    });
                }
                });
                $('#nxtCriteria').on('click',function(){
                    var articleNumber = $('#criteria_articleId').val();
                    if(articleNumber == ""){
                        Swal.fire({
                    title: 'Error',
                    text: "Please Add a Product First",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                    });
                    }
                });
            });
        </script>

    </section>
@endsection
