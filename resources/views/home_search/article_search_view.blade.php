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
                            @if(isset($dual) && $dual == "dual")
                            <div class="col-10" style="margin: 0px; padding:0px;">
                                <div class="card pr-3 pl-3 pt-0" style="box-shadow: none !important; border-left: 1px solid rgb(240, 240, 240); border-right: 1px solid rgb(240, 240, 240)">
                                    {{-- <div class="row bg-secondary pt-2 mt-0 text-white">
                                        <div class="col-6">
                                            <h6>
                                                Article Name
                                            </h6>
                                        </div>
                                        
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-12 pl-0 pr-0">
                                            <div class="">
                                                <table class="table-responsive">
                                                    <thead class="article_view_tr_head">
                                                        <tr class="article_table_row">
                                                            <th style="width: 30%">Article Number</th>
                                                            <th>Image</th>
                                                            <th style="width: 50%" class="text-center">Description</th>
                                                            {{-- <th>Article Status</th> --}}
                                                            <th style="width: 100%">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-12 pl-0 pr-0">
                                            <div class="">
                                                <table class="table-responsive">
                                                    <tbody class="article_search_view_scroll">
                                                        @if(count($section_parts) > 0)
                                                        @foreach ($section_parts as $section_part)
                                                        <tr class="article_table_row article_table_row_style">
                                                            <td style="width: 30%">
                                                                <b>{{$section_part->articleNumber}}</b>
                                                            </td>
                                                            <td>
                                                                <img src="{{ asset('images/part_small.jpg')}}" alt="">
                                                            </td>
                                                            <td style="width: 50%" class="text-center">
                                                                {{$section_part->genericArticleDescription}}
                                                            </td>
                                                            
                                                            <td style="width: 100%">
                                                                <a class="btn btn-primary text-white" href="{{route('articles_view',[$section_part->legacyArticleId,$engine->linkageTargetId,$section_id])}}"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        
                                                        @else
                                                        <p class="text-center" style="color: red">No Products Available</p>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-3" style="margin: 0px; padding:0px; ">
                                <div class="card" style="margin: 0px; padding:0px; height: 100%;">
                                    <div class="card-body" style="margin: 0px; padding:0px;">
                                        @include('home_search.sidepanel')
                                    </div>
                                </div>
                            </div>
                            <div class="col-7" style="margin: 0px; padding:0px;">
                                <div class="card pr-3 pl-3 pt-0" style="box-shadow: none !important; border-left: 1px solid rgb(240, 240, 240); border-right: 1px solid rgb(240, 240, 240)">
                                    {{-- <div class="row bg-secondary pt-2 mt-0 text-white">
                                        <div class="col-6">
                                            <h6>
                                                Article Name
                                            </h6>
                                        </div>
                                        
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-12 pl-0 pr-0">
                                            <div class="">
                                                <table class="table-responsive">
                                                    <thead class="article_view_tr_head">
                                                        <tr class="article_table_row">
                                                            <th style="width: 30%">Article Number</th>
                                                            <th>Image</th>
                                                            <th style="width: 50%" class="text-center">Description</th>
                                                            {{-- <th>Article Status</th> --}}
                                                            <th style="width: 100%">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-12 pl-0 pr-0">
                                            <div class="">
                                                <table class="table-responsive">
                                                    <tbody class="article_search_view_scroll">
                                                        @if(count($section_parts) > 0)
                                                        @foreach ($section_parts as $section_part)
                                                        <tr class="article_table_row article_table_row_style">
                                                            <td style="width: 30%">
                                                                <b>{{$section_part->articleNumber}}</b>
                                                            </td>
                                                            <td>
                                                                <img src="{{ asset('/images/part_small.jpg')}}" alt="">
                                                            </td>
                                                            <td style="width: 50%" class="text-center">
                                                                {{$section_part->genericArticleDescription}}
                                                            </td>
                                                            
                                                            <td style="width: 100%">
                                                                <a class="btn btn-primary text-white" href="{{route('articles_view',[$section_part->legacyArticleId,$engine->linkageTargetId,$section_id])}}"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        
                                                        @else
                                                        <p class="text-center" style="color: red">No Products Available</p>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-2" style="margin: 0px; padding:0px;">
                                <div class="card" style="margin: 0px; padding:0px; box-shadow: none; ">
                                    <div class="card-body" style="margin: 0px; padding:0px;">
                                        @include('home_search.criteria_search')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
         $(document).ready(function() {
            // $('#search_home').on('click', function(e) {

            //     e.preventDefault();

                if ($(window).outerWidth() > 1199) {
                    $('nav.side-navbar').toggleClass('shrink');
                    $('.page').toggleClass('active');
                    // window.location.href = "/home_search";
                }
            // });
        })
    </script>
@endsection
