<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Api\rider;
use App\Model\Api\vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Api\commonModel;
use App\Model\Api\driverModel;
use App\Support\firebase;
use Mail;


//use App\Model\Admin\vehicletype;
//use App\Model\Admin\vehicle_make;
//use App\Model\Admin\vehicle_subtype;

class riderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function signup_validate_number(Request $request)
    {
        $errors = array();
        $rules = [
            'phone' => 'required',
            'dial_code' => 'required',
            'full_name' => 'required',
            'password'  => 'required',
            'email'  => 'required',
            //'phone' => 'required|unique:rider',
            //'email' => 'required|unique:users,email,' . $userID,
            // 'phone' => 'required|unique:rider,phone',
        ];
        $messages = [
            'required' => ':attribute is required.',
            // 'type_id.required' => 'Type Name is required.',
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
            $phone_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
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
            $email_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
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

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    public function signup(Request $request)
    {
        require_once('app/Support/firebase.php');
        $errors = array();
        $rules = [
            'phone' => 'required',
            'email' => 'required',
            'dial_code' => 'required',
            'full_name' => 'required',
            'password'  => 'required',
            'firebase_token' => 'required',
            //'phone' => 'required|unique:rider',
            //'email' => 'required|unique:users,email,' . $userID,
            // 'phone' => 'required|unique:rider,phone',
        ];
        $messages = [
            'required' => ':attribute is required.',
            // 'type_id.required' => 'Type Name is required.',
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
            $phone_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
            if ($phone_exist) {
                $errors[] = "Phone Number Already Exist";
            }
        }

        if ($email) {
            $where_array = [
                'email' => $request->email,
            ];
            $email_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
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
                            'token' => $token,
                            'is_verified' => '1',
                            'last_login_on' => date('Y-m-d H:i:s'),
                            'device_type' => $request->device_type,
                            'version_code' => $request->version_code,
                            'version_name' => $request->version_name,
                            'os_version' =>  $request->os_version,
                            'device_name' => $request->device_name
                        ];

                        $rider = rider::create($fieldvalues);

                        $id = $rider->id;

                        //$rider = rider::find($id);

                        $rider->rider_code = 'R00' . $id;

                        //$rider
                        $rider->save();

                        $message = "Registration Successful";

                        $data = [
                            'rider_id' => $id,
                            'token' => $token,
                            'sos_number' => get_config()['sos_number'],
                        ];
                    } else {
                        $errors[] = "OTP not verified";
                    }
                } else {
                    $errors[] = "Something Went Wrong with OTP Verification";
                }

                //	var_dump($firebase_user);
            } catch (\Exception $e) {
                //var_dump($e);
                $errors[] = "Something Went Wrong with Registration";
                // $errors[] = $e->getMessage();
                //                            $message = $e->getMessage();
                //                            return response()->json([
                //                                'success' => '0',
                //                                'message' => $message
                //                            ], 201);
                //                            die;
            }
        }


        //        $rider = new rider;
        //        $rider->full_name = $request->full_name;
        //        $rider->email = $request->email;
        //        $success = $rider->save();

        if (!empty($errors)) {
            $messages = implode(",", $errors);
            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            //send welcome mail
            $this->welcome_mail($request->full_name, $request->email);
            $response['status'] = '1';
            $response['message'] = $message;
            $response['data']  = $data;
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);

        //        return response()->json([
        //            "message" => "Signup done Successfully"
        //        ], 201);
    }

    public function login(Request $request)
    {
        $errors = array();
        $rules = [
            'phone' => 'required',
            'dial_code' => 'required',
            'password'  => 'required',
            //'phone' => 'required|unique:rider',
            //'email' => 'required|unique:users,email,' . $userID,
            // 'phone' => 'required|unique:rider,phone',
        ];
        $messages = [
            'required' => ':attribute is required.',
            // 'type_id.required' => 'Type Name is required.',
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
            $rider = rider::where([
                ['dial_code', '=', $dial_code],
                ['phone', '=', $phone],
            ])->first();
            if ($rider) {
                $rider_password = $rider->password;

                if (Hash::check($password, $rider_password)) {
                    $message = "Login Successfull";
                    $token = generateRandomString(15);
                    $rider->token = $token;
                    //                     'last_login_time' => date('Y-m-d H:i:s'),
                    //                            'device_type' => $request->device_type,
                    //                            'version_code' => $request->version_code,
                    //                            'version_name' => $request->version_name,
                    //                            'os_version' =>  $request->os_version,
                    //                            'device_name' => $request->device_name
                    $rider->save();
                    $data = [
                        'rider_id' => $rider->id,
                        'token' => $token,
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
            //'phone' => 'required|unique:rider',
            //'email' => 'required|unique:users,email,' . $userID,
        ];
        $messages = [
            'required' => ':attribute is required.',
            // 'type_id.required' => 'Type Name is required.',
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
            $phone_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
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

        return json_encode($response, JSON_UNESCAPED_SLASHES);
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
            //'phone' => 'required|unique:rider',
            //'email' => 'required|unique:users,email,' . $userID,
            // 'phone' => 'required|unique:rider,phone',
        ];
        $messages = [
            'required' => ':attribute is required.',
            // 'type_id.required' => 'Type Name is required.',
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
            $phone_exist  = commonModel::check_value_exist('rider', $where_array, '', '');
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

                        rider::where([
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

                //	var_dump($firebase_user);
            } catch (\Exception $e) {
                //var_dump($e);
                $errors[] = "Something Went Wrong with Change Password";
                // $errors[] = $e->getMessage();
                //                            $message = $e->getMessage();
                //                            return response()->json([
                //                                'success' => '0',
                //                                'message' => $message
                //                            ], 201);
                //                            die;
            }
        }


        //        $rider = new rider;
        //        $rider->full_name = $request->full_name;
        //        $rider->email = $request->email;
        //        $success = $rider->save();

        if (!empty($errors)) {
            $messages = implode(",", $errors);
            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);

        //        return response()->json([
        //            "message" => "Signup done Successfully"
        //        ], 201);
    }

    public function rider_detail(Request $request)
    {

        $rider_id = $request->rider_id;

        $rider_detail = rider::get_rider_detail($rider_id);

        $response['status'] = '1';

        $response['data'] = $rider_detail;

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    public function edit_profile(Request $request)
    {
        $errors = array();
        $rules = [
            // 'phone' => 'required',
            //'dial_code' => 'required',
            'full_name' => 'required',
            //'phone' => 'required|unique:rider',
            'email' => 'required|unique:rider,email,' . $request->rider_id,
            // 'phone' => 'required|unique:rider,phone',
        ];
        $messages = [
            'required' => ':attribute is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //'phone' => 'Phone Number',
            'full_name' => 'Name',

            //'dial_code' => 'Dail Code'

        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $rider_id = $request->rider_id;
        $full_name =  $request->full_name;
        $email = $request->email;
        $country_id =  $request->country_id;
        $address =  $request->address;

        $file = $request->file('image');

        if ($file) {
            $file_name = $file->getClientOriginalName();

            if ($file_name) {
                $file_name = time() . $file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/rider';

                $file->move($destinationPath, $file_name);
            }
        }

        $fieldvalues = [
            'full_name'   => $full_name,
            'email' => $email,
            'address' => $address,
        ];

        if (isset($file_name)) {
            $fieldvalues['image'] = $file_name;
        }

        $updated = DB::table('rider')->where('id', $rider_id)->update($fieldvalues);

        $message = 'Profile Updated';

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['message'] = $message;
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
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

        $rider_id = $request->rider_id;
        $password =  $request->new_password;
        $old_password = $request->old_password;

        $rider_detail  = rider::find($rider_id);
        $rider_password = $rider_detail->password;

        if (!Hash::check($old_password, $rider_password)) {
            $errors[] = 'Old Password is Incorrect';
        }

        if (empty($errors)) {
            $fieldvalues = [
                'password' => Hash::make($password),
            ];

            $updated = DB::table('rider')->where('id', $rider_id)->update($fieldvalues);

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

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function logout(Request $request)
    {
        $rider_id =  $request->rider_id;

        $rider = rider::find($rider_id);

        $rider->fcm_id =  '';
        $rider->token = '';

        $rider->save();

        return response()->json(['status' => '1', 'message' => 'Logout Successfully'], 201);
    }

    public function selected_driver(Request $request)
    {
        $config_data = get_config();
        $distance_unit = $config_data['distance_unit'];
        $rider_id = $request->rider_id;
        $driver_id = $request->driver_id;
        $vehicle_id = get_vehicle_id_from_driver($driver_id);
        $vehicle_detail = (new vehicle)->get_vehicle_details_from_driver($driver_id);
        $vehicle_detail->id = $vehicle_id;
        $source_lat = $request->source_lat;
        $source_lon = $request->source_lon;
        $destination_lat = $request->destination_lat;
        $destination_lon = $request->destination_lon;
        $driver = (new driverModel)->find($driver_id);
        $driver_lat = $driver->lat;
        $driver_lon = $driver->lon;
        $route_details = get_google_map_directions($driver_lat, $driver_lon, $source_lat, $source_lon, $distance_unit);
        $distance_value = $route_details['routes'][0]["legs"][0]["distance"]["value"];
        $duration_value = $route_details['routes'][0]["legs"][0]["duration"]["value"];
        $driver_distance = $distance = round($distance_value / 1000, 2);
        $driver_time = $time_in_min = $duration_value / 60;
        $res['driver_to_pickup_route'] = $route_details;
        $route_details = get_google_map_directions($source_lat, $source_lon, $destination_lat, $destination_lon, $distance_unit);
        $res['pickup_to_drop_route'] = $route_details;
        $distance_value = $route_details['routes'][0]["legs"][0]["distance"]["value"];
        $duration_value = $route_details['routes'][0]["legs"][0]["duration"]["value"];
        if ($distance_value) {
            $distance = round($distance_value / 1000, 2);
            $time_in_min = $duration_value / 60;
        }
        if (!trim($driver->image)) {
            $driver_img = '';
        } else {
            $driver_img = config('global.DRIVER_IMAGE_PATH') . $driver->image;
        }
        $res['driver'] = ["name" => $driver->full_name, "image" => $driver_img, "driver_distance" => $driver_distance . " km", "driver_time" => round($driver_time) . " min"];
        $res['vehicle_detail'] = $vehicle_detail;
        $res['route_distance'] = round($distance) . " km";
        $res['route_time'] = round($time_in_min) . " min";
        $estimated_cost = $vehicle_detail->base_price + ($vehicle_detail->per_minute_price * $time_in_min) + ($vehicle_detail->per_km_price * $distance);
        $res['route_estimated_cost'] = config('constant.currency_symbol') . $estimated_cost;
        $res['status'] = 1;
        $res['message'] = "Success";
        return response($res);
    }

    public function select_offline_driver(Request $request)
    {
        $driver_id = $request->driver_id;
        $lat = $request->lat;
        $lon = $request->lon;
        $end_lat = $request->end_lat;
        $end_lon = $request->end_lon;
        $start_location = $request->start_location;
        $end_location = $request->end_location;
        $driver_details = DB::table('driver')->find($driver_id);

        //ride
        $data['est_duration_text'] = '0';
        $data['est_duration_secs'] = '0';
        $data['est_distance_text'] = '0';
        $data['est_distance_meters'] = '0';
        $data['distance'] = '0';
        $data['duration'] = '0';
        $data['rider_driver_distance'] = '0';
        $data['rider_driver_duration'] = '0';
        $data['polyline_overview'] = '';
        $data['rider_id'] = $request->rider_id;
        $data['start_lat'] =  $lat;
        $data['start_lon'] = $lon;
        $data['end_lat'] = $end_lat;
        $data['end_lon'] = $end_lon;
        $data['start_location'] = $start_location;
        $data['end_location'] = $end_location;
        $data['ride_type_id'] = '4';
        $data['payment_type'] = 'cash';
        $data['is_payment'] = '0';
        $data['driver_id'] = $request->driver_id;
        $data['pickup_time'] = date("Y-m-d H:i:s");
        $data['base_fare'] = '0.00';
        $data['distance_fare'] = '0.00';
        $data['time_fare'] = '0.00';
        $data['tax'] = '0.00';
        $data['total_bill'] = '0.00';
        $data['ride_cost'] = '0.00';
        $data['driver_earning'] = '0.00';
        $data['rated_by_driver'] = '1';
        $data['rated_by_rider'] = '1';
        $data['ride_status_id'] = '5';
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['ride_number'] = rand(10, 99) . $request->rider_id . time();
        $ride_id = DB::table('ride')->insertGetId($data);


        // notification data
        $notification_data = [
            "driver_id" => $driver_id,
            "lat" => $lat,
            "lon" => $lon,
            "start_location" => $start_location,
            "end_location" => $end_location,
            "title" => "Ride Request",
            "message" => "You have received an offline ride request",
            "created_at" => date("Y-m-d H:i:s")

        ];
        send_notification([$driver_details->fcm_id], $notification_data, $driver_details->device_type, 1);
        //notification data
        $res['status'] = '1';
        $res['message'] = 'Request send successfully';
        return response($res);
    }


    public function change_fcm_token(Request $request)
    {
        $rider_id = $request->rider_id;
        $fcm_token = $request->fcm_token;
        $change_fcm_token = DB::table('rider')->where('id', $rider_id)->update(array('fcm_id' => $fcm_token));
        $res['status'] = 1;
        $res['message'] = 'Token updated successfully';
        return response($res);
    }

    public function welcome_mail($to_name, $to_email)
    {
        $data = [];
        $to_email = $to_email;
        $to_name = $to_name;
        $subject = 'Welcome to Palkee';
        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');
        try {
            Mail::send('email_templates.welcome_mail', $data, function ($message) use ($to_email, $to_name, $subject, $from_email, $from_name) {
                $message->to($to_email, $to_name)->subject($subject);
                $message->from($from_email, $from_name);
            });
        } catch (\Exception $e) {
            // get error here 
        }
        return true;
    }
}
