<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\Facades\DB;

function myFunction($message)
{
    return 'this is my ' . $message;
}
function create_folder_if_not_exist($target_dir)
{

    if (is_dir($target_dir)) {
    } else {
        mkdir($target_dir, 0777);
    }
}
function beautify_distance($distance, $distance_unit)
{
    $dis = "";
    if ($distance_unit == 'km') {
        $distance_km = round($distance / 1000, 1);
        $dis = $distance_km . ' km';
    } else  if ($distance_unit == 'miles') {
        $distance_km = round($distance / 1609, 1);
        $dis = $distance_km . ' miles';
    }

    return $dis;
}

function beautify_time($time_in_sec)
{
    $time = '';
    if ($time_in_sec > 0) {
        $init = $time_in_sec;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        if ($hours > 0) {
            $time .= $hours . 'hr:';
        }
        if ($minutes > 0) {
            $time .= $minutes . 'min:';
        }
        if ($seconds > 0) {
            $time .= $seconds . 'sec';
        }
    } else {
        $time = "0 min";
    }
    return $time;
}

function beautify_timer($time_in_sec)
{
    $time = '00:00:00';
    if ($time_in_sec > 0) {
        $time = '';
        $init = $time_in_sec;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        //hours
        if ($hours > 10) {
            $time .= $hours . ":";
        } else if ($hours > 0) {
            $time .= "0" . $hours . ":";
        } else {
            $time .= "00:";
        }
        //minutes
        if ($minutes > 10) {
            $time .= $minutes . ":";
        } else if ($minutes > 0) {
            $time .= "0" . $minutes . ":";
        } else {
            $time .= "00:";
        }
        //seconds
        if ($seconds > 10) {
            $time .= $seconds . ":";
        } else if ($seconds > 0) {
            $time .= "0" . $seconds;
        } else {
            $time .= "00";
        }
    }

    return $time;
}

function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function get_google_map_directions($src_lat, $src_lon, $dest_lat, $dest_lon, $unit)
{
    if ($unit == 'miles') {
        $gunit = 'imperial';
    } else {
        $gunit = 'metric';
        $unit = 'km';
    }
    $resp = do_curl("https://maps.googleapis.com/maps/api/directions/json?units={$gunit}&origin={$src_lat},{$src_lon}&destination={$dest_lat},{$dest_lon}&key=" . config('constant.GOOGLE_MAP_KEY'));
    if (empty($resp) || $resp['status'] != 'OK') {
        $res['status'] = 0;
        $res['message'] = "No route found";
        echo json_encode($res);
        die;
    }

    $data =  [

        "routes" => $resp["routes"],

    ];

    return $data;
}
function calculate_distance_google()
{
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $store_info->latitude . ',' . $store_info->longitude . '&destinations=' . $lat . ',' . $long . '&key=' . GOOGLE_KEY,
        CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    ]);
    $resp = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($resp);
    if (isset($response) && $response->status == 'OK') {
        $resp_data = @$response->rows[0]->elements[0];
        if (!empty($resp_data->distance)) {
            $distance = $resp_data->distance->value / 1000;
            $d_time = $resp_data->duration->value / 60;
        }
    } else {
        $res['status'] = 0;
        $res['message'] = "No route found";
        echo json_encode($res);
        die;
    }
    $km = round($distance, 2);
    $min = round($d_time);
}

function gMapDistanceNdTime($pickLat, $pickLng, $dropLat, $dropLng)
{
    $unit = 'metric'; // default
    $key = GOOGLE_KEY;
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?'
        . 'origins=' . $pickLat . ',' . $pickLng
        . '&destinations=' . $dropLat . ',' . $dropLng
        . '&units=' . $unit
        . '&key=' . $key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);

    $status = $result['status'];
    $distanceData = [];
    if (
        $status == 'OK' &&
        $result['rows'][0]['elements'][0]['status'] != 'ZERO_RESULTS' &&
        $result['rows'][0]['elements'][0]['status'] != 'NOT_FOUND'
    ) {
        $distance = $result['rows'][0]['elements'][0]['distance']['value'];
        $time = $result['rows'][0]['elements'][0]['duration']['value'];
        $distanceData = [
            'distance' => $distance / 1000,
            'time' => $time / 60,
        ];
    } else {
        //        $fileName = ROOT . "/log_" . date('Y-m-d_H-i-s') . ".txt";
        //        $file = fopen($fileName, "a");
        //        file_put_contents($fileName, $response);
        //        fclose($file);
    }
    return $distanceData;
}

function calculate_time_distance($src_lat, $src_lon, $dest_lat, $dest_lon, $unit = "")
{
    if ($unit == 'miles') {
        $gunit = 'imperial';
    } else {
        $gunit = 'metric';
        $unit = 'km';
    }
    $resp = do_curl("https://maps.googleapis.com/maps/api/distancematrix/json?units={$gunit}&origins={$src_lat},{$src_lon}&destinations={$dest_lat},{$dest_lon}&key=" . config('constant.GOOGLE_MAP_KEY'));

    if (empty($resp) || $resp['status'] != 'OK') {

        $res['status'] = 0;
        $res['message'] = "No route found";
        echo json_encode($res);
        die;
    }
    return [
        "origin_addresses" => $resp["origin_addresses"],
        "destination_addresses" => $resp["destination_addresses"],
        "distance_text" => $resp["rows"][0]["elements"][0]["distance"]["text"] ?: '0 ' . $unit,
        "distance_value" => $resp["rows"][0]["elements"][0]["distance"]["value"] ?: 0,
        "duration_text" => $resp["rows"][0]["elements"][0]["duration"]["text"] ?: '',
        "duration_value" => $resp["rows"][0]["elements"][0]["duration"]["value"] ?: 0,
    ];
}
function do_curl($url)
{
    $ch = curl_init();
    $headers = array(
        "Cache-Control: no-cache",
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $data;
}

function url_get_contents($Url)
{
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function calculate_time_distance_tomtom($src_lat, $src_lon, $dest_lat, $dest_lon, $unit = "")
{
    if ($unit == 'mi') {
        $gunit = 'imperial';
    } else {
        $gunit = 'metric';
        $unit = 'km';
    }
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.tomtom.com/routing/1/calculateRoute/' . $src_lat . ',' . $src_lon . ':' . $dest_lat . ',' . $dest_lon . '/json?key=' . TOMTOM_API_KEY . '&routeType=eco',
        CURLOPT_USERAGENT => 'Codular Sample cURL Request'
    ]);
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    //curl_close($ch);
    // print_r($resp);die();
    $response = json_decode($resp);
    // echo "<pre>";
    // print_r($response);
    // echo "</pre>";
    // die();
    $distance = 0;
    if (isset($response->error)) {
        return $distance = 0;
    } else {
        if (isset($response->routes[0]->summary->lengthInMeters)) {
            //   if ($response->routes[0]->summary->lengthInMeters < 1) {
            $distance = (round($response->routes[0]->summary->lengthInMeters, 2)); //in meters
            //   }
            //    else {
            //       $distance = (round($response->routes[0]->summary->lengthInMeters / 1000, 2)) . " KM";
            //   }
            return [
                "distance_value" => $distance,
                "duration_value" => $response->routes[0]->summary->travelTimeInSeconds,
            ];
        } else {
            return $distance = 0;
        }
    }
}

function get_config()
{
    $data = array();
    $config_data =  DB::table('config')->get();
    foreach ($config_data as $row) {
        $data[$row->key_name] = $row->key_value;
    }
    return $data;
}

function check_driver_is_on_another_ride($driver_id)
{
    $res = DB::table('driver')
        ->where([
            "id" => $driver_id,
            "on_ride" => '1'
        ])
        ->first();
    return $res;
}

function send_fcm_notification($fcm_ids, $message, $time_to_expire)
{
    if (empty($fcm_ids) || empty($message)) return 0;

    $fields = [
        'registration_ids' => $fcm_ids,
        'data' => $message
    ];

    if ($time_to_expire) {
        $fields['ttl'] = "120s";
    }


    $headers = [
        'Authorization: key=' . config('global.FCM_API_KEY'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, config('global.FCM_URL'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    // print_r($result);die;
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
}

function send_fcm_notification_ios($fcm_ids, $message, $time_to_expire)
{
    if (empty($fcm_ids) || empty($message)) return 0;

    $notification = [
        'body' => $message['text'],
        'title' => $message['title'],
        'sound' => "default"
    ];

    $fields = [
        'registration_ids' => $fcm_ids,
        'notification' => $notification,
        'data' => $message
    ];

    if ($time_to_expire) {
        $fields['time_to_live'] = "120";
    }

    $headers = [
        'Authorization: key=' . config('global.FCM_API_KEY'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, config('global.FCM_URL'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    // print_r($result);die;
    curl_close($ch);
}

function send_notification(array $fcm_id, array $notification_data, $device_type, $time_to_expire = 0)
{
    if (strtolower($device_type) == "ios") {
        send_fcm_notification_ios($fcm_id, $notification_data, $time_to_expire);
    } else {
        send_fcm_notification($fcm_id, $notification_data, $time_to_expire);
    }
}


function check_ride_status($ride_id)
{
    return DB::table('ride')->where('id', $ride_id)->value('ride_status_id');
}

function ride_status_name($id)
{
    return DB::table('ride_status')->where('id', $id)->value('name');
}

function generate_four_digit_otp_number()
{
    return  rand(1000, 9999);
}

function get_driver_id_from_ride($ride_id)
{

    return DB::table('ride')->where('id', $ride_id)->value('driver_id');
}

function get_rider_id_from_ride($ride_id)
{

    return DB::table('ride')->where('id', $ride_id)->value('rider_id');
}


function check_driver_last_ride($driver_id)
{
    return DB::table('ride')->where('driver_id', $driver_id)->orderByDesc('id')->limit(1)->value('id');
}

function check_rider_last_ride($rider_id)
{
    return DB::table('ride')->where('rider_id', $rider_id)->orderByDesc('id')->limit(1)->value('id');
}

function check_rating_submitted_by_driver($ride_id)
{
    return DB::table('ride')->where('id', $ride_id)->where('rated_by_driver', '1')->value('id');
}

function check_rating_submitted_by_rider($ride_id)
{
    return DB::table('ride')->where('id', $ride_id)->where('rated_by_rider', '1')->value('id');
}

function get_vehicle_price($ride_id)
{
    return DB::table('vehicle_driver_history')->where('ride_id', $ride_id)->select('min_price', 'per_km_price', 'per_mile_price', 'per_minute_price', 'base_price')->first();
}

function get_vehicle_id_from_driver($driver_id)
{
    return DB::table('vehicle')->where('driver_id', $driver_id)->value('id');
}

function get_vehicle_subtype_id_from_driver($driver_id)
{
    return DB::table('driver as d')
        ->join('vehicle as v', 'v.driver_id', '=', 'd.id')
        ->join('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
        ->join('vehicle_subtype as vst', 'vst.id', '=', 'vm.vehicle_subtype_id')
        ->where('d.id', $driver_id)->value('vst.id');
}

function check_running_ride_type_id_for_rider($rider_id)
{
    return DB::table('ride')->select('ride_type_id')->where('rider_id', $rider_id)->whereNotIN('ride_status_id', ['7', '8'])->orderByDesc('id')->limit(1)->get();
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
