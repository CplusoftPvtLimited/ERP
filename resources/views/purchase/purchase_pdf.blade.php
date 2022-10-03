<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Purchase Products') }}</h4>
                        </div>
                        <table class="table" id="purchase-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Puchase Date</th>
                                    <th>Reference No</th>
                                    <th>Total Quantity</th>
                                    <th>Total Item</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Section</th>
                                    <th>Brand</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_data as $key => $data)
                                    @foreach ($data['purchase_products'] as $product)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $data['purchase']->date }}</td>
                                            <td>{{ $product->reference_no }}</td>
                                            <td>{{ $data['purchase']->total_qty }}</td>
                                            <td>{{ $data['purchase']->item }}</td>
                                            <td>{{ $product->manufacturer }}</td>
                                            <td>{{ $product->model }}</td>
                                            <td>{{ $product->section }}</td>
                                            <td>{{ $product->supplier }}</td>
                                            
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>
</body>
</html>
