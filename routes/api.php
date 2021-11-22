<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['namespace' => 'Api'], function () {
    //Route::get('/', 'authController@login');
    //    Route::get('students', 'ApiController@getAllStudents');
    //    Route::get('students/{id}', 'ApiController@getStudent');
    //    Route::post('students', 'ApiController@createStudent');
    //    Route::put('students/{id}', 'ApiController@updateStudent');
    //    Route::delete('students/{id}','ApiController@deleteStudent');

    Route::post('rider/signup_validate_number', 'riderController@signup_validate_number');

    Route::post('rider/signup', 'riderController@signup');

    Route::post('rider/login', 'riderController@login');

    Route::post('rider/forgot_validate_number', 'riderController@forgot_validate_number');

    Route::post('rider/forgot_change_password', 'riderController@forgot_change_password');

    Route::post('driver/signup_validate_number', 'driverController@signup_validate_number');

    Route::post('driver/signup', 'driverController@signup');

    Route::post('driver/login', 'driverController@login');

    Route::post('driver/forgot_validate_number', 'driverController@forgot_validate_number');

    Route::post('driver/forgot_change_password', 'driverController@forgot_change_password');

    Route::post('common/country_list', 'commonController@country_list');

    Route::post('common/region_list', 'commonController@region_list');

    Route::post('common/vehicle_type', 'commonController@vehicle_type');

    Route::post('common/vehicle_subtype', 'commonController@vehicle_subtype');

    Route::post('common/vehicle_make', 'commonController@vehicle_make');

    Route::post('common/vehicle_model', 'commonController@vehicle_model');

    Route::post('common/city_list', 'commonController@city_list');

    Route::post('common/config_values', 'commonController@config_values');

    Route::post('common/get_nearby_drivers_for_offline_drive', 'commonController@get_nearby_drivers_for_offline_drive');

    Route::post('common/vehicle_colors', 'commonController@vehicle_colors');
});

Route::group(['middleware' => 'api:rider', 'namespace' => 'Api'], function () {
    Route::post('common/rider_menu', 'commonController@get_rider_menu');

    Route::post('rider/edit_profile', 'riderController@edit_profile');

    Route::post('rider/rider_detail', 'riderController@rider_detail');

    Route::post('rider/change_password', 'riderController@change_password');

    Route::post('rider/logout', 'riderController@logout');

    Route::post('common/get_nearby_drivers', 'commonController@get_nearby_drivers');

    Route::post('common/get_nearby_vehicle_subtypes_with_cost', 'commonController@get_nearby_vehicle_subtypes_with_cost');

    Route::post('common/get_nearby_drivers_for_vehicle_subtype', 'commonController@get_nearby_drivers_for_vehicle_subtype');

    Route::post('ride/apply_promo', 'rideController@apply_promo');

    Route::post('ride/book_ride', 'rideController@book_ride');
    Route::post('ride/book_ride_automatically', 'rideController@book_ride_automatically');
    Route::post('ride/book_ride_hourly', 'rideController@book_ride_hourly');

    Route::post('rider/get_ride', 'rideController@get_ride');
    Route::post('rider/ride_rating', 'rideController@ride_rating');
    Route::post('rider/invoice', 'rideController@invoice');
    Route::post('rider/selected_driver', 'riderController@selected_driver');
    Route::post('rider/ride_cancelled_by_rider', 'rideController@ride_cancelled_by_rider');
    Route::post('rider/payment', 'paymentController@payment');
    Route::post('rider/select_offline_driver', 'riderController@select_offline_driver');
    Route::post('rider/ride_history', 'rideController@ride_history');
    Route::post('rider/ride_details', 'rideController@ride_details');
    Route::post('rider/complaint', 'complaintController@complaint');
    Route::post('rider/complaint_reply', 'complaintController@complaint_reply');
    Route::post('rider/complaint_list', 'complaintController@complaint_list');
    Route::post('rider/complaint_details', 'complaintController@complaint_details');
    Route::post('rider/change_fcm_token', 'riderController@change_fcm_token');

    Route::get('/rider', function () {
        echo "hello rider apis";
    });
});

Route::group(['middleware' => 'api:driver', 'namespace' => 'Api'], function () {

    Route::post('driver/update_driver_latitute_longitude', 'driverController@update_driver_latitute_longitude');
    Route::post('driver/ride_accept_or_reject_notification', 'driverController@ride_accept_or_reject_notification');
    Route::post('driver/book_ride', 'driverController@book_ride');
    Route::post('driver/arrived_at_location', 'driverController@arrived_at_location');
    Route::post('driver/start_ride', 'driverController@start_ride');
    Route::post('driver/arrived_at_destination', 'driverController@arrived_at_destination');
    Route::post('driver/ride_rating', 'rideController@ride_rating');
    Route::post('driver/end_ride', 'rideController@end_ride');
    Route::post('driver/invoice', 'rideController@invoice');
    Route::post('driver/change_password', 'driverController@change_password');
    Route::post('driver/view_profile', 'driverController@view_profile');
    Route::post('driver/edit_profile', 'driverController@edit_profile');
    Route::post('driver/vehicle_info', 'driverController@vehicle_info');
    Route::post('driver/edit_vehicle_info', 'driverController@edit_vehicle_info');
    Route::post('driver/ride_cancelled_by_driver', 'rideController@ride_cancelled_by_driver');
    Route::post('driver/get_ride', 'rideController@get_ride');
    Route::post('driver/confirm_cash_payment', 'paymentController@confirm_cash_payment');
    Route::post('driver/change_online_status', 'driverController@change_online_status');
    Route::post('driver/logout', 'driverController@logout');
    Route::post('driver/enter_rider_id_to_start_offline_drive', 'rideController@enter_rider_id_to_start_offline_drive');
    Route::post('driver/end_offline_drive', 'rideController@end_offline_drive');
    Route::post('driver/ride_history', 'rideController@ride_history');
    Route::post('driver/ride_details', 'rideController@ride_details');
    Route::post('driver/earning_list', 'driverController@earning_list');
    Route::post('driver/complaint', 'complaintController@complaint');
    Route::post('driver/complaint_reply', 'complaintController@complaint_reply');
    Route::post('driver/complaint_list', 'complaintController@complaint_list');
    Route::post('driver/complaint_details', 'complaintController@complaint_details');
    Route::post('driver/change_fcm_token', 'driverController@change_fcm_token');
    Route::post('driver/change_password', 'driverController@change_password');
});
