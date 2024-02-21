<x-app-layout>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Product Categories
                            <a href="{{ url('categories/create') }}" class="btn btn-primary float-end">Add Category</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        
                                        <a href="{{ route('categories.edit', ['category' => $category->id]) }}" class="btn btn-success mx-2">Edit</a>
                                        <a href="{{ route('categories.delete', ['id' => $category->id]) }}" class="btn btn-danger mx-2" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $categories->links() }}  {{-- set pagination --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

</x-app-layout>