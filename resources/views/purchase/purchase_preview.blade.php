<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP</title>
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div style="padding:25px">
        <table style="width:100%;">
            <tr style="border-bottom:1px dotted grey;">
                <td colspan="5">
                    <h1 style="color:#728299">Purchase</h1>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" align="right"><img src="images/logo.png" alt="" width="150"></td>
            </tr>
            <tr>
                <td style="border-bottom:3px solid black;padding-top:10px" colspan="5"> <strong>Supplier:</strong>
                {{ isset($data['supplier']) ? $data['supplier']->name : 'N/A' }} </td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td style="border-bottom:3px solid black;padding-top:10px" colspan="2"><strong>
                   Document Details</strong></td> --}}
            </tr>
            <tr>
                <td colspan="5"><strong>Shop Name:</strong> {{ isset($data['supplier']) ? $data['supplier']->shop_name : 'N/A' }} </td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td colspan="2"><strong>Document Date:</strong> 00-00-0000</td> --}}
            </tr>
            <tr>
                <td colspan="5"><strong>Mobile Number:</strong> {{ isset($data['supplier']) ? $data['supplier']->phone : 'N/A' }} </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5"><strong>Address:</strong> {{ isset($data['supplier']) ? $data['supplier']->address : 'N/A' }} </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5"><strong>Email:</strong> {{ isset($data['supplier']) ? $data['supplier']->email : 'N/A' }} </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background:lightgrey;border-top:5px solid black; ">
                <td colspan="5" style="padding:10px;">Reference No.</td>
                <td>Date</td>
                <td>Qty</td>
                <td>Unit Price</td>
                <td>Discount</td>
                <td>Total Amount</td>
            </tr>
            @if(count($data['purchase_products']) > 0)
            @foreach ($data['purchase_products'] as $item)
                <tr>
                    <td colspan="5" style="padding:10px;">{{ $item->reference_no }} </td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->actual_price }} TND</td>
                    <td>{{ $item->discount }}</td>
                    <td>{{ $item->actual_cost_per_product }}</td>
                </tr>
                {{-- @php $i++; @endphp --}}
            @endforeach
            @endif

            <tr>
                <td style="padding:25px"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-top:3px solid black; padding-top:2px;">Total Excluding VAT</td>
                <td style="border-top:3px solid black; padding-top:2px;">{{ isset($data['purchase']->total_exculding_vat) ? $data['purchase']->total_exculding_vat : 0 }} TND
                </td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total VAT</td>
                <td>{{ isset($data['purchase']->total_vat) ? $data['purchase']->total_vat : 0 }} TND</td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Tax Stamp</td>
                <td>{{ isset($data['purchase']->tax_stamp) ? $data['purchase']->tax_stamp : 0 }} TND</td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Shipping Cost</td>
                <td>{{ $data['purchase']->additional_cost }} TND</td>
            </tr>
            <tr>
                <td style="padding:10px;"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-top:2px dotted grey; padding-top:10px;">Net To Pay</td>
                <td style="border-top:2px dotted grey;padding-top:10px;">{{ $data['purchase']->total_cost }} TND</td>
            </tr>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>
</body>

</html>
