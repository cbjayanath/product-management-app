<x-app-layout>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Update Product
                            <a href="{{ url('products') }}" class="btn btn-primary float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', ['product' => $product->id])  }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ $product->name }}" id="name"
                                    class="form-control" />
                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" value="{{  $product->code }}" id="code"
                                    class="form-control" />
                                @error('code')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Image</label>
                                <input type="file" name="image" accept=".png,.jpg,.jpeg" id="image"
                                    onchange=previewImage() class="form-control" />
                                @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{  $product->description }}</textarea>
                                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name=" category_id" aria-label="Default select example">
                                    <option selected>Select product category</option>
                                    @foreach ($category as $item)
                                    <option value="{{ $item->id }}" {{ $product->category_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>

                                    @endforeach

                                </select>
                                @error('category_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order_no" value="{{ $product->display_order_no  }}"
                                    id="display_order_no" class="form-control" />
                                @error('display_order_no')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" value="{{ $product->price  }}" id="name"
                                    class="form-control" />
                                @error('price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                {{-- updating created by field as hidden input --}}
                                <input type="hidden" name="created_by" value="{{auth()->user()->id }}" id="created_by"
                                    class="form-control" />
                               
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary ">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


</x-app-layout>