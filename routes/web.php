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
Route::get('/program/edit/{id}', 'ProgramController@edit');
Route::post('/program/update/{id}', 'ProgramController@update');
Route::post('/program/delete/{id}', 'ProgramController@destroy');


Route::get('/project', 'ProjectController@index');
Route::post('/project/store', 'ProjectController@store');
Route::get('/project/edit/{id}', 'ProjectController@edit');
Route::post('/project/update/{id}', 'ProjectController@update');
Route::post('/project/delete/{id}', 'ProjectController@destroy');
Route::post('/project/{id}/detail', 'ProjectController@detail');
Route::get('/project/getStatus', function(){
	$arr = App\Status::select('id',"status_name")->get();
	return $arr;
});
Route::get('/project/getPriority', function(){
	$arr = App\Priority::select('id',"priority_type")->get();
	return $arr;
});


Route::get('/company', 'CompanyController@index');
Route::post('/company/store', 'CompanyController@store');
Route::get('/company/edit/{id}', 'CompanyController@edit');
Route::post('/company/update/{id}', 'CompanyController@update');
Route::post('/company/delete/{id}', 'CompanyController@destroy');
Route::get('/company/getResources', function(){
	$arr = App\User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->get();
	return $arr;
});
Route::get('/access/{module_name}/{access_name}', function($module_name, $access_name){

    return json_encode(Gate::allows($access_name.'_'.$module_name));
});


Route::get('/acl/roles', 'AclController@roles');
Route::get('/acl/modules', 'AclController@modules');
Route::get('/acl/actions', 'AclController@actions');
Route::get('/acl/getaccess/{roleid}', 'AclController@access');
Route::post('/acl/update', 'AclController@update');

Route::get('/acl/fakeaccess', 'AclController@fakeaccess');
Route::get('/acl/userdetails', function(){
	return json_encode(auth()->user()->toArray());
});


Route::get('/scope', 'ScopeController@index');
Route::post('/scope/store', 'ScopeController@store');
Route::get('/scope/edit/{id}', 'ScopeController@edit');
Route::post('/scope/update/{id}', 'ScopeController@update');
Route::post('/scope/delete/{id}', 'ScopeController@destroy');
Route::post('/scope/comments/{id}/store', 'ScopeController@storeCommment');
Route::post('/scope/comments/{id}/approvedocument', 'ScopeController@approveDocument');
Route::get('/scope/comments/{crdid}', 'ScopeController@fetchCommments');
Route::get('/scope/documents/{crdid}', 'ScopeController@fetchDocuments');
Route::get('/scope/document/{crdid}', 'ScopeController@fetchApprovedDocument');
// Route::get('/scope/edit/{id}', 'ScopeController@getStory');
Route::post('/project/{projectid}/scope/{crid}', 'UserstoryController@index');
Route::get('/scope/project/{projectid}', 'ScopeController@getProjectScope');
Route::get('/scope/userstory/{scopeid}', 'ScopeController@getScopeUserstory');


Route::get('/userstory/{id}', 'UserstoryController@index');
Route::post('/userstory/store', 'UserstoryController@store');
Route::get('/userstory/edit/{id}', 'UserstoryController@edit');
Route::post('/userstory/update/{id}', 'UserstoryController@update');
Route::post('/userstory/delete/{id}', 'UserstoryController@destroy');
Route::post('/userstory/comments/{id}/store', 'UserstoryController@storeCommment');
Route::post('/userstory/comments/{id}/approvedocument', 'UserstoryController@approveDocument');
Route::get('/userstory/comments/{crdid}', 'UserstoryController@fetchCommments');
Route::get('/userstory/documents/{crdid}', 'UserstoryController@fetchDocuments');
Route::get('/userstory/document/{crdid}', 'UserstoryController@fetchApprovedDocument');
Route::get('/403', function(){
	abort(403, "Permission Denied");
});

Route::get('/download/{file}', 'DownloadsController@download');

Route::post('/task/wps/{projectid}/store', 'TaskController@storeWBS');
Route::get('/task/{projectid}', 'TaskController@index');
Route::get('/task/subtask/{taskid}', 'TaskController@getSubtask');
Route::get('/task/userstory/{projectid}', 'TaskController@getUserstory');
Route::post('/task/store', 'TaskController@store');
Route::get('/task/subtask/{point}/{taskid}', 'TaskController@getRemainingPoints');
Route::get('/user/tasks', 'TaskController@userTasks');
Route::get('/task/edit/{taskid}', 'TaskController@edit');
Route::post('/task/comments/{taskid}/store', 'TaskController@storeComment');
Route::get('/task/allcomments/{taskid}', 'TaskController@fetchCommments');
Route::get('/task/availablehours/{taskpoint}/{taskid}', 'TaskController@fetchAvailableHours');
Route::get('/project/getTaskType', function(){
	$arr = App\TaskType::select('id',"type")->get();
	return $arr;
});