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
    return view('welcome');
});

Auth::routes();


Route::group(['middleware' => 'web'], function () {
	Route::auth();
	Route::get('/home', 'HomeController@index');
});


Route::get('/user/{user}', 'UserController@getUserById');



<<<<<<< HEAD
Route::post('/send', 'ChatController@setMessages');
=======
Route::get('/send', 'ChatController@setMessages');


Route::get('/test', function (){
	$test = \App\Models\TestModel::all();
	$test->each(function ($item){
		$item->text = $item->text . 'Laravel - 1';
		$item->update();
	});
	echo "done";
});
>>>>>>> commit put REQUEsT
