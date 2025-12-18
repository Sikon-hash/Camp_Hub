<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style type="text/css">

        .div_deg 
        {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .table_deg
        {
            border: 2px solid greenyellow;

        }

        th
        {
            background-color: skyblue;
            color: white;
            font-size: 19px;
            font-weight: bold;
            padding: 15px;
        }

        td 
        {
            border: 1px solid skyblue;
            text-align: center;
            color: white;
        }

        input[type='search']
        {
            width: 500px;
            height: 60px;
            margin-left: 50px;
        }

    </style>
    
  </head>
  <body>

    @include('admin.header')

    @include('admin.sidebar')
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <div class="page-header">
        <div class="container-fluid">
    <!-- Search Form -->
    <form action="{{url('product_search')}}" method="get">
        @csrf
        <input type="search" name="search" placeholder="Search products...">
        <input type="submit" class="btn btn-secondary" value="Search">
    </form>

    <!-- Button for Trashed Products -->
    <div class="mt-4">
        <a href="{{ url('trashed_products') }}" class="btn btn-warning">
            View Trashed Products
        </a>
    </div>
</div>



            <div class="div_deg">

            <table class="table_deg">

                <tr>

                    <th>Product Title</th>

                    <th>Description</th>

                    <th>Category</th>

                    <th>Price</th>

                    <th>Quantity</th>

                    <th>Image</th>

                    <th>Edit</th>

                    <th>Delete</th>

                    

                </tr>


                @foreach($product as $products)

                <tr>
                    <td>{{$products->title}}</td>

                    <td>{!!Str::limit($products->description, 50)!!}</td>

                    <td>{{$products->category}}</td>

                    <td>{{$products->price}}</td>

                    <td>{{$products->quantity}}</td>

                    <td>
                        <img height="120" width="120" src="
                        products/{{$products->image}}" >
                    </td>

                    <td>
                        <a class="btn btn-success" href="{{url('update_product', $products->id)}}">Edit</a>
                    </td>

                    <td>
                        <a class="btn btn-danger" onclick="confirmation(event)" href="
                        {{url('delete_product', $products->id)}}">Delete</a>
                    </td>

                    


                </tr>


                @endforeach
                

            </table>

            





            </div>

            <div class="div_deg">
                {{$product->links()}}
            </div>


            @if($products->trashed())
    <td>
        <a class="btn btn-warning" href="{{url('restore_product', $products->id)}}">Restore</a>
    </td>
    <td>
        <a class="btn btn-danger" href="{{url('force_delete_product', $products->id)}}">Delete Permanently</a>
    </td>
@else
    <td>
        <a class="btn btn-success" href="{{url('update_product', $products->id)}}">Edit</a>
    </td>
    <td>
        <a class="btn btn-danger" onclick="confirmation(event)" href="{{url('delete_product', $products->id)}}">Delete</a>
    </td>
@endif

        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{asset('admincss/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/popper.js/umd/popper.min.js')}}"> </script>
    <script src="{{asset('admincss/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{asset('admincss/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('admincss/js/charts-home.js')}}"></script>
    <script src="{{asset('admincss/js/front.js')}}"></script>
  </body>
</html>