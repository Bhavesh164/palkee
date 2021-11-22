<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Api\ride;
use App\Services\PromocodeService;
use App\Model\Api\rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Api\driverModel;
use App\Model\Api\commonModel;
use App\Support\firebase;
use Google\Cloud\Core\Timestamp;
use Mail;
//use App\Model\Admin\vehicletype;
//use App\Model\Admin\vehicle_make;
//use App\Model\Admin\vehicle_subtype;

class rideController extends Controller
{
    public function apply_promo(Request $request)
    {
        $promo_code = $request->promo_code;
        $promo_object = new PromocodeService();
        $check_promo = $promo_object->promocode($request);
        if (is_object($check_promo)) {
            if ($check_promo->promo_type == 'Percentage') {
                $message = "You will get $check_promo->promo_rate % discount on ride";
            } else {
                $message = "You will get flat $check_promo->promo_rate  discount on ride";
            }
            $res['status'] = 1;
            $res['message'] = $message;
        } else {
            $res['status'] = 0;
            $res['message'] = "Invalid Promocode";
        }
        return response($res);
    }

    public function book_ride(Request $request)
    {
        $data['rider_id'] = $request->rider_id;
        $data['start_lat'] =  $request->pickup_lat;
        $data['start_lon'] = $request->pickup_lon;
        $data['end_lat'] = $request->drop_lat;
        $data['end_lon'] = $request->drop_lon;
        $data['start_location'] = $request->start_location;
        $data['end_location'] = $request->end_location;
        $data['ride_type_id'] = $request->ride_type_id;
        $data['payment_type'] = $request->payment_type;
        //$data['vehicle_id'] = $request->vehicle_id;
        $data['driver_id'] = $request->driver_id;
        $driver_details = (new driverModel)->find($request->driver_id);
        //promo code 
        if ($request->has('promo_code') && $request->promo_code != "") {
            $promo_code = $request->promo_code;
            $promo_object = new PromocodeService();
            $check_promo = $promo_object->promocode($request);
            if (is_object($check_promo)) {
                $data['promo_code'] = $promo_code;
                $data['is_promo_applied'] = '1';
                $data['promo_type'] = ($check_promo->promo_type == 'Percentage') ? '2' : '1';
                $data['promo_amount'] = $check_promo->promo_rate;
            } else {
                $res['status'] = 0;
                $res['message'] = "Invalid Promocode";
                return response($res);
            }
        }
        //promo code end

        //config
        $config_data = get_config();
        $distance_unit = $config_data['distance_unit'];

        //Check if driver is already on another ride
        $check_driver_status = check_driver_is_on_another_ride($data['driver_id']);
        if ($check_driver_status) {
            $res['status'] = 0;
            $res['message'] = "Driver is on another ride";
            return response($res);
        }

        $driver['driver_lat'] = $driver_details->lat;
        $driver['driver_lon'] = $driver_details->lon;
        $data['ride_number'] = rand(10, 99) . $request->rider_id . time();
        // calculate distance between pickup and drop location after pickup
        $route_details = calculate_time_distance($data['start_lat'], $data['start_lon'], $data['end_lat'], $data['end_lon']);
        //overviewpolyline
        $directions_data = get_google_map_directions($data['start_lat'], $data['start_lon'], $data['end_lat'], $data['end_lon'], $distance_unit);
        $polyline_overview = $directions_data['routes'][0]["overview_polyline"]["points"];
        // calculate distance between rider and driver location before pickup
        $driver_pickup_dis = calculate_time_distance($data['start_lat'], $data['start_lon'], $driver['driver_lat'], $driver['driver_lon']);
        $data['est_duration_text'] = $route_details['duration_text'];
        $data['est_duration_secs'] = $route_details['duration_value'];
        $data['est_distance_text'] = $route_details['distance_text'];
        $data['est_distance_meters'] = $route_details['distance_value'];
        $data['distance'] = $route_details['distance_value'];
        $data['duration'] = $route_details['duration_value'];
        $data['rider_driver_distance'] = $driver_pickup_dis['distance_value'];
        $data['rider_driver_duration'] = $driver_pickup_dis['duration_value'];
        $data['polyline_overview'] = $polyline_overview;
        $ride_id = DB::table('ride')->insertGetId($data);
        // notification data
        $notification_data = [
            "driver_id" => $driver_details->id,
            "ride_id" => $ride_id,
            "title" => "Ride Request",
            "message" => "You have one new request",
            "created_at" => date("Y-m-d H:i:s")

        ];
        send_notification([$driver_details->fcm_id], $notification_data, $driver_details->device_type, 1);
        //notification data
        $res['status'] = 1;
        $res['message'] = 'Ride booked successfully';
        return response($res);
    }

    public function get_ride(Request $request)
    {
        if ($request->has('driver_id')) {
            $res['is_driver_online'] = DB::table('driver')->where('id', $request->driver_id)->value('is_online');
        }
        if (!$request->has('ride_id')) { // ride_id is optional 
            if ($request->has('driver_id')) {
                $ride_id = check_driver_last_ride($request->driver_id);
            }
            if ($request->has('rider_id')) {
                $ride_id = check_rider_last_ride($request->rider_id);
            }
        } else {
            $ride_id = $request->ride_id;
        }

        if (!$ride_id) {
            $ride_id = 0;
        }

        $details = (new ride)->get_ride($ride_id);
        if ($details) {
            $res['status'] = 1;
            $res['data'] = $details;
            $res['message'] = 'Success';
        } else {
            $res['status'] = '0';
            $res['message'] = 'No ride yet';
        }
        return response($res);
    }

    public function ride_rating(Request $request)
    {
        $ride_id = $request->ride_id;
        $skip = $request->skip;
        if ($skip == 0) {
            $comment = $request->comment;
            $rating = $request->rating;
        }
        if ($request->has('driver_id')) {
            if (check_rating_submitted_by_driver($ride_id)) {
                $res['status'] = 0;
                $res['message'] = 'Rating already submitted';
                return response($res);
            }
            $ride_status_id = check_ride_status($ride_id);
            if (!in_array($ride_status_id, ["6", "7"])) {
                $res['status'] = 0;
                $res['message'] = 'Rating submit only when ride is finished';
                return response($res);
                exit;
            }

            if ($skip == 0) {
                $rider_id = get_rider_id_from_ride($ride_id);
                $data = [
                    'ride_id' => $ride_id,
                    'rider_id' => $rider_id,
                    'driver_id' => $request->driver_id,
                    'rating_to' => 'rider',
                    'rating_comment' => $comment ?? "",
                    'rating' => $rating,
                ];
                DB::table('ride_rating')->insertGetId($data);
            }
            DB::table('ride')->where('id', $ride_id)->update(["rated_by_driver" => 1]);
            $res['status'] = 1;
            if ($skip == 0) {
                $res['message'] = 'Rating submitted successfully';
            } else {
                $res['message'] = 'Rating skipped successfully';
            }
        }
        if ($request->has('rider_id')) {
            if (check_rating_submitted_by_rider($ride_id)) {
                $res['status'] = 0;
                $res['message'] = 'Rating already submitted';
                return response($res);
            }
            if ($skip == 0) {
                $driver_id = get_driver_id_from_ride($ride_id);
                $data = [
                    'ride_id' => $ride_id,
                    'rider_id' => $request->rider_id,
                    'driver_id' => $driver_id,
                    'rating_to' => 'driver',
                    'rating_comment' => $comment ?? "",
                    'rating' => $rating,
                ];
                DB::table('ride_rating')->insertGetId($data);
            }
            DB::table('ride')->where('id', $ride_id)->update(["rated_by_rider" => 1]);
            $res['status'] = 1;
            if ($skip == 0) {
                $res['message'] = 'Rating submitted successfully';
            } else {
                $res['message'] = 'Rating skipped successfully';
            }
        }
        return response($res);
    }

    public function end_ride(Request $request)
    {
        if ($request->has('driver_id')) {
            $ride_id = $request->ride_id;
            $ride_details = (new ride)->find($ride_id);
            $rider_details = (new rider)->find($ride_details->rider_id);
            $start_lat = $ride_details->start_lat;
            $start_lon = $ride_details->start_lon;
            $driver_lat = $request->lat;
            $driver_lon = $request->lon;
            $current_time = time();
            $duration_secs = $current_time - strtotime($ride_details->pickup_time);
            $route_details = calculate_time_distance($start_lat, $start_lon, $driver_lat, $driver_lon);
            $costs = (new ride)->calculate_cost($route_details['distance_value'], $duration_secs, $request->driver_id, $ride_details);
            $data = [
                'driver_earning' => $costs['driver_earning'],
                //'min_price' => $costs['min_price'],
                'base_fare' => $costs['base_price'],
                'distance_fare' => $costs['distance_cost'],
                'time_fare' => $costs['duration_cost'],
                'tax' => $costs['tax'],
                'total_bill' => $costs['total_cost'],
                'distance' => $route_details['distance_value'],
                'duration' =>  $duration_secs,
                'distance_fare' => $costs['distance_cost'],
                'time_fare' => $costs['duration_cost'],
                'ride_cost' => $costs['ride_cost'],
                'discount_amount' => $costs['discount_amount'],
                'drop_time' => date('Y-m-d H:i:s'),
                'rider_driver_distance' => '0',
                'rider_driver_duration' => '0',
                'ride_status_id' => '7',
                'end_ride_on' => date("Y-m-d H:i:s"),
                'admin_earning' => $costs['admin_earning'],
            ];
            $update_ride = (new ride)->update_ride($ride_id, $data);
            DB::table('driver')->where('id', $request->driver_id)->update(["on_ride" => '0']);
            // notification data
            $notification_data = [
                "title" => "Ride Completed",
                "text" => "You have reached your destination",
                "type" => "ride_dropped",
                "ride_id" => $ride_id,
                "created_at" => date("Y-m-d H:i:s"),
            ];
            send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type);
            //notification data

            //send mail
            $email_data = $data;
            $email_data['start_location'] = $ride_details->start_location;
            $email_data['end_location'] = $ride_details->end_location;
            $email_data['full_name'] = $rider_details->full_name;
            $email_data['ride_id'] = $rider_details->ride_id;
            $invoice_details = (new ride)->invoice($ride_details);
            $email_data['vehicle_number'] = $invoice_details->vehicle_number;
            $email_data['model_name'] = $invoice_details->model_name;
            $this->invoice_mail($rider_details->full_name, $rider_details->email_id, $email_data);
            $res['status'] = 1;
            $res['message'] = 'Ride Completed';
            return response($res);
        }
    }

    public function invoice(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        if (check_ride_status($ride_id) == '7') {
            $data = (new ride)->invoice($ride_details);
            $res['status'] = 1;
            $res['data'] = $data;
        } else {
            $res['status'] = 0;
            $res['message'] = 'Ride is not completed yet';
        }
        return response($res);
    }

    public function ride_details(Request $request)
    {
        $ride_id = $request->ride_id;
        $ride_details = (new ride)->find($ride_id);
        $ride_status = check_ride_status($ride_id);
        if (!in_array($ride_status, ['1', '2', '4', '5', '6'])) {
            $data = (new ride)->ride_detail($ride_details);
            $res['status'] = 1;
            $res['data'] = $data;
        } else {
            $res['status'] = 0;
            $res['message'] = 'Ride is not completed yet';
        }
        return response($res);
    }

    public function ride_cancelled_by_driver(Request $request)
    {
        if ($request->has('driver_id')) {
            $ride_id = $request->ride_id;
            $ride_details = (new ride)->find($ride_id);
            $ride_status = check_ride_status($ride_id);
            if (in_array($ride_status, ["8"])) {
                $res['status'] = 0;
                $res['message'] = 'Ride is already cancelled';
            } else if (in_array($ride_status, ["2", "4"])) {
                DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '8', 'cancel_by' => 'driver', "cancellation_time" => date("Y-m-d H:i:s")]);
                DB::table('driver')->where('id', $ride_details->driver_id)->update(["on_ride" => '0']);
                $res['status'] = 1;
                $res['message'] = 'Ride cancelled successfully';
            } else if (in_array($ride_status, ["1"])) {
                $rider_id = get_rider_id_from_ride($ride_id);
                DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '1']);
                DB::table('driver')->where('id', $ride_details->driver_id)->update(["on_ride" => '0']);
                $data = [
                    "ride_id" => $ride_id,
                    "driver_id" => $request->driver_id,
                    "rider_id" => $rider_id,
                    "cancellation_date" => date("Y-m-d H:i:s"),
                ];
                DB::table('ride_cancelled_by_driver')->insert($data);
                $vehicle_subtype_id = get_vehicle_subtype_id_from_driver($ride_details->driver_id);
                $result = $this->forward_request($vehicle_subtype_id, $ride_details->start_lat, $ride_details->start_lon, $ride_details->ride_type_id, $ride_id);
                if (!$result) {
                    DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '8']);
                    $rider_details = DB::table('rider')->find($rider_id);
                    $notification_data = [
                        "ride_id" => $ride_id,
                        "title" => "Cancel Request",
                        "message" => "Driver has cancel your ride request",
                        "created_at" => date("Y-m-d H:i:s")
                    ];
                    send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type);
                }
                $res['status'] = 1;
                $res['message'] = 'Ride cancelled successfully';
            } else {
                $res['status'] = 0;
                $res['message'] = 'Ride cannot be cancelled';
            }
            return response($res);
        }
    }

    public function ride_cancelled_by_rider(Request $request)
    {
        if ($request->has('rider_id')) {
            $ride_id = $request->ride_id;
            $ride_details = (new ride)->find($ride_id);
            $ride_status = check_ride_status($ride_id);
            if (in_array($ride_status, ["8"])) {
                $res['status'] = 0;
                $res['message'] = 'Ride is already cancelled';
            } else if (in_array($ride_status, ["1", "2"])) {
                DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '8', 'cancel_by' => 'rider', 'cancellation_time' => date("Y-m-d H:i:s")]);
                DB::table('driver')->where('id', $ride_details->driver_id)->update(["on_ride" => '0']);
                $res['status'] = 1;
                $res['message'] = 'Ride cancelled successfully';
            } else {
                $res['status'] = 0;
                $res['message'] = 'Ride cannot be cancelled';
            }
            return response($res);
        }
    }

    public function book_ride_automatically(Request $request)
    {
        $data['rider_id'] = $request->rider_id;
        $data['start_lat'] =  $request->pickup_lat;
        $data['start_lon'] = $request->pickup_lon;
        $data['end_lat'] = $request->drop_lat;
        $data['end_lon'] = $request->drop_lon;
        $data['start_location'] = $request->start_location;
        $data['end_location'] = $request->end_location;
        $data['ride_type_id'] = $request->ride_type_id;
        $data['payment_type'] = $request->payment_type;
        $vehicle_subtype_id = $request->vehicle_subtype_id;
        //$data['vehicle_id'] = $request->vehicle_id;

        $where_condition[] = 1;
        if ($request->ride_type_id == 4) {
            $where_condition[]  =   "driver.offline_drive = '1'";
        }

        if (!empty($where_condition)) {
            $where = implode(' and ', $where_condition);
        }
        $nearby_drivers = commonModel::get_nearby_drivers_for_vehicle_subtype($vehicle_subtype_id, $data['start_lat'], $data['start_lon'], $where);
        if ($nearby_drivers) {
            $driver_id = $nearby_drivers[0]->id;
        }
        if (isset($driver_id)) {
            $driver_details = (new driverModel)->find($driver_id);

            //Check if driver is already on another ride
            $check_driver_status = check_driver_is_on_another_ride($driver_id);
            if ($check_driver_status) {
                $res['status'] = 0;
                $res['message'] = "Driver is on another ride";
                return response($res);
            }

            $driver['driver_lat'] = $driver_details->lat;
            $driver['driver_lon'] = $driver_details->lon;
            $data['ride_number'] = rand(10, 99) . $request->rider_id . time();
            // calculate distance between pickup and drop location after pickup
            $route_details = calculate_time_distance($data['start_lat'], $data['start_lon'], $data['end_lat'], $data['end_lon']);
            // calculate distance between rider and driver location before pickup
            $driver_pickup_dis = calculate_time_distance($data['start_lat'], $data['start_lon'], $driver['driver_lat'], $driver['driver_lon']);
            $data['driver_id'] = $driver_id;
            $data['est_duration_text'] = $route_details['duration_text'];
            $data['est_duration_secs'] = $route_details['duration_value'];
            $data['est_distance_text'] = $route_details['distance_text'];
            $data['est_distance_meters'] = $route_details['distance_value'];
            $data['distance'] = $route_details['distance_value'];
            $data['duration'] = $route_details['duration_value'];
            $data['rider_driver_distance'] = $driver_pickup_dis['distance_value'];
            $data['rider_driver_duration'] = $driver_pickup_dis['duration_value'];
            $ride_id = DB::table('ride')->insertGetId($data);
            // notification data
            $notification_data = [
                "driver_id" => $driver_details->id,
                "ride_id" => $ride_id,
                "title" => "Ride Request",
                "message" => "You have one new request",
                "created_at" => date("Y-m-d H:i:s")

            ];
            send_notification([$driver_details->fcm_id], $notification_data, $driver_details->device_type, 1);
            //notification data
            $res['status'] = 1;
            $res['message'] = 'Ride booked successfully';
            return response($res);
        } else {
            $res['status'] = 0;
            $res['message'] = 'No nearby driver found';
            return response($res);
        }
    }

    private function forward_request($vehicle_subtype_id, $lat, $lon, $ride_type_id, $ride_id)
    {
        $driver_excluded = DB::table('ride_cancelled_by_driver')->where('ride_id', $ride_id)->select(DB::raw('group_concat(driver_id) as driver_id'))->get();
        if (!$driver_excluded->isEmpty()) {
            $driver_excluded = $driver_excluded[0]->driver_id;
        } else {
            $driver_excluded = 0;
        }
        $where_condition[] = "driver.id NOT IN ($driver_excluded)";
        if ($ride_type_id == 4) {
            $where_condition[]  =   "driver.offline_drive = '1'";
        }

        if (!empty($where_condition)) {
            $where = implode(' and ', $where_condition);
        }

        $nearby_drivers = commonModel::get_nearby_drivers_for_vehicle_subtype($vehicle_subtype_id, $lat, $lon, $where);

        if ($nearby_drivers) {
            $nearby_driver_id = $nearby_drivers[0]->id;
        }
        if (isset($nearby_driver_id)) {
            DB::table('ride')->where('id', $ride_id)->update(["driver_id" => $nearby_driver_id]);
        }
        if (isset($nearby_driver_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function book_ride_hourly(Request $request)
    {
        $data['rider_id'] = $request->rider_id;
        $data['start_lat'] =  $request->pickup_lat;
        $data['start_lon'] = $request->pickup_lon;
        $data['end_lat'] = $request->drop_lat;
        $data['end_lon'] = $request->drop_lon;
        $data['start_location'] = $request->start_location;
        $data['end_location'] = $request->end_location;
        $data['ride_type_id'] = '3';
        $data['payment_type'] = $request->payment_type;
        //$data['vehicle_id'] = $request->vehicle_id;
        $data['driver_id'] = $request->driver_id;
        $data['hourly'] = $request->hourly;
        $driver_details = (new driverModel)->find($request->driver_id);
        //promo code 
        if ($request->has('promo_code') && $request->promo_code != "") {
            $promo_code = $request->promo_code;
            $promo_object = new PromocodeService();
            $check_promo = $promo_object->promocode($request);
            if (is_object($check_promo)) {
                $data['promo_code'] = $promo_code;
                $data['is_promo_applied'] = '1';
                $data['promo_type'] = ($check_promo->promo_type == 'Percentage') ? '2' : '1';
                $data['promo_amount'] = $check_promo->promo_rate;
            } else {
                $res['status'] = 0;
                $res['message'] = "Invalid Promocode";
                return response($res);
            }
        }

        //config
        $config_data = get_config();
        $distance_unit = $config_data['distance_unit'];

        //Check if driver is already on another ride
        $check_driver_status = check_driver_is_on_another_ride($data['driver_id']);
        if ($check_driver_status) {
            $res['status'] = 0;
            $res['message'] = "Driver is on another ride";
            return response($res);
        }

        $driver['driver_lat'] = $driver_details->lat;
        $driver['driver_lon'] = $driver_details->lon;
        $data['ride_number'] = rand(10, 99) . $request->rider_id . time();
        // calculate distance between pickup and drop location after pickup
        $route_details = calculate_time_distance($data['start_lat'], $data['start_lon'], $data['end_lat'], $data['end_lon']);
        //overviewpolyline
        $directions_data = get_google_map_directions($data['start_lat'], $data['start_lon'], $data['end_lat'], $data['end_lon'], $distance_unit);
        $polyline_overview = $directions_data['routes'][0]["overview_polyline"]["points"];
        // calculate distance between rider and driver location before pickup
        $driver_pickup_dis = calculate_time_distance($data['start_lat'], $data['start_lon'], $driver['driver_lat'], $driver['driver_lon']);
        $data['est_duration_text'] = $route_details['duration_text'];
        $data['est_duration_secs'] = $route_details['duration_value'];
        $data['est_distance_text'] = $route_details['distance_text'];
        $data['est_distance_meters'] = $route_details['distance_value'];
        $data['distance'] = $route_details['distance_value'];
        $data['duration'] = $route_details['duration_value'];
        $data['rider_driver_distance'] = $driver_pickup_dis['distance_value'];
        $data['rider_driver_duration'] = $driver_pickup_dis['duration_value'];
        $data['polyline_overview'] = $polyline_overview;
        $ride_id = DB::table('ride')->insertGetId($data);
        // notification data
        $notification_data = [
            "driver_id" => $driver_details->id,
            "ride_id" => $ride_id,
            "title" => "Ride Request",
            "message" => "You have one new request",
            "created_at" => date("Y-m-d H:i:s")

        ];
        send_notification([$driver_details->fcm_id], $notification_data, $driver_details->device_type, 1);
        //notification data
        $res['status'] = 1;
        $res['message'] = 'Ride booked successfully';
        return response($res);
    }

    public function enter_rider_id_to_start_offline_drive(Request $request)
    {
        $rider_code = $request->rider_id;
        $ride_id = $request->ride_id;
        $check_rider_id_is_valid = DB::table('rider')->where('rider_code', $rider_code)->select('id')->first();
        if ($check_rider_id_is_valid) {
            $data['ride_status_id'] = '5';
            $update_ride = (new ride)->update_ride($ride_id, $data);
            $res['status'] = 1;
            $res['message'] = 'Ride started';
        } else {
            $res['status'] = 0;
            $res['message'] = 'You have entered wrong rider id';
        }
        return response($res);
    }

    public function end_offline_drive(Request $request)
    {
        if ($request->has('driver_id')) {
            $ride_id = $request->ride_id;
            $ride_details = (new ride)->find($ride_id);
            $rider_details = (new rider)->find($ride_details->rider_id);
            $current_time = time();
            $duration_secs = $current_time - strtotime($ride_details->pickup_time);
            $driver_details = (new driverModel)->find($request->driver_id);
            $route_details = calculate_time_distance($ride_details->start_lat, $ride_details->start_lon, $driver_details->lat, $driver_details->lon);
            //update ride
            $data['ride_status_id'] = '7';
            $data['end_ride_on'] = date("Y-m-d H:i:s");
            $data['drop_time'] = date("Y-m-d H:i:s");
            $data['distance'] = $route_details['distance_value'];
            $data['duration'] =  $duration_secs;
            $data['is_payment'] = '1';
            $update_ride = (new ride)->update_ride($ride_id, $data);
            DB::table('driver')->where('id', $request->driver_id)->update(["on_ride" => '0']);
            // notification data
            $notification_data = [
                "title" => "Ride Completed",
                "text" => "You have reached your destination",
                "type" => "ride_dropped",
                "ride_id" => $ride_id,
                "created_at" => date("Y-m-d H:i:s"),
            ];
            send_notification([$rider_details->fcm_id], $notification_data, $rider_details->device_type);
            //notification data
            $res['status'] = 1;
            $res['message'] = 'Ride Completed';
            return response($res);
        }
    }

    public function ride_history(Request $request)
    {
        $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 1;
        if ($request->has('driver_id')) {
            $ride_history = (new ride)->ride_history('driver', $request->driver_id, $limit, $offset);
        }
        if ($request->has('rider_id')) {
            $ride_history = (new ride)->ride_history('rider', $request->rider_id, $limit, $offset);
        }
        if (!$ride_history->isEmpty()) {
            $res['status'] = 1;
            $res['data'] = $ride_history;
        } else {
            $res['status'] = 0;
            $res['message'] = "No record found";
        }
        return response($res);
    }

    public function invoice_mail($to_name, $to_email, $data)
    {
        $to_email = $to_email;
        $to_name = $to_name;
        $subject = 'Welcome to Palkee';
        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');
        try {
            Mail::send('email_templates.invoice', $data, function ($message) use ($to_email, $to_name, $subject, $from_email, $from_name) {
                $message->to($to_email, $to_name)->subject($subject);
                $message->from($from_email, $from_name);
            });
        } catch (\Exception $e) {
            //get error here
        }
        return true;
    }
}
