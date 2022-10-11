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
        <td colspan="5"><h1 style="color:#728299">Invoice</h1></td>
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
        <td style="border-bottom:3px solid black;padding-top:10px" colspan="4"><strong>Customer:</strong> Walk In</td>
        <td style="padding-top:10px" colspan="2"> </td>
        <td style="border-bottom:3px solid black;padding-top:10px" colspan="3"><strong>Document Details:</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Company:</strong> New Company </td>
        <td colspan="2"></td>
        <td colspan="3" ><strong>Document Date:</strong> {{$sale->created_at->format('d-m-Y')}}</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Tax Number:</strong> 1022121201</td>
        <td colspan="2"></td>
        <td colspan="3"><strong>Note:</strong></td>

    </tr>
    <tr>
        <td colspan="7"><strong>Mobile Number:</strong> +219121212 </td>
    </tr>
    <tr>
        <td colspan="7"><strong>Address:</strong> Tunisia </td>
    </tr>
    <tr>
        <td colspan="7" style="padding-bottom:10px"><strong>Email:</strong>walk-in@erp.com</td>
    </tr>
    <tr style="background:lightgrey;border-top:5px solid black; ">
        <td style="padding:10px;" >#</td>
        <td colspan="5">Reference</td>
        <td>Qty</td>
        <td>Unit Price</td>
        <td>Total Amount</td>
    </tr>
        @php $i = 0; @endphp
        @foreach($products as $prd)
    <tr>
        <td>{{$i+1}}</td>
        <td colspan="5">{{$prd->reference_no}}</td>
        <td>{{$prd->quantity}}</td>
        <td>TND {{$prd->sale_price}}</td>
        <td>TND {{$prd->sale_price * $prd->quantity}}</td>
    </tr>
        @php $i++; @endphp
        @endforeach
        <tr>
            <td style="padding:25px"></td>
        </tr>
        <tr >
            <td colspan="7"></td>
            <td style="border-top:3px solid black; padding-top:2px">Sub Total</td>
            <td style="border-top:3px solid black; padding-top:2px">TND {{$sale->sale_entire_total_exculding_vat}}</td>
        </tr>
        <tr>
        <td colspan="7"></td>
            <td>Discount</td>
            <td>TND {{$sale->discount}}</td>
        </tr>
        <tr>
        <td colspan="7"></td>

            <td>Order Tax</td>
            <td>TND {{$sale->entire_vat}}</td>
        </tr>
        <tr>
        <td colspan="7"></td>

            <td>Shipping Cost</td>
            <td>TND {{$sale->shipping_cost}}</td>
        </tr>
        <tr>
            <td style="padding:10px;"></td>
        </tr>
        <tr>
        <td colspan="7"></td>

            <td style="border-top:2px dotted grey; padding-top:10px;">Net To Pay</td>
            <td style="border-top:2px dotted grey;padding-top:10px;">TND {{$sale->total_bill}}</td>
        </tr>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>


