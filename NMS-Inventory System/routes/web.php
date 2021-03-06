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
    return redirect('/login');
});

// Route::get('/login', function () {
//     return view('auth/login');
// });

Auth::routes();

//dashboard route

// Route::group(['middleware'=>'auth'], function(){


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sidebar', 'tryController@image');

//items routes
Route::resource('/items', 'ItemsController');
Route::resource('/items_page', 'ItemsController');
Route::post('/editItem', 'ItemsController@update')->name('itemEdit');
// Route::post('/editCat', 'CategoriesController@update')->name('catEdit');
// Route::get('/items', 'ItemsController@getData');
Route::post('/softdelitem', 'ItemsController@delete')->name('itemSoftDelete');
Route::post('/getItem', 'ItemsController@edit')->name('itemGetDataToEdit');
Route::post('/addItem','ItemsController@insert')->name('itemAdd');
Route::get('items/getdata', 'ItemsController@getdata')->name('items.getdata');
//Route::post('/items','ItemsController@insert')->name('itemInsert');
Route::post('items/insert', 'Items@insert')->name('items.insert');


//categoies routes
Route::resource('/categories', 'CategoriesController');
Route::resource('/categories_page', 'CategoriesController');
Route::post('/softDelCat', 'CategoriesController@delete')->name('catSoftDelete');
Route::post('/editCat', 'CategoriesController@update')->name('catEdit');
Route::post('/categories','CategoriesController@insert');

//users routes
// Route::get('/users', 'UsersController@getData');
Route::resource('/users_page', 'UsersController');
Route::post('/editUser', 'UsersController@update');
Route::post('/addUser', 'UsersController@insert')->name('addUser');
Route::post('/softDelUser', 'UsersController@destroy')->name('userSoftDelete');

Route::post('/items','ItemsController@insert');
Route::post('/categories','CategoriesController@insert')->name('categoryInsert');

//profile routes
Route::resource('/profile_page', 'ProfileController');
Route::get('/profile', 'ProfileController@getData');

//receipt routes
Route::resource('/receipt', 'ReceiptController');
Route::resource('/receipts_page', 'ReceiptController');


// });


