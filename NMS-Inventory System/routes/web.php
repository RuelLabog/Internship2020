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
    // return view('auth.login');
    return redirect('/login');
});

// Route::get('/login', function () {
//     return view('auth/login');
// });

Auth::routes();

//dashboard route
Route::get('/home', 'HomeController@index')->name('home');

//items routes
Route::resource('items_page', 'ItemsController');
Route::get('/items', 'ItemsController@getData');
Route::post('/items','ItemsController@insert');
Route::get('/softdelitem', 'ItemsController@destroy')->name('itemSoftDelete');

//categoies routes
Route::get('/categories', 'CategoriesController@getData');
Route::get('/softdelcat', 'CategoriesController@destroy')->name('catSoftDelete');

//users routes
Route::get('/users', 'UsersController@getData');


