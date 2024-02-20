<?php

use App\Http\Controllers\ProfileController;
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

Route::get('categories', 'App\Http\Controllers\CategoryController@index')->name('categories.index');

Route::get('categories/create', 'App\Http\Controllers\CategoryController@create')->name('categories.create');

Route::post('categories/create', 'App\Http\Controllers\CategoryController@store')->name('categories.store');

Route::get('categories/edit/{id}', 'App\Http\Controllers\CategoryController@edit')->name('categories.edit');

Route::put('categories/edit/{id}', 'App\Http\Controllers\CategoryController@update')->name('categories.update');

Route::get('categories/delete/{id}', 'App\Http\Controllers\CategoryController@destroy')->name('categories.delete');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
