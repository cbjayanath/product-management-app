<x-app-layout>

    <div class="container mt-5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Products
                            <a href="{{ url('products/create') }}" class="btn btn-primary float-end">Add Product</a>
                        </h4>
                    </div>

                    <div class="col-md-4 mt-3 mx-auto text-center">

                        <div class="form-group">
                            <form method="get" action="/search">
                                <div class="input-group">
                                    <input class="form-control" name="search" type="text"
                                        value="{{ isset($search) ? $search : '' }}">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="card-body">

                        <a href="{{ url('products') }}"><i class="bi bi-arrow-left-circle-fill"
                                style="font-size: 2rem; margin-top:-10px"></i></a>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Display Order</th>
                                    <th>Price</th>
                                    <th>Created By</th>
                                    {{-- disable if user has no permission --}}
                                    @if(auth()->check() && auth()->user()->is_admin)<th>Action</th>@endif 
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->code }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <img src="{{  asset('uploads/products/' . $product->image)  }}"
                                            style="width: 100px; height:100px" alt="Img" />
                                    </td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->display_order_no }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->creator->name}}</td>
                                    @if(auth()->check() && auth()->user()->is_admin)  {{-- disable if user has no permission --}}
                                    <td>
                                        <a href="{{ route('products.edit', ['id' => $product->id]) }}"
                                            class="btn btn-success mx-2">Edit</a>
                                        <a href="{{ route('products.delete', ['id' => $product->id]) }}"
                                            class="btn btn-danger mx-2"
                                            onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                    @endif

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{ $products->links() }}  {{-- set pagination --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

</x-app-layout>