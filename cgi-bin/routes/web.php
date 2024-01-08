<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::get('/', 'IndexController@homePage')->name('landingPage.exterior');

Route::get('/homes', 'ExteriorController@homes')->name('homes.exterior');

Route::group(['prefix' => 'admin', 'middleware'=>'auth'], function () {
    // Dashboard
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('home')->defaults('menu', 'dashboard');

    // Homeplans
    Route::get('/homeplans', 'Admin\HomeController@homeplans')->name('homeplans')->defaults('menu', 'homeplans');
    Route::get('/homeplans/elevations/{home_id}', 'Admin\HomeController@elevations')->name('elevations')->defaults('menu', 'homeplans');

    // Exterior URLs
    Route::get('/exterior', 'Admin\HomeController@exterior')->name('exterior')->defaults('menu', 'exterior');
    Route::get('/exterior/design-types/{elevation_id}', 'Admin\HomeController@designTypes')->name('home-design-types')->defaults('menu', 'exterior');
    Route::get('/exterior/elevations/design-types/designs/{home_design_type_id}/{elevation_id}', 'Admin\HomeController@designs')->name('home-designs')->defaults('menu', 'exterior');

    Route::get('/homeplan-features/{homeplan_id}', 'Admin\FloorplanController@features')->name('homeplan-features')->defaults('menu', 'floorplan');
    Route::get('/homeplan-acl-settings/{homeplan_id}', 'Admin\FloorplanController@aclSettings')->name('homeplan-acl-settings')->defaults('menu', 'floorplan');
    Route::post('/save-acl-settings', 'Admin\FloorplanController@saveAclSettings')->name('save-acl-settings')->defaults('menu', 'floorplan');
    Route::get('/add-acl-setting/{home_floorplan_id}', 'Admin\FloorplanController@addAclSetting')->name('add-acl-setting')->defaults('menu', 'floorplan');
    // Profile and Settigns
    Route::get('/profile', 'Admin\UsersController@index')->name('profile')->defaults('menu', 'profile');
    Route::get('settings', 'Admin\SettingsController@index')->name('settings');
    Route::post('settings/save', 'Admin\SettingsController@update');

    // Bulk Upload
    Route::get('/uploads/data','Admin\BulkUploadController@returnBulkDataView')->name('bulk-data');
    Route::post('/uploads/images','Admin\BulkUploadController@storeImgTemporary')->name('bulk-image-upload');


    Route::post('upload-csv', 'Admin\DashboardController@uploadCsv')->name('upload-all');



    Route::get('/colorlibrary', 'Admin\HomeController@colorlibrary')->name('colorlibrary')->defaults('menu', 'colorlibrary');
    Route::get('/patternlibrary', 'Admin\HomeController@patternlibrary')->name('patternlibrary')->defaults('menu', 'patternlibrary');
});

Route::get('/{slug?}', 'ExteriorController@index')->name('front.exterior');

Route::get('/user_logout', 'Auth\LoginController@userLogout')->name('user.logout');
