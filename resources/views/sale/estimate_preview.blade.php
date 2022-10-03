
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<style>
    body{
    margin-top:20px;
    color: #484b51;
}
.text-secondary-d1 {
    color: #728299!important;
}
.page-header {
    margin: 0 0 1rem;
    padding-bottom: 1rem;
    padding-top: .5rem;
    border-bottom: 1px dotted #e2e2e2;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-align: center;
    align-items: center;
}
.page-title {
    padding: 0;
    margin: 0;
    font-size: 1.75rem;
    font-weight: 300;
}
.brc-default-l1 {
    border-color: #dce9f0!important;
}

.ml-n1, .mx-n1 {
    margin-left: -.25rem!important;
}
.mr-n1, .mx-n1 {
    margin-right: -.25rem!important;
}
.mb-4, .my-4 {
    margin-bottom: 1.5rem!important;
}

hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.1);
}

.text-grey-m2 {
    color: #888a8d!important;
}

.text-success-m2 {
    color: #86bd68!important;
}

.font-bolder, .text-600 {
    font-weight: 600!important;
}

.text-110 {
    font-size: 110%!important;
}
.text-blue {
    color: #478fcc!important;
}
.pb-25, .py-25 {
    padding-bottom: .75rem!important;
}

.pt-25, .py-25 {
    padding-top: .75rem!important;
}
.bgc-default-tp1 {
    background-color: rgba(121,169,197,.92)!important;
}
.bgc-default-l4, .bgc-h-default-l4:hover {
    background-color: #f3f8fa!important;
}
.page-header .page-tools {
    -ms-flex-item-align: end;
    align-self: flex-end;
}

.btn-light {
    color: #757984;
    background-color: #f5f6f9;
    border-color: #dddfe4;
}
.w-2 {
    width: 1rem;
}

.text-120 {
    font-size: 120%!important;
}
.text-primary-m1 {
    color: #4087d4!important;
}

.text-danger-m1 {
    color: #dd4949!important;
}
.text-blue-m2 {
    color: #68a3d5!important;
}
.text-150 {
    font-size: 150%!important;
}
.text-60 {
    font-size: 60%!important;
}
.text-grey-m1 {
    color: #7b7d81!important;
}
.align-bottom {
    vertical-align: bottom!important;
}
footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 75px;
  background: white;
  border-top: 5px solid #6699cc;
}
</style>
  </head>
  <body>
<div class="page-content container">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Estimate
        </h1>
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                            <img src="images/logo.png" alt="" width="200">
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
                <div class="row">
                    <div class="col-sm-5">
                        <div>
                            <span class="text-600 text-110">Customer : </span>{{$lims_customer_data->name}}
                            <hr style="border: 2px solid black; opacity:1">
                        </div>
                        <div>
                            <span class="">Company : </span>{{$lims_customer_data->company_name}}
                        </div>
                        <div>
                            <span class="">Tax number : </span>{{$lims_customer_data->tax_no}}
                        </div>
                        <div>
                            <span class="">Mobile number : </span>{{$lims_customer_data->phone_number}}
                        </div>
                        <div>
                            <span class="">Address : </span>{{$lims_customer_data->address}}
                        </div>
                        <div>
                            <span class="">Email : </span>{{$lims_customer_data->email}}
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-5 offset-2">
                        <hr class="d-sm-none" />
                        <div class="text-black-m2">
                            <div>
                            <span class="text-600 text-110">Document Details:</span>
                            <hr style="border: 2px solid black;opacity:1">
                            </div>
                            <div>
                                <span class="">Document Date : </span>{{$request['created_at']}}
                            </div>
                            <div>
                                <span class="">Note : </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="mt-4">
                    <div class="row text-600 text-white pm-25 pb-3" style="background:lightgray;">
                    <hr style="border: 3px solid black;opacity:1">
                        <div class="d-none d-sm-block col-1">#</div>
                        <div class="col-9 col-sm-5">Description</div>
                        <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                        <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                        <div class="col-2">Total Amount</div>
                    </div>

                    <div class="text-95 text-secondary-d3">
                        @php $i = 0;
                        @endphp
                        @foreach($request['product_id'] as $product_id)
                        @php 
                        $product = App\Product::Find($product_id);
                        $qty = $request['qty'][$i];
                        @endphp
                        <div class="row mb-2 mb-sm-0 py-25">
                            <div class="d-none d-sm-block col-1">{{$i+1}}</div>
                            <div class="col-9 col-sm-5">{{$product->name}}</div>
                            <div class="d-none d-sm-block col-2">{{$qty}}</div>
                            <div class="d-none d-sm-block col-2 text-95">${{$product->price}}</div>
                            <div class="col-2 text-secondary-d2">${{$product->price * $qty}}</div>
                        </div>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                    </div>

                    <div class="row border-b-2 brc-default-l2"></div>

                    <!-- or use a table instead -->
                    <!--
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                    <thead class="bg-none bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th width="140">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                        <tr></tr>
                        <tr>
                            <td>1</td>
                            <td>Domain registration</td>
                            <td>2</td>
                            <td class="text-95">$10</td>
                            <td class="text-secondary-d2">$20</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
            -->

                    <div class="row mt-3">

                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            
                        </div>

                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                            <div class="row my-2">
                    <hr style="border: 2px solid black;opacity:1">

                                <div class="col-7 text-right">
                                    
                                    SubTotal
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">${{$request['total_price']}}</span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-7 text-right">
                                    Discount 
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$request['order_discount']}}</span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-7 text-right">
                                    Tax
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$request['order_tax']}}</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-7 text-right">
                                    Shipping Cost
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">${{$request['shipping_cost']}}</span>
                                </div>
                            </div>

                            <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                <div class="col-7 text-right">
                                    Net To Pay
                                </div>
                                <div class="col-5">
                                    <span class="text-150 text-success-d3 opacity-2">${{$request['grand_total']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:100px;">
        <div class="col-12">

        </div>
    </div>
</div>
<footer>
    <div class="row p-2">
        <div class="col-6">
            <h1><i class="bi bi-file-earmark-text"></i> Estimate</h1>
        </div>
        <div class="col-6 mr-2" align="right">
            <a href="{{route('sales.edit',$lims_sale_data->id)}}" class="btn btn-primary">Update</a>
            <a href="{{route('sales.approveEstimate',$lims_sale_data->id)}}" class="btn btn-success">Approve</a>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>
