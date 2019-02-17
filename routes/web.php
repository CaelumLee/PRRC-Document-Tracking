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
Route::get('/accepted', 'HomeController@accepted')->name('accepted');
Route::get('/inactive', 'HomeController@inactive')->name('inactive');
Route::get('/received', 'HomeController@received')->name('received');
Route::get('/archived', 'HomeController@archived')->name('archived');
Route::get('/dashboard/statistics','AdminDashboard@index')->name('dashboard');
Route::get('/dashboard/users','AdminDashboard@userList')->name('userlists');
Route::get('/dashboard/docutype','AdminDashboard@docuType')->name('docuType');
Route::get('/dashboard/holidays','AdminDashboard@holidays')->name('holidays');

Route::post('/jsonFile', 'AjaxFileController@getJsonFile');

Route::resource('docu', 'DocuController');
Route::post('/restore', 'DocuController@restore');
Route::post('/approve', 'DocuController@approve');
Route::get('/history/{id}', 'HistoryController@history')->name('history');
Route::post('/sendTo', 'ForsendController@sendTo');
Route::post('/routeInfo', 'RouteInfoController@newRouteInfo');
Route::post('/editRouteInfo', 'RouteInfoController@editRouteInfo');

Route::get('/batch', 'BatchController@index')->name('batch');
Route::post('/add', 'BatchController@add');

Route::get('/dynamic_pdf/pdf/{id}', 'DynamicPDFController@pdf');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/notifications', 'NotificationController@createNewDocuNotification');
    Route::get('/readAll', 'NotificationController@readAllNotifications')->name('readAll');
});