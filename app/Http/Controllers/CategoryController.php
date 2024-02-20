<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        
        $categories = Category::get();
        return view('category.index', compact( 'categories'));
    }

    public function create(){
        return view('category.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|max:255|string'
            
        ]);

        Category::create([
            'name'=>$request->input('name')
        ]);
        return  redirect()->route('categories.create')->with('success','Category Created  Successfully!');
    }
    public function edit(int $id){

        $category = Category::findOrFail($id); //if  the id does not exist then return 404 
        return view('category.edit',compact('category'));
        
    }
    public function update(Request $request, int $id){
        $request->validate([
            'name'=>'required|max:255|string'
            
        ]);

        Category::findOrFail($id)->update([
            'name'=>$request->input('name')
        ]);
        return  redirect()->back()->with('success','Category Updated  Successfully!');
    }
    public function destroy(int $id){
  

        Category::findOrFail($id)->delete();
        return  redirect()->back()->with('success','Category Deleted  Successfully!');
    }
}
