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

/*Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['mySpatieRbacCheck', /*  'myJsonForce' , 'sendTokenMyX'*/],  /* 'prefix' => 'admin-s'*/ ], function () { //False?? => prefix can be changed any time, while no need to change url in menu, e.g  it still will be <a  class="nav-link" href="{{ route('crud-simple') }}"> CRUD </a>

    //Crud simple section
    Route::get('crud-simple',             'Crud_Simple\CrudController@index')         ->name('crud-simple');        //simple crud index
    Route::get('/wpBlogImagesOne/{id}',   'Crud_Simple\CrudController@viewOne')       ->name('wpBlogImagesOne');     //WpPress with Images Blog one article view route
    Route::get('createNewWpressImg',      'Crud_Simple\CrudController@create')        ->name('createNewWpressImg');  //WpPress with Images route for displaying form to create new entry
    Route::post('/storeNewWpressImg',     'Crud_Simple\CrudController@store');                                       //Saving form fields via POST
    Route::get('gii-edit-post/{id}',      'Crud_Simple\CrudController@edit')          ->name('gii-edit-post');       //WpPress with Images route for displaying form to edit one post record
    Route::put('update-post',             'Crud_Simple\CrudController@updatePost')    ->name('update-post');         //$_PUT to update existing post, not 'update-post/{id}'
    Route::delete('gii-delete-post',      'Crud_Simple\CrudController@deleteOneItem') ->name('gii-delete-post');     //REST API to /POST Delete one item
    // Route::delete('gii-delete-post/{id}', 'Crud_Simple\CrudController@deleteOneItem') ->name('gii-delete-post');     //REST API to /POST Delete one item

	
});




  //Vue-crud-panel section
  Route::get('vue-crud-panel',  'Vue_Crud_Panel\VueCrudController@index')  ->name('vue-crud-panel');        //vue-crud-panel index