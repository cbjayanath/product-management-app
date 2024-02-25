<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        
        $categories = Category::paginate(10);
        return view('category.index', compact( 'categories'));
    }

    public function create(){
        return view('category.create');
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'name'=>'required|max:255|string'
            
        ]);
        $category = new Category( ); 
        $category->name = $validateData['name'];
        $category->save();
        return  redirect()->route('categories.create')->with('success','Category Created  Successfully!');
    }
    public function edit(int $id){

        $category = Category::findOrFail($id); //if  the id does not exist then return 404 
        return view('category.edit',compact('category'));
        
    }
    public function update(Request $request, int $id){
        $validateData = $request->validate([
            'name'=>'required|max:255|string'
            
        ]);
        $category = Category::findOrFail($id);
        $category->name = $validateData['name'];
        $category->update();
        return  redirect()->back()->with('success','Category Updated  Successfully!');
    }
    public function destroy(int $id)
{
    $category = Category::findOrFail($id);

    // Check if any products are associated with this category
    $productsCount = Product::where('category_id', $id)->count();

    if ($productsCount > 0) {
        return redirect()->back()->with('error', 'Cannot delete category. It is associated with '.$productsCount.' product(s).');
    }

    $category->delete();

    return redirect()->back()->with('success', 'Category Deleted Successfully!');
}

}
