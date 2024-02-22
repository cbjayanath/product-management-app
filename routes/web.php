<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Product Routes 
Route::get('products', 'App\Http\Controllers\ProductController@index')->name('products.index');
Route::get('products/create', 'App\Http\Controllers\ProductController@create')->name('products.create');
Route::post('products/create', 'App\Http\Controllers\ProductController@store')->name('products.store');
Route::get('products/edit/{id}', 'App\Http\Controllers\ProductController@edit')->name('products.edit');
Route::put('products/edit/{id}', 'App\Http\Controllers\ProductController@update')->name('products.update');
Route::get('products/delete/{id}', 'App\Http\Controllers\ProductController@destroy')->name('products.delete');

//search product
Route::get('/search', 'App\Http\Controllers\ProductController@search')->name('products.search');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('/categories', CategoryController::class); //granting permisssion for category section 
    
});

require __DIR__ . '/auth.php';
