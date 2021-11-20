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
    return redirect(url('/login'));
});

Auth::routes(); 

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/accessdenied', 'HomeController@access')->name('accessdenied');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/access-denied', function(){
	return view('access_denied');
})->name('access-denied');

#ManageUser - vinothcl
Route::group(['prefix' => '/manage-user', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-user'], function () {
    Route::get('/', 'ManageUserController@index')->name('manage-user');
    Route::get('/get-user-list-ajax', 'ManageUserController@getUserListAjax')->name('getUserListAjax');
    Route::get('/add', 'ManageUserController@add')->name('manage-user-add');
    Route::post('/save', 'ManageUserController@save')->name('manage-user-save');
    Route::post('/update', 'ManageUserController@update')->name('manage-user-update');
    Route::get('/edit/{id}', 'ManageUserController@edit')->name('manage-user-edit');
    Route::any('/delete/{id}', 'ManageUserController@delete')->name('manage-user-delete');
});

#ManageEvents - vinothcl
Route::group(['prefix' => '/manage-event', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-event'], function () {
    Route::get('/', 'ManageEventController@index')->name('manage-event');
    Route::get('/get-event-list-ajax', 'ManageEventController@getEventListAjax')->name('getEventListAjax');
    Route::get('/view-events', 'ManageEventController@viewEventsAdmin')->name('manage-event-view-events');
    Route::post('/add', 'ManageEventController@add')->name('manage-event-add');
    Route::post('/save', 'ManageEventController@save')->name('manage-event-save');
    Route::get('/edit/{id}', 'ManageEventController@edit')->name('manage-event-edit');
    Route::post('/update', 'ManageEventController@update')->name('manage-event-update');
    Route::any('/delete/{id}', 'ManageEventController@delete')->name('manage-event-delete');
});

#ManageDocuments - sivaramesh  
Route::group(['prefix' => '/manage-document', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-event'], function () {
    Route::get('/', 'ManageDocumentController@index')->name('manage-document');
    Route::get('/get-document-list-ajax', 'ManageDocumentController@getDocumentListAjax')->name('getDocumentListAjax');
    Route::get('/add', 'ManageDocumentController@add')->name('manage-document-add');
    Route::post('/save', 'ManageDocumentController@save')->name('manage-document-save');
    Route::post('/update', 'ManageDocumentController@update')->name('manage-document-update');
    Route::get('/edit/{id}', 'ManageDocumentController@edit')->name('manage-document-edit');
    Route::any('/delete/{id}', 'ManageDocumentController@delete')->name('manage-document-delete');
    Route::any('/import', 'ManageDocumentController@import')->name('manage-document-import');

});

#ManageDirectories - syamala
Route::group(['prefix' => '/manage-contractor-directories', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-contractor-directories'], function () {
    Route::get('/', 'ManageContractorDirectoriesController@index')->name('manage-contractor-directories');
    Route::get('/get-contractor-directories-list-ajax', 'ManageContractorDirectoriesController@getdirectoriesListAjax')->name('getdirectoriesListAjax');
    Route::get('/add', 'ManageContractorDirectoriesController@add')->name('manage-contractor-directories-add');
    Route::post('/save', 'ManageContractorDirectoriesController@save')->name('manage-contractor-directories-save');
    Route::post('/update', 'ManageContractorDirectoriesController@update')->name('manage-contractor-directories-update');
    Route::get('/edit/{id}', 'ManageContractorDirectoriesController@edit')->name('manage-contractor-directories-edit');
    Route::any('/delete/{id}', 'ManageContractorDirectoriesController@delete')->name('manage-contractor-directories-delete');
    Route::any('/import', 'ManageContractorDirectoriesController@import')->name('manage-contractor-directories-import');
});

#ManageJatcDirectories - vinothcl
Route::group(['prefix' => '/manage-jatc-directories', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-jatc-directories'], function () {
    Route::get('/', 'ManageJatcDirectoriesController@index')->name('manage-jatc-directories');
    Route::get('/get-jatc-directories-list-ajax', 'ManageJatcDirectoriesController@getJatcDirectoriesListAjax')->name('getJatcDirectoriesListAjax');
    Route::get('/add', 'ManageJatcDirectoriesController@add')->name('manage-jatc-directories-add');
    Route::post('/save', 'ManageJatcDirectoriesController@save')->name('manage-jatc-directories-save');
    Route::post('/update', 'ManageJatcDirectoriesController@update')->name('manage-jatc-directories-update');
    Route::get('/edit/{id}', 'ManageJatcDirectoriesController@edit')->name('manage-jatc-directories-edit');
    Route::any('/delete/{id}', 'ManageJatcDirectoriesController@delete')->name('manage-jatc-directories-delete');
    Route::any('/import', 'ManageJatcDirectoriesController@import')->name('manage-jatc-directories-import');
});

#ManageIbewDirectories - vinothcl
Route::group(['prefix' => '/manage-ibew-directories', 'middleware' => ['auth', 'IsSiteAdmin'], 'page-group' => '/manage-ibew-directories'], function () {
    Route::get('/', 'ManageIbewDirectoriesController@index')->name('manage-ibew-directories');
    Route::get('/get-ibew-directories-list-ajax', 'ManageIbewDirectoriesController@getIbewDirectoriesListAjax')->name('getIbewDirectoriesListAjax');
    Route::get('/add', 'ManageIbewDirectoriesController@add')->name('manage-ibew-directories-add');
    Route::post('/save', 'ManageIbewDirectoriesController@save')->name('manage-ibew-directories-save');
    Route::post('/update', 'ManageIbewDirectoriesController@update')->name('manage-ibew-directories-update');
    Route::get('/edit/{id}', 'ManageIbewDirectoriesController@edit')->name('manage-ibew-directories-edit');
    Route::any('/delete/{id}', 'ManageIbewDirectoriesController@delete')->name('manage-ibew-directories-delete');
    Route::any('/import', 'ManageIbewDirectoriesController@import')->name('manage-ibew-directories-import');
});

#ManageChapterDirectories - sivaramesh  
Route::group(['prefix' => '/manage-chapter-directories', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-chapter-directories'], function () {
    Route::get('/', 'ManageChapterDirectoriesController@index')->name('manage-chapter-directories');
    Route::get('/get-chapter-directories-list-ajax', 'ManageChapterDirectoriesController@getChapterDirectoriesListAjax')->name('getChapterDirectoriesListAjax');
    Route::get('/add', 'ManageChapterDirectoriesController@add')->name('manage-chapter-directories-add');
    Route::post('/save', 'ManageChapterDirectoriesController@save')->name('manage-chapter-directories-save');
    Route::post('/update', 'ManageChapterDirectoriesController@update')->name('manage-chapter-directories-update');
    Route::get('/edit/{id}', 'ManageChapterDirectoriesController@edit')->name('manage-chapter-directories-edit');
    Route::any('/delete/{id}', 'ManageChapterDirectoriesController@delete')->name('manage-chapter-directories-delete');
    Route::any('/import', 'ManageChapterDirectoriesController@import')->name('manage-chapter-directories-import');
});

#ManageContractorResourceController - vinothcl
Route::group(['prefix' => '/manage-contractor-resources', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-contractor-resources'], function () {
    Route::get('/', 'ManageContractorResourceController@index')->name('manage-contractor-resources');
    Route::get('/get-contractor-resources-list-ajax', 'ManageContractorResourceController@getContractorResourcesListAjax')->name('getContractorResourcesListAjax');
    Route::get('/add', 'ManageContractorResourceController@add')->name('manage-contractor-resources-add');
    Route::post('/save', 'ManageContractorResourceController@save')->name('manage-contractor-resources-save');
    Route::get('/edit/{id}', 'ManageContractorResourceController@edit')->name('manage-contractor-resources-edit');
    Route::post('/update', 'ManageContractorResourceController@update')->name('manage-contractor-resources-update');
    Route::any('/delete/{id}', 'ManageContractorResourceController@delete')->name('manage-contractor-resources-delete');
});

#DocumentType - vinothcl
Route::group(['prefix' => '/manage-document-type', 'middleware' => ['auth', 'IsSiteAdmin'], 'page-group' => '/manage-document-type'], function () {
    Route::get('/', 'ManageDocumentTypeController@index')->name('manage-document-type');
    Route::get('/get-document-type-list-ajax', 'ManageDocumentTypeController@getDocumentTypeListAjax')->name('getDocumentTypeListAjax');
    Route::get('/add', 'ManageDocumentTypeController@add')->name('manage-document-type-add');
    Route::post('/save', 'ManageDocumentTypeController@save')->name('manage-document-type-save');
    Route::post('/update', 'ManageDocumentTypeController@update')->name('manage-document-type-update');
    Route::get('/edit/{id}', 'ManageDocumentTypeController@edit')->name('manage-document-type-edit');
    Route::any('/delete/{id}', 'ManageDocumentTypeController@delete')->name('manage-document-type-delete');
});

#Notification - vinothcl
Route::group(['prefix' => '/manage-notification', 'middleware' => ['auth', 'IsChapterAdmin'], 'page-group' => '/manage-notification'], function () {
    Route::get('/', 'ManageNotificationController@index')->name('manage-notification');
    Route::get('/get-notification-list-ajax', 'ManageNotificationController@getNotificationListAjax')->name('getNotificationListAjax');
    Route::get('/add', 'ManageNotificationController@add')->name('manage-notification-add');
    Route::post('/save', 'ManageNotificationController@save')->name('manage-notification-save');
    Route::post('/update', 'ManageNotificationController@update')->name('manage-notification-update');
    Route::get('/edit/{id}', 'ManageNotificationController@edit')->name('manage-notification-edit');
    Route::any('/delete/{id}', 'ManageNotificationController@delete')->name('manage-notification-delete');
});



#Locations- sivaramesh 
Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {     
    Route::any('/document-get-state', 'ManageDocumentController@getDocumentState')->name('document-get-state');
    Route::any('/document-get-union', 'ManageDocumentController@getDocumentUnion')->name('document-get-union');
    Route::any('/document-get-union-group', 'ManageDocumentController@getDocumentUnionGroup')->name('document-get-union-group');    
});

Route::group(['prefix' => '/view-user', 'middleware' => ['auth'], 'page-group' => '/view-user'], function () { 
    Route::get('/documents', 'ManageDocumentController@view')->name('view-user-documents');
    Route::get('/event', 'ManageEventController@listView')->name('view-user-events');
    Route::get('/event-details/{id}', 'ManageEventController@viewDetails')->name('view-user-events-details');
    Route::get('/event-view-cal', 'ManageEventController@viewEventsUsers')->name('view-user-event-view-cal');
    Route::get('/get-document-list-ajax', 'ManageDocumentController@getDocumentListAjax')->name('getDocumentListAjaxUserView');
    Route::get('/get-event-list-ajax', 'ManageEventController@getEventListAjax')->name('getEventListAjaxUserView');
    //User Directories
    Route::get('/contractor-directories', 'ManageContractorDirectoriesController@view')->name('view-user-contractor-directories');
    Route::get('/get-contractor-directories-list-ajax', 'ManageContractorDirectoriesController@getdirectoriesListAjax')->name('getContractordirectoriesListAjaxUserView');  
    Route::get('/jatc-directories', 'ManageJatcDirectoriesController@view')->name('view-user-jatc-directories');
    Route::get('/get-jatc-directories-list-ajax', 'ManageJatcDirectoriesController@getJatcdirectoriesListAjax')->name('getJatcdirectoriesListAjaxUserView');
    Route::get('/ibew-directories', 'ManageIbewDirectoriesController@view')->name('view-user-ibew-directories');
    Route::get('/get-ibew-directories-list-ajax', 'ManageIbewDirectoriesController@getIbewdirectoriesListAjax')->name('getIbewdirectoriesListAjaxUserView');
    Route::get('/chapter-directories', 'ManageChapterDirectoriesController@view')->name('view-user-chapter-directories');
    Route::get('/get-chapter-directories-list-ajax', 'ManageChapterDirectoriesController@getChapterDirectoriesListAjax')->name('getChapterDirectoriesListAjaxUserView');
});

#User Actions - vinothcl
Route::group(['prefix' => '/user', 'middleware' => ['auth'], 'page-group' => '/user'], function () { 
    Route::get('/', 'UserController@index')->name('user-index');
    //Route::any('/contact', 'UserController@contact')->name('user-contact');
    //Route::get('/directory', 'UserController@directory')->name('user-directory');
    Route::get('/settings', 'UserController@settings')->name('user-settings');
    Route::post('/settings-post', 'UserController@settingsPost')->name('user-settings-post');    
    Route::any('/change-password', 'UserController@changePassword')->name('user-change-password');
   
    Route::get('/resource', 'UserController@resource')->name('user-resource');
    Route::get('/announcement', 'UserController@announcement')->name('user-announcement');
    Route::get('/calendar', 'UserController@calendar')->name('user-calendar');
    Route::get('/agreement', 'UserController@agreement')->name('user-agreement');

    Route::get('/logout', function(){
        \Auth::logout();
        return redirect(route('login'));
    })->name('user-logout');
});

Route::group(['prefix' => '/user',  'page-group' => '/user'], function () {

     Route::any('/contact', 'UserController@contact')->name('user-contact');
      Route::get('/directory', 'UserController@directory')->name('user-directory');

});


#Notifications - vinothcl
Route::group(['prefix' => '/notifications', 'middleware' => ['auth']], function () {
    Route::any('/', 'API\NotificationController@getAllNotification')->name('getAllNotification');
    Route::any('/action', 'API\NotificationController@notificationAction')->name('notificationAction');
}); 