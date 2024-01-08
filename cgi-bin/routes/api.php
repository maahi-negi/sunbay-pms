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
Route::get('/home', 'API\IndexController@getHome');

// Design Groups
Route::post('/edit-design-group', 'API\DesignController@modifyDesignGroup');
Route::post('/add-design-group', 'API\DesignController@createDesignGroup');
Route::delete('/delete-design-group', 'API\DesignController@deleteDesignGroup');

// Homes
Route::post('/edit-homeplan', 'API\ExteriorController@modifyHome');
Route::post('/add-homeplan', 'API\ExteriorController@createHome');
Route::delete('/delete-homeplan', 'API\ExteriorController@deleteHome');

// ColorLibrary
Route::post('/edit-colorlibrary', 'API\ColorLibraryController@modifyColorLibrary');
Route::post('/add-colorlibrary', 'API\ColorLibraryController@createColorLibrary');
Route::delete('/delete-colorlibrary', 'API\ColorLibraryController@deleteColorLibrary');
Route::get('/get-colors', 'API\ColorLibraryController@getColorLibrary');

// PatternLibrary
Route::post('/edit-patternlibrary', 'API\PatternLibraryController@modifyPatternLibrary');
Route::post('/add-patternlibrary', 'API\PatternLibraryController@createPatternLibrary');
Route::delete('/delete-patternlibrary', 'API\PatternLibraryController@deletePatternLibrary');
Route::get('/get-patterns', 'API\PatternLibraryController@getPatternLibrary');

// Elevations
Route::post('/edit-elevation', 'API\ExteriorController@modifyElevation');
Route::post('/add-elevation', 'API\ExteriorController@createElevation');
Route::post('/duplicate-elevation', 'API\ExteriorController@duplicateElevation');
Route::delete('/delete-elevation', 'API\ExteriorController@deleteElevation');

// Design Types
Route::post('/edit-design-type', 'API\DesignController@modifyDesignType');
Route::post('/add-design-type', 'API\DesignController@createDesignType');
Route::delete('/delete-design-type', 'API\DesignController@deleteDesignType');
Route::get('/get-design-category/{id}', 'API\DesignController@getDesignCategory');

// Home Design Types
Route::post('/edit-home-design-type', 'API\ExteriorOptionsController@modifyDesignType');
Route::post('/add-home-design-type', 'API\ExteriorOptionsController@createDesignType');
Route::delete('/delete-home-design-type', 'API\ExteriorOptionsController@deleteDesignType');
Route::get('/get-home-design-category/{id}', 'API\ExteriorOptionsController@getDesignCategory');
Route::get('/get-sub-category', 'API\ExteriorOptionsController@getDesignSubCategory');

// Designs
Route::delete('/delete-design', 'API\ExteriorOptionsController@deleteDesign');
Route::post('/edit-design', 'API\DesignController@modifyDesign');
Route::post('/add-design', 'API\DesignController@createDesign');
Route::put('/update-default', 'API\DesignController@updateDefault');

// Home Designs
Route::post('/edit-home-design', 'API\ExteriorOptionsController@modifyDesign');
Route::post('/add-home-design', 'API\ExteriorOptionsController@createDesign');
Route::delete('/delete-home-design', 'API\ExteriorOptionsController@deleteDesign');
Route::put('/update-home-default', 'API\ExteriorOptionsController@updateDefault');

Route::post('/add-design-option', 'API\ExteriorOptionsController@addDesignOption');
//Profile
Route::post('/edit-profile', 'API\UsersController@updateProfile');
Route::post('/update-password', 'API\UsersController@updatePassword');

/** Bulk Upload APIs */
// Mega Import
Route::apiresource('/mega-import', 'API\ImportController');
Route::post('/map/sheet/columns','API\ImportController@getColumnsToMap');

Route::post('/get-colors', 'API\IndexController@getColors');


Route::post('/upload-image', 'ImageController@uploadFromUrl');
Route::post('/upload-image-file', 'ImageController@uploadFromFile');
Route::post('/save-texture', 'ImageController@createDesign');



// Ajax Login
Route::post('/user-login', 'API\LoginController@index');
Route::post('/user-check', 'API\LoginController@check');
Route::post('/user-register', 'API\LoginController@register');
Route::post('/forgot-password', 'API\LoginController@forgotPassword');
Route::post('/update-password', 'API\LoginController@updatePassword');
Route::post('/verify-code', 'API\LoginController@VerifyCode');
