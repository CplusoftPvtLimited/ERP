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
            <div class="row ">
                <div class="col-6">
                    <div class="table">
                        <div class="m-3">
                            <img src="images/E.png" alt="" width="75px" height="75px">
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>
                                        Brand
                                    </th>
                                    <td>
                                        Hella
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Article Number
                                    </th>
                                    <td>
                                        Hella
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Product Group
                                    </th>
                                    <td>
                                        Hella
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>
                                        Hella
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Price
                                    </th>
                                    <td>
                                        Hella
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-6 text-center pt-3">
                    <img id="image_view" src="images/E.png" alt="" width="200px" height="200px" data-toggle="modal"
                        data-target="#staticBackdrop" style="cursor: pointer;">
                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Image</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="image_view" src="images/E.png" alt=""
                                        style="width: auto; height: 300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-6">
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        General
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        Article Number
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        GTIN/EAN
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Packing Unit
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Quantity per Packing Unit
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Criteria
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        Filter Type
                                    </th>
                                    <td>
                                        574797987
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Vehicle Linkage
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        Toyota
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-6">
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Prices
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        Price Type
                                    </th>
                                    <th>
                                        Price Unit
                                    </th>
                                    <th>
                                        Price Unit
                                    </th>
                                    <th>
                                        Quantity Unit
                                    </th>
                                    <th>
                                        Price
                                    </th>
                                    <th>
                                        Discount Group
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        Lorem ipsum dolor sit amet.
                                    </td>
                                    <td>
                                        Lorem
                                    </td>
                                    <td>
                                        Lorem
                                    </td>
                                    <td>
                                        Lorem
                                    </td>
                                    <td>
                                        Lorem
                                    </td>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Manufacturer Address
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        Name
                                    </th>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Name Supplement
                                    </th>
                                    <td>
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloribus, perferendis!
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Street
                                    </th>
                                    <td>
                                        Name
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Country
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        City
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Postal Code
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Telephone
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Fax
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Email
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Internet
                                    </th>
                                    <td>
                                        Lorem
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2 pt-1 offset-8 text-center" style="border: 1px solid black;">
                    <span class="mr-3" style="border: 1px solid blue; width:25px;  border-radius:50%"><strong>-
                        </strong></span>
                    <span class="" style="border: 1px solid blue; width:25px;  border-radius:50%">0</span>
                    <span class="ml-3" style="border: 1px solid blue; width:25px;  border-radius:50%">+</span>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary">
                        <i class="dripicons-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
<script></script>
