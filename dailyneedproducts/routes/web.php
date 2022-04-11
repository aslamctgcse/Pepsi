<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	//dd('i m here');
    return view('welcome');
});
Route::get('/products', 'ProductController@productlist')->name('products');
Route::get('/addtocart/{id}/{type?}', 'ProductController@addtocart');
Route::get('/incordec/{id}/{type?}', 'ProductController@incordec');
Route::get('/cart', 'ProductController@cart');
Route::any('/checkout', 'ProductController@checkout');
Route::any('/orders', 'ProductController@orders');
Route::get('/product-detail/{id}', 'ProductController@productDetail')->name('product-detail');
Route::any('/orders-export', 'ProductController@orderExport')->name('orders-export');
//admin panel aad product
Route::any('/add-product', 'ProductController@addproduct');
Route::post('/managestock', 'ProductController@managestock');  //to manage stock
Route::any('/edit-product-form/{id}', 'ProductController@editProductForm')->name('edit-product-form');
Route::any('/edit-product/{id}', 'ProductController@editProduct')->name('edit-product');
Route::any('/delete-product/{id}', 'ProductController@deleteProduct')->name('delete-product');
Route::any('/remove-product/{id}', 'ProductController@removeproduct');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/terms', function () {
    return view('terms');
});