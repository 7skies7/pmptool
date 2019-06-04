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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/program', 'ProgramController@index');
Route::post('/program/store', 'ProgramController@store');

Route::get('/company', 'CompanyController@index');
Route::post('/company/store', 'CompanyController@store');
Route::get('/company/edit/{id}', 'CompanyController@edit');
Route::get('/company/getResources', function(){
	$arr = App\User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->get();
	return $arr;
});

