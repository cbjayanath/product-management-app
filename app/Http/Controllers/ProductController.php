<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {

        $products = Product::orderBy('display_order_no', 'asc')->paginate(10);
        return view('product.index', compact('products'));
    }
    public function create()
    {
        $category = Category::all();
        $user = User::all();
        return view('product.create', compact('category', 'user'));
    }
    public function search(Request $request)
    {
        $search = $request->search;
        $products = Product::where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%') //search name wise 
                ->orWhere('code', 'like', '%' . $search . '%');//search code wise
        })
            ->orWhereHas( //search category wise
                'category',
                function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
            )->paginate(10);

        return view('product.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([ //validate when storing
            'name' => 'required|max:255|string',
            'code' => 'required|max:255',
            'description' => 'nullable|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required',
            'display_order_no' => 'required|numeric',
            'price' => 'required|numeric',

        ]);

        if ($request->has('image')) { 
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $extension;
            $path = 'uploads/products/';
            $file->move($path, $file_name);  //set file path with file name
        }
        
        $existingProductsWithSameOrder = Product::where('display_order_no', $request->display_order_no)->get();
        if ($existingProductsWithSameOrder->isNotEmpty()) {
            // Increment display_order_no for existing products with the same order
            foreach ($existingProductsWithSameOrder as $existingProduct) {
                $existingProduct->update(['display_order_no' => $existingProduct->display_order_no + 1]);
            }
        }

        Product::create([ //saving product data
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'image' => isset($file_name) ? $file_name : '',
            'category_id' => $request->category_id,
            'display_order_no' => $request->display_order_no,
            'price' => $request->price,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('products.create')->with('success', 'Product Created  Successfully!');
    }

    public function edit(int $id)
    {

        $product = Product::findOrFail($id); //if  the id does not exist then return 404 
        $category = Category::all();
        return view('product.edit', compact('product', 'category'));

    }
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'code' => 'required|max:255',
            'description' => 'nullable|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required',
            'display_order_no' => 'required|numeric',
            'price' => 'required|numeric',

        ]);
        $product = Product::findOrFail($id);

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $extension;
            $path = 'uploads/products/';
            $file->move($path, $file_name);  //set file path with file name

            if (File::exists($product->image)) { //delete existing image when upload new one
                File::delete($product->image);
            }
        }
        if ($product->display_order_no != $request->display_order_no) {// Check if the display_order_no is changing
           
            $existingProductsWithSameOrder = Product::where('display_order_no', $request->display_order_no)->get();
            if ($existingProductsWithSameOrder->isNotEmpty()) {
                // Increment display_order_no for existing products with the same order
                foreach ($existingProductsWithSameOrder as $existingProduct) {
                    $existingProduct->update(['display_order_no' => $existingProduct->display_order_no + 1]);
                }
            }
        }

       
        $product->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'image' => isset($file_name) ? $file_name : '',
            'category_id' => $request->category_id,
            'display_order_no' => $request->display_order_no,
            'price' => $request->price,
            'created_by' => Auth::user()->id
        ]);
        return redirect()->route('products.create')->with('success', 'Product Updated  Successfully!');
    }
    public function destroy(int $id)
    {

        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Product Deleted  Successfully!');
    }
}
