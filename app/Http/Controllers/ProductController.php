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
        $validateData = $request->validate([ //validate when storing
            'name' => 'required|max:255|string',
            'code' => 'required|max:255',
            'description' => 'nullable|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
            'category_id' => 'required|exists:categories,id',
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

        $product = new Product();
        $product->name = $validateData['name'];
        $product->code = $validateData['code'];
        $product->description = $validateData['description'];
        $product->category_id = $validateData['category_id'];
        $product->display_order_no = $validateData['display_order_no'];
        $product->price = $validateData['price'];
        $product->image = isset($file_name) ? $file_name : '';
        $product->created_by = Auth::user()->id;
        $product->save();

        Product::where('display_order_no', '>=', $validateData['display_order_no'])
            ->where('id', '<>', $product->id)
            ->increment('display_order_no');

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
        $validateData = $request->validate([
            'name' => 'required|max:255|string',
            'code' => 'required|max:255',
            'description' => 'nullable|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|exists:categories,id',
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
            $product->image = $file_name;
        }
        $oldDisplayOrder = $product->display_order_no;
        $newDisplayOrder = $request->input('display_order_no');

        // changing display order numbers 
        if ($oldDisplayOrder !== $newDisplayOrder) {
            if ($newDisplayOrder > $oldDisplayOrder) {
                Product::where('display_order_no', '>', $oldDisplayOrder)
                    ->where('display_order_no', '<=', $newDisplayOrder)
                    ->decrement('display_order_no');
            } else {
                Product::where('display_order_no', '<', $oldDisplayOrder)
                    ->where('display_order_no', '>=', $newDisplayOrder)
                    ->increment('display_order_no');
            }
        }

        $product->name = $validateData['name'];
        $product->code = $validateData['code'];
        $product->description = $validateData['description'];
        $product->category_id = $validateData['category_id'];
        $product->display_order_no = $validateData['display_order_no'];
        $product->price = $validateData['price'];
        $product->created_by = Auth::user()->id;
        $product->update();

        return redirect()->route('products.create')->with('success', 'Product Updated  Successfully!');
    }
    public function destroy(int $id)
    {

        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Product Deleted  Successfully!');
    }
}
