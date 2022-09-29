@extends('layout.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
@section('content')
@if (session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if (session()->has('not_permitted'))
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
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
                                <div class="card-body" style="margin: 0px;">
                                    <div class="tab article-tabs">
                                        <button class="tablinks" onclick="switchTab(event, 'Articles')" id="defaultOpen">Product</button>
                                        <button class="tablinks" onclick="switchTab(event, 'ArticleCrteria')" id="criteriaTab">Product Criteria</button>
                                        <button class="tablinks" onclick="switchTab(event, 'ArticleCrosses')" id="crossesTab">Product Crosses</button>
                                        <button class="tablinks" onclick="switchTab(event, 'ArticleEan')" id="eanTab">Product Ean</button>
                                        <button class="tablinks" onclick="switchTab(event, 'ArticleLinks')" id="linkstab">Product Links</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9" style="margin: 0px; padding:0px;">
                            <div class="card tabcontent" id="Articles" style="margin: 0px; height:100%;">
                                @include('articles.tabs.articles')
                            </div>
                            <div class="card tabcontent" id="ArticleCrteria" style="margin: 0px; height:100%;">
                                @include('articles.tabs.article_criteria')
                            </div>
                            <div class="card tabcontent" id="ArticleCrosses" style="margin: 0px; height:100%;">
                                @include('articles.tabs.article_crosses')
                            </div>
                            <div class="card tabcontent" id="ArticleEan" style="margin: 0px; height:100%;">
                                @include('articles.tabs.article_ean')
                            </div>
                            <div class="card tabcontent" id="ArticleLinks" style="margin: 0px; height:100%;">
                                @include('articles.tabs.article_links')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
</section>
@endsection