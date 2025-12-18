<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <style>
        .div_deg {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .table_deg {
            border: 2px solid greenyellow;
        }

        th {
            background-color: skyblue;
            color: white;
            font-size: 19px;
            font-weight: bold;
            padding: 15px;
        }

        td {
            border: 1px solid skyblue;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
@include('admin.header')
@include('admin.sidebar')

<div class="page-content">
    <div class="page-header">
        <div class="container-fluid">
            <h2 class="text-center">Trashed Products</h2>
            <div class="div_deg">
                <table class="table_deg">
                    <tr>
                        <th>Product Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Restore</th>
                        <th>Delete Permanently</th>
                    </tr>

                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{!! Str::limit($product->description, 50) !!}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <img height="120" width="120" src="/products/{{ $product->image }}">
                            
                        </td>
                        <td>
                            <a class="btn btn-warning" href="{{ url('restore_product', $product->id) }}">Restore</a>
                        </td>
                        <td>
                            <a class="btn btn-danger" onclick="confirmation(event)" href="{{ url('force_delete_product', $product->id) }}">Delete Permanently</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="div_deg">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>


<script>
    function confirmation(event) {
        if (!confirm('Are you sure you want to perform this action?')) {
            event.preventDefault();
        }
    }
</script>

@include('admin.js')
</body>
</html>
