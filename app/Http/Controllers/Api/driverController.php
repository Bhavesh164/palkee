<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Api\ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Api\commonModel;
use App\Model\Api\driverModel;
use App\Model\Api\rider;
use App\Model\Api\vehicle;
use App\Support\firebase;
use Google\Cloud\Core\Timestamp;
use Mail;

//use App\Model\Admin\vehicletype;
//use App\Model\Admin\vehicle_make;
//use App\Model\Admin\vehicle_subtype;

class driverController extends Controller
{
    public function update_driver_latitute_longitude(Request $request)
    {
        $data['id'] = $request->driver_id;
        $data['lat'] =  $request->lat;
        $data['lon'] = $request->lon;
        $data['current_address'] = $request->address;
        DB::table('driver')->where('id', $data['id'])->update($data);
        $res['status'] = 1;
        $res['message'] = 'Success';
        return response($res);
    }

    public function ride_accept_or_reject_notification(Request $request)
    {
        $driver_id = $request->driver_id;
        $ride_details = (new driverModel)->ride_accept_or_reject_notification($driver_id);
        if ($ride_details) {
            $rider_rating = (new rider)->rider_rating($ride_details->rider_id);
            $rider_detail = (new rider)->find($ride_details->rider_id);
            $ride_details->rider_rating = $rider_rating;
            $ride_details->rider_name = $rider_detail->full_name;
            $ride_details->rider_driver_distance =  round($ride_details->rider_driver_distance / 1000);
            $ride_details->rider_driver_duration =  round($ride_details->rider_driver_duration / 60);
            if (trim($rider_detail->image) != '') {
                $ride_details->rider_image = config("global.RIDER_IMAGE_PATH") . $rider_detail->image;
            } else {
                $ride_details->rider_image = "";
            }
            $res['data'] = $ride_details;
            $res['status'] = 1;
            $res['message'] = 'Success';
        } else {
            $res['status'] = 0;
            $res['message'] = 'No record found';
        }
        return response($res);
    }

    public function book_ride(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        $status = check_ride_status($ride_id);
        if ($status == 1) {
            DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '2', 'accepted_on' => date("Y-m-d H:i:s")]);
            DB::table('driver')->where('id', $ride_details->driver_id)->update(["on_ride" => '1']);
            $rider_details = (new rider)->find($ride_details->rider_id);
            $vehicle_history = (new vehicle)->get_vehicle_details_from_driver($ride_details->driver_id);
            $vehicle_history->ride_id = $ride_details->id;
            $vehicle_driver_history = (new driverModel)->vehicle_driver_history((array)$vehicle_history);
            // notification data
            $notification_data = [
                "driver_id" => $ride_details->driver_id,
                "ride_id" => $ride_id,
                "title" => "Ride booked successfully",
                "message" => "Driver has accepeted your ride request",
                "created_at" => date("Y-m-d H:i:s")
            ];
            send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type, 1);
            $res['status'] = 1;
            $res['message'] = 'Ride booked successfully';
            //notification data
        } else {
            $ride_details = (new ride)->find($ride_id);
            if ($ride_details->driver_id == $request->driver_id && $ride_details->ride_status_id == 2) {
                $res['status'] = 0;
                $res['message'] = 'The ride is already accepted by you';
            } else {
                $res['status'] = 0;
                $res['message'] = 'The ride is cancelled by Rider';
            }
        }
        return response($res);
    }

    public function arrived_at_location(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        $rider_details = (new rider)->find($ride_details->rider_id);
        DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '4', "arrived_on_rider_location" => date("Y-m-d H:i:s")]);
        // notification data
        $notification_data = [
            "rider_id" => $ride_details->ride_id,
            "ride_id" => $ride_id,
            "title" => "Driver has reached your location",
            "message" => "Driver has reached your location",
            "created_at" => date("Y-m-d H:i:s")

        ];
        send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type, 1);
        //notification data
        $res['status'] = 1;
        $res['message'] = 'You have arrived at rider location';
        return response($res);
    }

    public function start_ride(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        $rider_details = (new rider)->find($ride_details->rider_id);
        $data['pickup_time'] = date('Y-m-d H:i:s');
        $data['rider_driver_distance'] = 0;
        $data['rider_driver_duration'] = 0;
        $data['ride_status_id'] = 5;
        $data['started_ride_on'] = date("Y-m-d H:i:s");
        $update_ride = (new ride)->update_ride($ride_id, $data);
        // notification data
        $notification_data = [
            "title" => "Ride Started",
            "text" => "You will reach your destination soon",
            "type" => "ride_pickup",
            "ride_id" => $ride_id,
            "created_at" => date("Y-m-d H:i:s"),
        ];
        send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type, 1);
        //notification data
        $res['status'] = 1;
        $res['message'] = 'Ride Started';
        return response($res);
    }

    public function arrived_at_destination(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        $rider_details = (new rider)->find($ride_details->rider_id);
        $data['pickup_time'] = date('Y-m-d H:i:s');
        $data['rider_driver_distance'] = 0;
        $data['rider_driver_duration'] = 0;
        $data['ride_status_id'] = 6;
        $update_ride = (new ride)->update_ride($ride_id, $data);
        // notification data
        $notification_data = [
            "title" => "Ride Completed",
            "text" => "You will reached your destination",
            "type" => "ride_dropped",
            "ride_id" => $ride_id,
            "created_at" => date("Y-m-d H:i:s"),
        ];
        send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type, 1);
        //notification data
        $res['status'] = 1;
        $res['message'] = 'Ride Completed';
        return response($res);
    }

    public function signup_validate_number(Request $request)
    {
        $errors = array();
        $rules = [
            'phone' => 'required',
            'email' => 'required',
            'dial_code' => 'required',
            'full_name' => 'required',
            'password'  => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'phone' => 'Phone Number',
            'full_name' => 'Name',
            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $dial_code =  $request->dial_code;
        $phone = $request->phone;
        $email = $request->email;

        if ($phone && $dial_code) {
            $where_array = [
                'dial_code' => $request->dial_code,
                'phone' => $request->phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if ($phone_exist) {
                $errors[] = "Phone Number Already Exist";
            } else {
                $message =  "Phone Number Validate";
            }
        }

        if ($email) {
            $where_array = [
                'email' => $request->email,
            ];
            $email_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if ($email_exist) {
                $errors[] = "Email Already Exist";
            }
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }

        return response($response);
    }
    public function signup(Request $request)
    {
        require_once('app/Support/firebase.php');
        $errors = array();
        $rules = [
            'phone' => 'required',
            'email' => 'email',
            'dial_code' => 'required',
            'full_name' => 'required',
            'password'  => 'required',
            'firebase_token' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'phone' => 'Phone Number',
            'full_name' => 'Name',
            'dial_code' => 'Dail Code'

        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $dial_code =  $request->dial_code;
        $phone = $request->phone;
        $name =  $request->full_name;
        $firebase_token = $request->firebase_token;
        $password  =  $request->password;
        $email  =  $request->email;
        if ($phone && $dial_code) {
            $where_array = [
                'dial_code' => $request->dial_code,
                'phone' => $request->phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if ($phone_exist) {
                $errors[] = "Phone Number Already Exist";
            }
        }

        if ($email) {
            $where_array = [
                'email' => $request->email,
            ];
            $email_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if ($email_exist) {
                $errors[] = "Email Already Exist";
            }
        }

        if (empty($errors)) {
            try {
                $firebase = firebase::init();
                $firebase_user = firebase::verify_token($firebase, $firebase_token);

                if (!empty($firebase_user)) {
                    $phone_no_with_dial_code = $dial_code . $phone;

                    if ($firebase_user->phoneNumber == $phone_no_with_dial_code) {

                        $token = generateRandomString(15);
                        $fieldvalues = [
                            'password' => Hash::make($password),
                            'full_name' => $name,
                            'dial_code' => $dial_code,
                            'phone' => $phone,
                            'email' => $email,
                            'fcm_id' => '',
                            'is_online' => '1',
                            'token' => $token,
                            'is_verified' => '1',
                            'last_login_on' => date('Y-m-d H:i:s'),
                            'device_type' => $request->device_type,
                            'version_code' => $request->version_code,
                            'version_name' => $request->version_name,
                            'os_version' =>  $request->os_version,
                            'device_name' => $request->device_name
                        ];

                        $driver = driverModel::create($fieldvalues);

                        $id = $driver->id;
                        $driver->driver_code = 'DL00' . $id;

                        //$rider
                        $driver->save();

                        $message = "Registration Successful";

                        $data = [
                            'driver_id' => $id,
                            'token' => $token,
                            'sos_number' => get_config()['sos_number'],
                            'online' => '1'
                        ];
                    } else {
                        $errors[] = "OTP not verified";
                    }
                } else {
                    $errors[] = "Something Went Wrong with OTP Verification";
                }
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e->getMessage());
                die;
                $errors[] = "Something Went Wrong with Registration";
            }
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);
            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $this->welcome_mail($request->full_name, $request->email);
            $response['status'] = '1';
            $response['message'] = $message;
            $response['data']  = $data;
        }
        return response($response);
    }

    public function login(Request $request)
    {
        $errors = array();
        $rules = [
            'phone' => 'required',
            'dial_code' => 'required',
            'password'  => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'phone' => 'Phone Number',
            'dial_code' => 'Dail Code'

        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $dial_code =  $request->dial_code;
        $phone =  $request->phone;
        $password =  $request->password;

        if (empty($errors)) {
            $driver = driverModel::where([
                ['dial_code', '=', $dial_code],
                ['phone', '=', $phone],
            ])->first();
            if ($driver) {
                $driver_password = $driver->password;
                if (Hash::check($password, $driver_password)) {
                    $message = "Login Successfull";
                    $token = generateRandomString(15);
                    $driver->token = $token;
                    $driver->save();
                    $data = [
                        'driver_id' => $driver->id,
                        'token' => $token,
                        'online' => $driver->last_online_status,
                        'sos_number' => get_config()['sos_number'],
                    ];
                } else {

                    $errors[] = 'Password is incorrect';
                }
            } else {
                $errors[] = 'Phone Number not exist';
            }
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
            $response['data'] = $data;
        }
        return response($response);
    }

    public function forgot_validate_number(Request $request)
    {
        $errors = array();
        $rules = [
            'phone' => 'required',
            'dial_code' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'phone' => 'Phone Number',
            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $dial_code =  $request->dial_code;
        $phone = $request->phone;

        if ($phone && $dial_code) {
            $where_array = [
                'dial_code' => $request->dial_code,
                'phone' => $request->phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if ($phone_exist) {
                $message =  "Phone Number Validate";
            } else {
                $errors[] = "Phone Number not Registered";
            }
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }
        return response($response);
    }

    public function forgot_change_password(Request $request)
    {
        require_once('app/Support/firebase.php');
        $errors = array();
        $rules = [
            'phone' => 'required',
            'dial_code' => 'required',
            'password'  => 'required',
            'firebase_token' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'phone' => 'Phone Number',
            'dial_code' => 'Dail Code'

        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }
        $dial_code =  $request->dial_code;
        $phone = $request->phone;
        $firebase_token = $request->firebase_token;
        $password  =  $request->password;
        if ($phone && $dial_code) {
            $where_array = [
                'dial_code' => $request->dial_code,
                'phone' => $request->phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, '', '');
            if (!$phone_exist) {
                $errors[] = "Phone Number not Registered";
            }
        }
        if (empty($errors)) {
            try {
                $firebase = firebase::init();
                $firebase_user = firebase::verify_token($firebase, $firebase_token);
                if (!empty($firebase_user)) {
                    $phone_no_with_dial_code = $dial_code . $phone;

                    if ($firebase_user->phoneNumber == $phone_no_with_dial_code) {

                        $fieldvalues = [
                            'password' => Hash::make($password),
                        ];
                        driverModel::where([
                            ['dial_code', '=', $dial_code],
                            ['phone', '=', $phone],
                        ])->update($fieldvalues);

                        $message = "Password Changed Successfully";
                    } else {
                        $errors[] = "OTP not verified";
                    }
                } else {
                    $errors[] = "Something Went Wrong with OTP Verification";
                }
            } catch (\Exception $e) {
                $errors[] = "Something Went Wrong with Change Password";
            }
        }
        if (!empty($errors)) {
            $messages = implode(",", $errors);
            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }
        return response($response);
    }

    public function change_password(Request $request)
    {
        $errors = array();
        $rules = [
            'new_password' => 'required',
            'old_password' => 'required',
            'confirm_new_password' => 'same:new_password',

        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'old_password' => 'Old Password',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $driver_id = $request->driver_id;
        $password =  $request->new_password;
        $old_password = $request->old_password;

        $driver_detail  = driverModel::find($driver_id);
        $driver_password = $driver_detail->password;

        if (!Hash::check($old_password, $driver_password)) {
            $errors[] = 'Old Password is Incorrect';
        }

        if (empty($errors)) {
            $fieldvalues = [
                'password' => Hash::make($password),
            ];

            $updated = DB::table('driver')->where('id', $driver_id)->update($fieldvalues);

            $message = 'Password Changed';
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }

        return response($response);
    }

    public function logout(Request $request)
    {
        $driver_id =  $request->driver_id;

        $driver = driverModel::find($driver_id);

        $driver->fcm_id =  '';
        $driver->token = '';
        $driver->is_online = 0;

        $driver->save();

        return response(['status' => '1', 'message' => 'Logout Successfully']);
    }

    public function view_profile(Request $request)
    {

        $driver_id = $request->driver_id;
        $image_path = url('/') . '/uploads/driver/';
        $driver_details = DB::table('driver')->where('id', $driver_id)->select('full_name', 'email', 'dial_code', 'phone', 'country_id', 'city_id', 'region_id', 'postal_code', 'languages', 'email', 'dob', 'address', 'driver_code', 'offline_drive')->selectRaw('IF(image != "", CONCAT("' . $image_path . '","",image), "") as image')->first();
        $country_list = commonModel::getcountry($request);
        if ($driver_details->country_id != 0) {
            $region_list =  commonModel::getregions($request, $driver_details->country_id);
        } else {
            $region_list =  array();
        }

        if ($driver_details->region_id != 0) {
            $city_list =  commonModel::getcities($request, $driver_details->region_id);
        } else {
            $city_list = array();
        }

        $res['data'] = $driver_details;
        foreach ($country_list as $key => $value) {
            $res['country'][] = array("id" => $key, "name" => $value);
        }
        foreach ($region_list as $key => $value) {
            $res['region'][] = array("id" => $key, "name" => $value);
        }
        foreach ($city_list as $key => $value) {
            $res['city'][] = array("id" => $key, "name" => $value);
        }
        $known_languages = explode(",", $driver_details->languages);
        $languages = ["English", "German", "French", "Bangla"];
        foreach ($languages as $key => $lang) {
            if (in_array($lang, $known_languages)) {
                $res['lang'][$key] = array("lang" => $lang, "is_selected" => "1");
            } else {
                $res['lang'][$key] = array("lang" => $lang, "is_selected" => "0");
            }
        }
        $res['status'] = 1;
        return response($res);
    }


    public function edit_profile(Request $request)
    {
        $full_name = $request->full_name;
        $dob = $request->dob;
        $country_id = $request->country_id;
        $region_id = $request->region_id;
        $city_id = $request->city_id;
        $postal_code = $request->postal_code;
        $address = $request->address;
        $languages_known = $request->languages_known;
        $offline_drive = $request->offline_drive;
        $errors = array();
        $rules = [
            'full_name' => 'required',
            'dob' => 'required',
            'country_id' => 'required',
            'region_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'offline_drive' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'full_name' => 'Full Name',
            'phone' => 'Phone Number',
            'dial_code' => 'Dail Code',
            'postal_code' => 'Postal Coce',
            'country_id' => 'Country',
            'city_id' => 'City',
            'region_id' => 'Region'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }
        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $file = $request->file('image');
            if ($file) {
                $file_name = $file->getClientOriginalName();

                if ($file_name) {
                    $file_name = time() . $file_name;
                    //Move Uploaded File
                    $destinationPath = 'uploads/driver';

                    $file->move($destinationPath, $file_name);
                }
            }

            $fieldvalues = [
                'full_name'   => $full_name,
                'dob'   => $dob,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'city_id' => $city_id,
                'postal_code' => $postal_code,
                'address' => $address,
                'languages' => $languages_known,
                'offline_drive' => $offline_drive,
            ];
            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }

            $updated = DB::table('driver')->where('id', $request->driver_id)->update($fieldvalues);
            if (!empty($errors)) {
                $messages = implode(",", $errors);

                $response['status'] = '0';
                $response['message'] = $messages;
            } else {
                $response['status'] = '1';
                $response['message'] = "Profile updated successfully";
            }
        }
        return response($response);
    }

    public function vehicle_info(Request $request)
    {
        $driver_id = $request->driver_id;
        $data = (new driverModel)->vehicle_info($driver_id);
        $res['status'] = 1;
        $res['data'] = $data;
        return response($res);
    }

    public function edit_vehicle_info(Request $request)
    {
        $is_approved = '1';
        $driver_id = $request->driver_id;
        $vehicle_id = get_vehicle_id_from_driver($driver_id);
        $vehicle_type = $request->vehicle_type;
        $vehicle_subtype = $request->vehicle_subtype;
        $vehicle_make = $request->vehicle_make;
        $vehicle_model = $request->vehicle_model;
        $manufacturing_year = $request->manufacturing_year;
        $vehicle_color = $request->vehicle_color;
        $vehicle_number = $request->vehicle_number;
        $driving_licence_number = $request->driving_licence_number;
        $driving_licence_expiry_date = $request->driving_licence_expiry_date;

        $errors = array();
        $rules = [
            'vehicle_type' => 'required',
            'vehicle_subtype' => 'required',
            'vehicle_make' => 'required',
            'vehicle_model' => 'required',
            'manufacturing_year'  => 'required',
            'vehicle_color' => 'required',
            'vehicle_number' => 'required',
            'driving_licence_number' => 'required',
            'driving_licence_expiry_date' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            'vehicle_type' => 'Vehicle Type',
            'vehicle Subtype' => 'Vehicle Subtype',
            'vehicle_make' => 'Vehicle Make',
            'vehicle_model' => 'Vehicle Model',
            'manufacturing_year' => 'Manufacturing Year',
            'vehicle_color' => 'Vehicle Color',
            'vehicle_number' => 'Vehicle Number',
            'driving_licence_number' => 'Driving Licence Number',
            'driving_licence_expiry_date' => 'Driving Licence Expiry Date',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }
        if (!empty($errors)) {
            $messages = implode(",", $errors);
            $response['status'] = '0';
            $response['message'] = $messages;
            return response($response);
        } else {

            $fieldvalues = [
                'vehicle_model_id' => $vehicle_model,
                'driver_id' => $driver_id,
                'mfg_year' => $manufacturing_year,
                'vehicle_color' => $vehicle_color,
                'vehicle_number' => $vehicle_number,
            ];

            $vehicle_image = $request->file('vehicle_image');
            if ($vehicle_image) {
                $file_name = $vehicle_image->getClientOriginalName();

                if ($file_name) {
                    $file_name = time() . $file_name;
                    //Move Uploaded File
                    $destinationPath = "uploads/driver/docs/$driver_id/";
                    create_folder_if_not_exist($destinationPath);
                    $vehicle_image->move($destinationPath, $file_name);
                    $fieldvalues['vehicle_image'] = $file_name;
                    $is_approved = '0';
                }
            }
            $vehicle_registration_image = $request->file('vehicle_registration_image');
            if ($vehicle_registration_image) {
                $file_name = $vehicle_registration_image->getClientOriginalName();

                if ($file_name) {
                    $file_name = time() . $file_name;
                    //Move Uploaded File
                    $destinationPath = "uploads/driver/docs/$driver_id/";
                    create_folder_if_not_exist($destinationPath);
                    $vehicle_registration_image->move($destinationPath, $file_name);
                    $fieldvalues['reg_image'] = $file_name;
                    $is_approved = '0';
                }
            }
            $vehicle_insurance_image = $request->file('vehicle_insurance_image');
            if ($vehicle_insurance_image) {
                $file_name = $vehicle_insurance_image->getClientOriginalName();

                if ($file_name) {
                    $file_name = time() . $file_name;
                    //Move Uploaded File
                    $destinationPath = "uploads/driver/docs/$driver_id/";
                    create_folder_if_not_exist($destinationPath);
                    $vehicle_insurance_image->move($destinationPath, $file_name);
                    $fieldvalues['ins_image'] = $file_name;
                    $is_approved = '0';
                }
            }

            $updated = DB::table('vehicle')->where('id', $vehicle_id)->update($fieldvalues); // vehicle updated
            if (!$updated) {
                DB::table('vehicle')->insertGetId($fieldvalues); // vehicle updated
            }

            $fieldvalues = [];

            $fieldvalues = [
                "dl_number" => $driving_licence_number,
                "dl_expiry_date" => $driving_licence_expiry_date,
            ];
            $driving_licence_image = $request->file('driving_licence_image');
            if ($driving_licence_image) {
                $file_name = $driving_licence_image->getClientOriginalName();

                if ($file_name) {
                    $file_name = time() . $file_name;
                    //Move Uploaded File
                    $destinationPath = "uploads/driver/docs/$driver_id/";
                    create_folder_if_not_exist($destinationPath);
                    $driving_licence_image->move($destinationPath, $file_name);
                    $fieldvalues['dl_image'] = $file_name;
                    $is_approved = '0';
                }
            }

            $updated = DB::table('driver')->where('id', $driver_id)->update($fieldvalues); // driver updated
            if ($is_approved == '0') {
                $updated = DB::table('driver')->where('id', $driver_id)->update(['is_activated' => '0']);
            }
            $response['status'] = '1';
            $response['message'] = "Vehicle Info updated successfully";
            return response($response);
        }
    }

    public function change_online_status(Request $request)
    {
        $online_status = $request->status;
        $driver_id = $request->driver_id;
        DB::table('driver')->where('id', $driver_id)->update(["is_online" => $online_status, "last_online_status" => $online_status]);
        $response['status'] = '1';
        $response['message'] = "Status changed successfully";
        return response($response);
    }

    public function earning_list(Request $request)
    {
        $driver_id = $request->driver_id;
        $data = (new driverModel)->earning_list($driver_id);
        if (!$data->isEmpty()) {
            $res['status'] = 1;
            $res['data'] = $data;
        } else {
            $res['status'] = 0;
            $res['message'] = 'No earning yet';
        }
        return response($res);
    }

    public function change_fcm_token(Request $request)
    {
        $driver_id = $request->driver_id;
        $fcm_token = $request->fcm_token;
        $change_fcm_token = DB::table('driver')->where('id', $driver_id)->update(array('fcm_id' => $fcm_token));
        $res['status'] = 1;
        $res['message'] = 'Token updated successfully';
        return response($res);
    }

    public function welcome_mail($to_name, $to_email)
    {
        $data = [];
        $to_email =  $to_email;
        $to_name =  $to_name;
        $subject = 'Welcome to Palkee';
        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');
        try {
            Mail::send('email_templates.welcome_mail', $data, function ($message) use ($to_email, $to_name, $subject, $from_email, $from_name) {
                $message->to($to_email, $to_name)->subject($subject);
                $message->from($from_email, $from_name);
            });
        } catch (\Exception $e) {
            //get error here
        }
        return true;
    }
}
