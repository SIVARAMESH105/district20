<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

#User - vinothcl
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('change-password', 'API\UserController@changePassword');
Route::post('forget-password', 'API\UserController@forgetPassword');
Route::get('get-chapter-admin-contacts', 'API\UserController@getChapterAdminContact');
Route::get('get-chapter-directory-list', 'API\DirectoryController@getChapterDirectoryList');

Route::group(['middleware' => 'auth:api'], function(){
	#UserDetails - vinothcl
	Route::post('user-details', 'API\UserController@details');
	Route::post('settings', 'API\UserController@settingsPost');
	Route::get('settings', 'API\UserController@settingsGet');	
	Route::post('logout', 'API\UserController@logout');
	#Location - vinothcl
	Route::group(['prefix' => '/location'], function () {
	    Route::any('/chapters', 'API\LocationController@getChapters')->name('getChapters');
	    Route::any('/states', 'API\LocationController@getStates')->name('getStates');
    	Route::any('/unions', 'API\LocationController@getUnions')->name('getUnions');
    	Route::any('/state-codes', 'API\LocationController@getStateCodes')->name('getStateCodes');
    	Route::any('/districts', 'API\LocationController@getDistricts')->name('getDistricts');
	});
	#Documents - vinothcl
	Route::group(['prefix' => '/document'], function () {
	    Route::any('/', 'API\DocumentController@getDocuments')->name('getDocuments');
	    Route::any('/types', 'API\DocumentController@getDocumentTypes')->name('getDocumentTypes');   
	});
	#Directories - vinothcl
	Route::group(['prefix' => '/directory'], function () {
	    Route::any('/', 'API\DirectoryController@getDirectories')->name('getDirectories');  
	});
	#Events - vinothcl
	Route::group(['prefix' => '/event'], function () {
	    Route::any('/', 'API\EventController@getEvents')->name('getEvents');   
	});
	#ContractorResource - vinothcl
	Route::group(['prefix' => '/contractor-resource'], function () {
	    Route::any('/', 'API\ContractorResourceController@getContractorResource')->name('getContractorResource');   
	});
	#Notifications - vinothcl
	Route::group(['prefix' => '/notifications'], function () {
	    Route::any('/', 'API\NotificationController@getAllNotification')->name('getAllNotification');
	    Route::any('/action', 'API\NotificationController@notificationAction')->name('notificationAction');
	});
});

Route::any('location/chapters', 'API\LocationController@getChapters')->name('getChapters');