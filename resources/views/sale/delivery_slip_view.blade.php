<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP</title>
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<style>
    *{
  box-sizing: border-box;
    }
   </style>
  </head>
  <body>
<div style="padding:25px">
<table style="width:100%;">
    <tr style="border-bottom:1px dotted grey;">
        <td colspan="5"><h1 style="color:#728299">Delivery Slip</h1></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr style="border-bottom:1px solid lightgrey;">
        <td colspan="4"></td>
        <td align="right"><img src="images/logo.png" alt="" width="200"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>
        <td style="border-bottom:3px solid black;padding-top:10px" colspan="4"><strong>Customer:</strong> {{$customer->name}}</td>
        <td style="padding-top:10px" colspan="2"> </td>
        <td style="border-bottom:3px solid black;padding-top:10px" colspan="3"><strong>Document Details:</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Company:</strong> {{$customer->company_name}}</td>
        <td colspan="2"></td>
        <td colspan="3" ><strong>Document Date:</strong> {{$deliveryslip->created_at->format('d-m-Y')}}</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Tax Number:</strong> {{$customer->tax_no}}</td>
        <td colspan="2"></td>
        <td colspan="3"><strong>Note:</strong></td>

    </tr>
    <tr>
        <td colspan="7"><strong>Mobile Number:</strong> {{$customer->phone_number}}</td>
    </tr>
    <tr>
        <td colspan="7"><strong>Address:</strong> {{$customer->address}}</td>
    </tr>
    <tr>
        <td colspan="7" style="padding-bottom:10px"><strong>Email:</strong> {{$customer->email}}</td>
    </tr>
    <tr style="background:lightgrey;border-top:5px solid black; ">
        <td style="padding:10px;" >#</td>
        <td colspan="4">Description</td>
        <td>Qty</td>
        <td>Unit Price</td>
        <td>Unit Tax</td>
        <td>Total Amount</td>
    </tr>
        @php $i = 0; @endphp
        @foreach($products as $prd)
            @php 
                $product = App\Product::find($prd->product_id);
                $tax = App\Tax::Find($product->tax_id);
            @endphp
    <tr>
        <td>{{$i+1}}</td>
        <td colspan="3">{{$product->name}}</td>
        <td></td>
        <td>{{$prd->qty}}</td>
        <td>${{$product->price}}</td>
        @if($tax)
        @php $taxes = ($tax->rate * $product->price)/100; @endphp
        <td>${{$taxes}}</td>
        <td>${{($product->price + $taxes) * $prd->qty}}</td>
        @else
        <td>$0</td>
        <td>${{$product->price * $prd->qty}}</td>
        @endif
    </tr>
        @php $i++; @endphp
        @endforeach
        <tr>
            <td style="padding:25px"></td>
        </tr>
        <tr >
            <td colspan="7"></td>
            <td style="border-top:3px solid black; padding-top:2px">Sub Total</td>
            @if($sale != null)
            <td style="border-top:3px solid black; padding-top:2px">${{$sale->total_price}}</td>
            @elseif($invoice != null)
            <td style="border-top:3px solid black; padding-top:2px">${{$invoice->total_price}}</td>
            @endif
        </tr>
        <tr>
        <td colspan="7"></td>
            <td>Discount</td>
            @if($sale != null)
            <td>${{$sale->order_discount}}</td>
            @elseif($invoice != null)
            <td>${{$invoice->order_discount}}</td>
            @endif
        </tr>
        <tr>
        <td colspan="7"></td>

            <td>Order Tax</td>
            @if($sale != null)
            <td>${{$sale->order_tax}}</td>
            @elseif($invoice != null)
            <td>${{$invoice->order_tax}}</td>
            @endif
        </tr>
        <tr>
        <td colspan="7"></td>

            <td>Shipping Cost</td>
            @if($sale != null)
            <td>${{$sale->shipping_cost}}</td>
            @elseif($invoice != null)
            <td>${{$invoice->shipping_cost}}</td>
            @endif
        </tr>
        <tr>
            <td style="padding:10px;"></td>
        </tr>
        <tr>
        <td colspan="7"></td>

            <td style="border-top:2px dotted grey; padding-top:10px;">Net To Pay</td>
            @if($sale != null)
            <td style="border-top:2px dotted grey;padding-top:10px;">${{$sale->grand_total}}</td>
            @elseif($invoice != null)
            <td style="border-top:2px dotted grey;padding-top:10px;">${{$invoice->grand_total}}</td>
            @endif
        </tr>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>

