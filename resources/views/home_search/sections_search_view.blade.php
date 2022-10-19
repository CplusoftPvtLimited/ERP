@extends('layout.main')
@section('content')
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
                        <div class="row m-0">
                            <div class="col-3" style="margin: 0px; padding:0px; ">
                                <div class="card" style="margin: 0px; padding:0px; height: 100%; box-shadow: none !important;">
                                    <div class="card-body" style="margin: 0px; padding:0px;">
                                        @include('home_search.sidepanel')
                                    </div>
                                </div>
                            </div>
                            <div class="col-9" style="margin: 0px; padding:0px;">
                                <div class="card p-3" style="margin: 0px; height:100%;box-shadow: none !important;border-left:1px solid  rgb(240, 240, 240)">
                                    <div class="row">
                                        @if(count($sections))
                                        @foreach ($sections as $section)
                                        <div class="col-md-4">
                                            <div class="card" style="box-shadow: none !important; border:1px solid rgb(240, 240, 240)">
                                                <div class="card-header">
                                                    <h6>{{ $section['section']->assemblyGroupName }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    
                                                    <ul class="">
                                                        @if(count($section['sub_sections']) > 0)
                                                        @foreach ($section['sub_sections'] as $sub_section)
                                                        <li class="">
                                                            <form action="{{route('articles_search_view')}}" enctype="multipart/form-data" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="type" value="{{ $type }}">
                                                                <input type="hidden" name="sub_type" value="{{ $sub_type }}">
                                                                <input type="hidden" name="model_year" value="{{ $model_year }}">
                                                                <input type="hidden" name="cc" value="{{ $cc }}">
                                                                <input type="hidden" name="fuel" value="{{ $fuel }}">
                                                                <input type="hidden" name="engine" value="{{ json_encode($engine) }}">
                                                                <input type="hidden" name="section" value="{{ $sub_section->assemblyGroupNodeId }}">
                                                                <button type="submit" style="background-color: transparent;border:none">{{ $sub_section->assemblyGroupName }}</button>
                                                            </form>
                                                            <a href=""></a>
                                                        </li>
                                                        @endforeach
                                                        
                                                        @else
                                                        <p class="text-center" style="color: red">No Sub Sections Available</p>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <p class="text-center" style="color: red">No Sections Available</p>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
