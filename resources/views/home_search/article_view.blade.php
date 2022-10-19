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
    <link rel="stylesheet" href="{{ asset('css/article_search.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <section>
        <form action="{{ route('purchase_add_to_cart') }}" id="add_to_cart_form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="article" value="{{ $article->legacyArticleId }}">
            <input type="hidden" name="section" value="{{ $section->assemblyGroupNodeId }}">
            <input type="hidden" name="sub_section" value="{{ $sub_section->assemblyGroupNodeId }}">
            <input type="hidden" name="engine" value="{{ $engine->linkageTargetId }}">
            <input type="hidden" name="brand" value="{{ $brand->brandId }}">
            <input type="hidden" name="purchase_price" value="1">
            <input type="hidden" name="discount" value="0">
            <input type="hidden" name="additional_cost_with_vat" value="0">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-6">
                        <div class="table">
                            <div class="m-3">
                                <img src="{{ asset('images/E.png') }}" alt="" width="75px" height="75px">
                            </div>
                            <table class="table">
                                <thead>
                                    <tr class="article_view_tr_head">
                                        <th>
                                            Basic Information
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            Brand
                                        </th>
                                        <td>
                                            {{ $brand ? $brand->brandName : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Article Number
                                        </th>
                                        <td>
                                            {{ $article ? $article->articleNumber : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Product Group
                                        </th>
                                        <td>
                                            {{ $sub_section ? $sub_section->assemblyGroupName : 'N/A' }}
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-6 text-center pt-3">
                        <img id="image_view" src="{{ asset('images/E.png') }}" alt="" width="200px" height="200px"
                            data-toggle="modal" data-target="#staticBackdrop" style="cursor: pointer;">

                    </div>
                </div>
                <div class="row ">
                    <div class="col-6">
                        <div class="table">
                            <table class="table">
                                <thead>
                                    <tr class="article_view_tr_head">
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
                                            {{ $article ? $article->articleNumber : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            GTIN/EAN
                                        </th>
                                        <td>
                                            {{ !empty($article->articleEAN) ? $article->articleEAN->eancode : 'N/A' }}
                                        </td>
                                    </tr>
                                    {{-- <tr>
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
                                </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="table">
                            <table class="table">
                                <thead>
                                    <tr class="article_view_tr_head">
                                        <th>
                                            Criteria
                                        </th>
                                        <th>{{ !empty($article->articleCriteria) ? $article->articleCriteria->criteriaAbbrDescription : 'N/A' }}
                                        </th>
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
                                    <tr class="article_view_tr_head">
                                        <th>
                                            Vehicle Linkage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ $engine ? $engine->description : 'N/A' }}
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="row ">
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
            </div> --}}
                <div class="row">
                    <div class="col-6">
                        <div class="table">
                            <table class="table">
                                <thead>
                                    <tr class="article_view_tr_head">
                                        <th>
                                            Manufacturer Information
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
                                            {{ $engine->mfrName }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="article_view_cash_type_head">Select Cash Type <span>*</span></h6>
                        <div class="box">
                            <label class="custom-radio-button__container">
                                <input type="radio" name="cash_type" value="white_cash">
                                <span class="custom-radio-button designer">
                                    <i class="fa fa-solid fa-sack-dollar"></i> White Cash
                                </span>
                            </label>
                            <label class="custom-radio-button__container">
                                <input type="radio" name="cash_type" value="black_cash">
                                <span class="custom-radio-button designer">
                                    <i class="fa fa-solid fa-sack-dollar"></i> Black Cash
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1 offset-9 mr-4">
                        <div class="prod_cart_option d-flex justify-content-between mb-3 pb-2">
                            <div class="buttons_opt">
                                <span class="m_btn" id="minus"> <i class="fa fa-minus text-danger"></i></span>
                                <input type="number" class="cart_item" id="quantity"
                                    style="border: none;outline: none;background: transparent;width: 45px" min="1" name="quantity"
                                    value="1">
                                <span class="m_btn" id="plus"> <i class="fa fa-plus text-success"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-right ">
                        <button class="btn btn-primary" type="button" onclick="addToCart()">
                            <i class="dripicons-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
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
                            <img id="image_view" src="{{ asset('images/E.png') }}" alt=""
                                style="width: auto; height: 300px">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#minus').click(function() {
            var quantity = $('.cart_item').val();
            if (quantity > 1) {
                var alter_quantity = quantity - 1;
                $('.cart_item').val(alter_quantity);
            }
        });

        $('#plus').click(function() {
            var quantity = $('.cart_item').val();
            var alter_quantity = quantity - (-1);
            $('.cart_item').val(alter_quantity);
        })
    });

    function addToCart() {
        var cash_type = $('input[name="cash_type"]:checked').val();
        var quantity = $('#quantity').val();
        if (!cash_type) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select cash type',

            });
            exit();

        }
        if (quantity <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Quantity must be greater than 0',

            });
            exit();

        }
        document.getElementById("add_to_cart_form").submit();

    }
</script>
