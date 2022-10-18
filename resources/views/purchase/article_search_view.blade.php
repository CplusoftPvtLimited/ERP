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
                                <div class="card pr-3 pl-3 pt-0" style="margin: 0px; height:100%;">
                                    <div class="row bg-secondary pt-2 mt-0 text-white">
                                        <div class="col-6">
                                            <h6>
                                                Article Name
                                            </h6>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h6>
                                                Results 0-0 of 0
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 pl-0 pr-0">
                                            <div class="table">
                                                <table class="table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Article Number</th>
                                                            <th>Image</th>
                                                            <th style="width: 50%" class="text-center">Description</th>
                                                            <th>Article Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <img src="images/logo.png" width="100px" alt="">
                                                            </td>
                                                            <td style="width: 50%" class="text-center">
                                                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam at esse repellendus labore, sit, soluta nobis obcaecati necessitatibus architecto dolores ea corporis consequatur laboriosam sequi minus molestiae porro magni fugiat!
                                                            </td>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <a href="" class="btn btn-primary text-white"><i
                                                                    class="fa fa-eye text-white"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <img src="images/logo.png" width="100px" alt="">
                                                            </td>
                                                            <td style="width: 50%" class="text-center">
                                                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam at esse repellendus labore, sit, soluta nobis obcaecati necessitatibus architecto dolores ea corporis consequatur laboriosam sequi minus molestiae porro magni fugiat!
                                                            </td>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <a href="" class="btn btn-primary text-white"><i
                                                                    class="fa fa-eye text-white"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <img src="images/logo.png" width="100px" alt="">
                                                            </td>
                                                            <td style="width: 50%" class="text-center">
                                                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam at esse repellendus labore, sit, soluta nobis obcaecati necessitatibus architecto dolores ea corporis consequatur laboriosam sequi minus molestiae porro magni fugiat!
                                                            </td>
                                                            <td>
                                                                4637526734
                                                            </td>
                                                            <td>
                                                                <a href="" class="btn btn-primary text-white"><i
                                                                        class="fa fa-eye text-white"></i></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
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
