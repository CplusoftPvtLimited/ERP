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
                                <div class="card" style="margin: 0px; padding:0px; height: 100%;">
                                    <div class="card-body" style="margin: 0px; padding:0px;">
                                        @include('purchase.sidepanel')
                                    </div>
                                </div>
                            </div>
                            <div class="col-9" style="margin: 0px; padding:0px;">
                                <div class="card p-3" style="margin: 0px; height:100%;">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>Graphic Search</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="">
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 1</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="{{route('articles_serch_view')}}">link 2</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
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
