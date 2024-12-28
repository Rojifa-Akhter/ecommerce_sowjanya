<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .product-images img {
            max-width: 150px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Product Added Successfully</h1>
    <table>
        <tr>
            <th>Property</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>ID</td>
            <td>{{ $product['id'] }}</td>
        </tr>
        <tr>
            <td>Title</td>
            <td>{{ $product['title'] }}</td>
        </tr>
        <tr>
            <td>Sale Price</td>
            <td>${{ $product['sale_price'] }}</td>
        </tr>
        <tr>
            <td>Stock</td>
            <td>{{ $product['stock'] }}</td>
        </tr>
        <tr>
            <td>Description</td>
            <td>{{ $product['description'] }}</td>
        </tr>
        <tr>
            <td>Images</td>
            <td class="product-images">
                @foreach ($product['image'] as $image)
                    <img src="{{ $image }}" alt="Product Image">
                @endforeach
            </td>
        </tr>
    </table>
</body>
</html>
