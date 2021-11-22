<?php

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

//Route::get('ID/{id}',function($id) {
//   echo 'ID: '.$id;
//});
//Route::get('user/{name?}', function ($name = 'TutorialsPoint') { 
//    echo  $name;
//    
//});
// Route::get('/', function () {

//     return redirect()->route('login');

//     //return view('welcome');
// });
//route redirect to login page
Route::get('/', function () {
    return redirect()->route('login');
});

//static pages
Route::group(['namespace' => 'Admin'], function () {
    Route::get('/about', 'configController@about_page_contents');
    Route::get('/help', 'configController@help_page_contents');
    Route::get('/eula', 'configController@eula_page_contents');
    Route::get('/terms-and-condition', 'configController@terms_and_condition_page_contents');
    Route::get('/privacy-policy', 'configController@privacy_policy_page_contents');
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //Route::get('/', 'authController@login');
    Route::post('/checklogin', 'authController@checklogin');

    Route::get('/', [
        'as' => 'login',
        'uses' => 'authController@login'
    ]);

    Route::get('/forgot', [
        'uses' => 'authController@forgot'
    ]);

    Route::get('/logout', 'authController@logout');
    Route::get('/cron/forward_request', 'cronController@forward_request');
    Route::get('/cron/automatic_cancel_ride', 'cronController@automatic_cancel_ride');
    Route::get('config/about_page', 'configController@about_page');
    Route::get('config/help_page', 'configController@help_page');
    Route::get('config/terms_and_condition_page', 'configController@terms_and_condition_page');
    Route::get('config/eula_page', 'configController@eula_page');
    Route::get('config/privacy_policy_page', 'configController@privacy_policy_page');
    Route::post('config/update_about_page', 'configController@update_about_page');
    Route::post('config/update_help_page', 'configController@update_help_page');
    Route::post('config/update_terms_and_condition_page', 'configController@update_terms_and_condition_page');
    Route::post('config/update_eula_page', 'configController@update_eula_page');
    Route::post('config/update_privacy_policy_page', 'configController@update_privacy_policy_page');
});
Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function () {



    Route::get('/home', [
        'as' => 'dashboard',
        // 'middleware' => 'admin',
        'uses' => 'homeController@index'
    ]);

    //    Route::resource('/vehicletype', [
    //        'middleware' => 'admin',
    //        'uses' => 'vehicletypeController'
    //    ]);
    Route::get('/profile', 'authController@profile');

    Route::post('/update_profile', 'authController@update_profile');

    Route::resource('/vehicletype', 'vehicletypeController');

    Route::resource('/vehicle_subtype', 'vehicle_subtypeController');

    Route::resource('/vehicle_make', 'vehicle_makeController');

    Route::resource('/vehicle_model', 'vehicle_modelController');

    Route::resource('/rider', 'riderController');

    Route::resource('/driver', 'driverController');
    Route::resource('/promo_code', 'promo_codeController');

    //Route::resource('/content_management', 'content_managementController');

    //Route::any('/content_management/privacy_policy','content_managementController@privacy_policy');
    Route::get('/content_management/{page_name}', 'content_managementController@index');

    Route::post("/content_management/content_update", 'content_managementController@content_update');

    // Route::post('/content_management/{page_name}', [PostController::class, 'content_update']);


    Route::get('/driver/edit_vehicle_info/{id}', 'driverController@edit_vehicle_info');

    Route::post('/driver/update_vehicle_info/{id}', 'driverController@update_vehicle_info');

    Route::get('/driver/edit_documents/{id}', 'driverController@edit_documents');

    Route::post('/driver/update_documents/{id}', 'driverController@update_documents');

    Route::get('/drivers/map_view', 'driverController@map_view');

    Route::post('drivers/get_all_drivers', 'driverController@get_all_drivers');


    Route::resource('/rider_menu', 'rider_menuController');

    Route::get('service_area/country', 'service_areaController@country');
    Route::get('service_area/region', 'service_areaController@region');
    Route::get('service_area/city', 'service_areaController@city');

    Route::post('common/get_selected_subtype_list', 'commonController@get_selected_subtype_list');
    Route::post('common/location', 'commonController@location');

    Route::post('common/get_selected_vehicle_subtypes', 'commonController@get_selected_vehicle_subtypes');
    Route::post('common/get_selected_vehicle_makes', 'commonController@get_selected_vehicle_makes');
    Route::post('common/get_selected_vehicle_models', 'commonController@get_selected_vehicle_models');

    Route::post('common/updatestatus', 'commonController@updatestatus');


    Route::get('config/commission', 'configController@commission');

    Route::post('config/commission', 'configController@update_commission');

    Route::get('config/general', 'configController@general');

    Route::post('config/general', 'configController@update_general');

    //Route::get('rides/index/{type?}/{id?}', 'ridesController@index');

    Route::resource('/ride', 'rideController');

    Route::resource('/ride_rating', 'ride_ratingController');

    Route::resource('/ticket', 'ticketController');
});


//Route::group(['prefix' => 'admin','middleware' => 'auth','namespace' => 'Admin'], function() {
//    
//
//});

//Route::get('/admin', 'Admin\authController@login');


// Route::get('/about', function () {
//     return view('about');
// });



Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";
});
