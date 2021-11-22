<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use App\Model\Api\commonModel;


class commonController extends Controller
{

    public function __construct()
    {

        //$this->middleware('auth');

        //$this->middleware('admin')->only('login');

        // $this->middleware('admin')->except('login');
    }

    public function get_rider_menu(Request $request)
    {

        $rider_menu = commonModel::get_rider_menu_list();
        $rider_id = $request->rider_id;
        $check_running_ride = check_running_ride_type_id_for_rider($rider_id);
        if ($check_running_ride->isEmpty()) {
            $response['running_ride_type_id'] = 0;
        } else {
            $response['running_ride_type_id'] = $check_running_ride[0]->ride_type_id;
        }

        $response['status'] = '1';

        $response['data'] = $rider_menu;

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function country_list()
    {

        $countries = DB::table('country')->where([
            ['is_activated', '=', '1']
        ])->orderBy('name', 'asc')->get();

        $response['status'] = '1';

        $response['data'] = $countries;

        return response($response);
    }

    public function region_list(Request $request)
    {
        $errors = array();
        $rules = [
            'country_id' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            //'type_id.required' => 'Type Name is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //            'phone' => 'Phone Number',
            //            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $country_id = $request->country_id;

        if (empty($errors)) {
            $regions = DB::table('region')->where([
                ['countryId', '=', $country_id],
                ['is_activated', '=', '1'],

            ])->orderBy('name', 'asc')->get();
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['data'] = $regions;
        }

        return response($response);
    }

    public function city_list(Request $request)
    {
        $errors = array();
        $rules = [
            'country_id' => 'required',
            'region_id' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            //'type_id.required' => 'Type Name is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //            'phone' => 'Phone Number',
            //            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        $country_id = $request->country_id;
        $region_id = $request->region_id;

        if (empty($errors)) {
            $cities = DB::table('cities')->where([
                ['countryId', '=', $country_id],
                ['regionId', '=', $region_id],
                ['is_activated', '=', '1'],

            ])->orderBy('name', 'asc')->get();
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['data'] = $cities;
        }
        return response($response);
    }

    public function get_nearby_drivers(Request $request)
    {
        $errors = array();
        $rules = [
            'vehicle_type_ids' => 'required',
            'lat'   => 'required',
            'lon' => 'required',
            'ride_type_id' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            //'type_id.required' => 'Type Name is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //            'phone' => 'Phone Number',
            //            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        if (empty($errors)) {

            $vehicle_type_ids = $request->vehicle_type_ids;
            $lat =  $request->lat;
            $lon =  $request->lon;
            $ride_type_id =  $request->ride_type_id;

            $where_condition[] = 1;

            if ($ride_type_id == 4) {
                $where_condition[]  =   "driver.offline_drive = '1'";
            }

            if (!empty($where_condition)) {
                $where = implode(' and ', $where_condition);
            }

            $nearby_drivers = commonModel::get_nearby_drivers($vehicle_type_ids, $lat, $lon, $where);
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else {
            $response['status'] = '1';
            $response['data'] = $nearby_drivers;
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_nearby_vehicle_subtypes_with_cost(Request $request)
    {

        $errors = array();
        $rules = [
            'vehicle_type_ids' => 'required',
            'source_lat'   => 'required',
            'source_lon' => 'required',
            'destination_lat' => 'required',
            'destination_lon'  => 'required',
            'ride_type_id' => 'required',
            // 'time_in_min' => 'required',
            // 'distance' => 'required',
        ];
        $messages = [
            'required' => ':attribute is required.',
            //'type_id.required' => 'Type Name is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //            'phone' => 'Phone Number',
            //            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        if (empty($errors)) {
            $config_data = get_config();
            $distance_unit = $config_data['distance_unit'];


            $vehicle_type_ids = $request->vehicle_type_ids;
            $source_lat =  $request->source_lat;
            $source_lon =  $request->source_lon;
            $destnation_lat =  $request->destination_lat;
            $destination_lon =  $request->destination_lon;
            $ride_type_id =  $request->ride_type_id;
            $time_in_min = 0;
            $distance = 0;

            $route_data = get_google_map_directions($source_lat, $source_lon, $destnation_lat, $destination_lon, $distance_unit);
            $polyline_overview = $route_data['routes'][0]["overview_polyline"]["points"];
            $distance_value = $route_data['routes'][0]["legs"][0]["distance"]["value"];

            $duration_value = $route_data['routes'][0]["legs"][0]["duration"]["value"];

            if ($distance_value) {

                $distance = round($distance_value / 1000, 2);
                $time_in_min = $duration_value / 60;
            }

            $where_condition[] = 1;

            if ($ride_type_id == 4) {
                $where_condition[]  =   "driver.offline_drive = '1'";
            }

            if (!empty($where_condition)) {
                $where = implode(' and ', $where_condition);
            }

            $req_data = commonModel::nearby_vehicle_sub_types($vehicle_type_ids, $source_lat, $source_lon, $where);
            $nearby_vehicle_sub_types = array();

            $config_data = get_config();
            $distance_unit = $config_data['distance_unit'];
            $i = 0;
            $data_type = array();
            foreach ($req_data as $row) {

                $base_price = $row->base_price;

                $per_minute_price = $row->per_minute_price;

                $total_minute_price = $time_in_min * $per_minute_price;

                if ($distance_unit == 'km') {
                    $distance_price = $row->per_km_price;
                } else {

                    $distance_price = $row->per_mile_price;
                }

                $distance_price = $distance * $distance_price;

                $estimate_price = round($base_price + $total_minute_price + $distance_price, 2);


                $data = [
                    'subtype_id' => $row->id,
                    'subtype_name' => $row->subtype_name,
                    'estimate_price' => config('constant.currency_symbol') . '' . $estimate_price,
                ];

                if (!in_array($row->type_id, $data_type)) {
                    $nearby_vehicle_sub_types[$i]['type_id'] = $row->type_id;
                    $nearby_vehicle_sub_types[$i]['type_name'] = $row->vehicle_type_name;
                    $nearby_vehicle_sub_types[$i]['image'] = $row->vehicle_type_image;
                    $data_type[] = $row->type_id;
                    $i++;
                }

                $index = array_search($row->type_id, $data_type);

                $nearby_vehicle_sub_types[$index]['vehicle_subtype'][] = $data;
            }
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else if (empty($nearby_vehicle_sub_types)) {
            $response['status'] = '0';
            $response['message'] = 'No nearby driver found';
        } else {
            $response['status'] = '1';
            //$response['data']['route_data'] = $route_data;
            $response['data']['polyline_overview'] = $polyline_overview;
            $response['data']['vehicle_data'] = $nearby_vehicle_sub_types;
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_nearby_drivers_for_vehicle_subtype(Request $request)
    {
        $errors = array();
        $rules = [
            'lat'   => 'required',
            'lon' => 'required',
            'ride_type_id' => 'required',
            'vehicle_subtype_id' => 'required'
        ];
        $messages = [
            'required' => ':attribute is required.',
            //'type_id.required' => 'Type Name is required.',
            'unique' => ':attribute already exist.',
        ];
        $customAttributes = [
            //            'phone' => 'Phone Number',
            //            'dial_code' => 'Dail Code'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->messages()->all();
        }

        if (empty($errors)) {
            $vehicle_subtype_id = $request->vehicle_subtype_id;
            $lat =  $request->lat;
            $lon =  $request->lon;
            $ride_type_id =  $request->ride_type_id;


            $where_condition[] = 1;

            if ($ride_type_id == 4) {
                $where_condition[]  =   "driver.offline_drive = '1'";
            }

            if (!empty($where_condition)) {
                $where = implode(' and ', $where_condition);
            }


            $get_nearby_drivers_for_vehicle_subtype = commonModel::get_nearby_drivers_for_vehicle_subtype($vehicle_subtype_id, $lat, $lon, $where);
        }

        if (!empty($errors)) {
            $messages = implode(",", $errors);

            $response['status'] = '0';
            $response['message'] = $messages;
        } else if (empty($get_nearby_drivers_for_vehicle_subtype)) {
            $response['status'] = '0';
            $response['message'] = 'No driver available';
        } else {
            $response['status'] = '1';
            $response['data'] = $get_nearby_drivers_for_vehicle_subtype;
        }

        return response($response);
    }

    public function config_values()
    {
        $keys = array('ride_cancel_charges', 'distance_unit');
        $data = array();

        $config_data =  DB::table('config')->get();
        foreach ($config_data as $row) {
            if (in_array($row->key_name, $keys))
                $data[$row->key_name] = $row->key_value;
        }
        $response['status'] = '1';

        $response['data'] = $data;

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    public function get_selected_subtype_list(Request $request)
    {

        if ($request->vehicle_type_id && $request->type == 'json') {
            $vehicle_type_id =  $request->vehicle_type_id;

            $vehicle_subtype = DB::table('vehicle_subtype')
                ->where('type_id', '=', $vehicle_type_id)
                ->where('is_activated', '=', 1)
                ->select('id', 'subtype_name')
                ->orderBy('vehicle_subtype.subtype_name', 'asc')
                ->get();

            if (!empty($vehicle_subtype)) {
                $response['status'] = '1';
                $response['data'] = $vehicle_subtype;
            } else {
                $response['status'] = '0';
                $response['data'] = 'not found';
            }
            return response()->json($response, 200);
        }
    }


    public static function  get_selected_vehicle_subtypes(Request $request, $vehicle_type_id = '')
    {

        if ($request->vehicle_type_id && $request->type == 'json') {
            $vehicle_type_id =  $request->vehicle_type_id;
        }

        $data = commonModel::get_subtypes($vehicle_type_id);

        if ($request->vehicle_type_id && $request->type == 'json') {
            if (!empty($data)) {
                $response['status'] = '1';
                $response['data'] = $data;
            } else {
                $response['status'] = '0';
                $response['data'] = 'not found';
            }
            return json_encode($response);
        } else {
            return  $data;
        }
    }
    public static function  get_selected_vehicle_makes(Request $request, $vehicle_type_id = '', $vehicle_subtype_id = '')
    {

        if ($request->vehicle_type_id && $request->vehicle_subtype_id && $request->type == 'json') {
            $vehicle_type_id =  $request->vehicle_type_id;
            $vehicle_subtype_id = $request->vehicle_subtype_id;
        }

        $data = commonModel::get_makes($vehicle_type_id, $vehicle_subtype_id);


        if ($request->vehicle_type_id && $request->vehicle_subtype_id && $request->type == 'json') {
            if (!empty($data)) {
                $response['status'] = '1';
                $response['data'] = $data;
            } else {
                $response['status'] = '0';
                $response['data'] = 'not found';
            }
            return json_encode($response);
        } else {
            return  $data;
        }
    }

    public static function  get_selected_vehicle_models(Request $request, $vehicle_type_id = '', $vehicle_subtype_id = '', $vehicle_make_id = '')
    {


        if ($request->vehicle_type_id && $request->vehicle_subtype_id && $request->vehicle_make_id && $request->type == 'json') {
            $vehicle_type_id =  $request->vehicle_type_id;

            $vehicle_subtype_id =  $request->vehicle_subtype_id;

            $vehicle_make_id =  $request->vehicle_make_id;
        }

        $data = commonModel::get_models($vehicle_type_id, $vehicle_subtype_id, $vehicle_make_id);


        if ($request->vehicle_type_id && $request->vehicle_subtype_id && $request->vehicle_make_id && $request->type == 'json') {
            if (!empty($data)) {
                $response['status'] = '1';
                $response['data'] = $data;
            } else {
                $response['status'] = '0';
                $response['data'] = 'not found';
            }
            return json_encode($response);
        } else {
            return  $data;
        }
    }

    public function updatestatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $table_name =  $request->table_name;
        $primary_column = $request->primary_column;

        $fieldvalues = [
            'is_activated' => $status,
        ];

        $update = DB::table($table_name)->where($primary_column, $id)->update($fieldvalues);

        return response()->json(array('success' => 1, 'msg' => 'Update successfully'), 200);
    }

    public static function test(Request $request, $test)
    {
        echo $test;
    }

    public function vehicle_type()
    {
        return DB::table("vehicle_type")->where(["is_activated" => "1"])->get();
    }
    public function vehicle_subtype(Request $request)
    {
        $type_id = $request->type_id;
        return DB::table("vehicle_subtype")->where(["is_activated" => "1", "type_id" => $type_id])->get();
    }
    public function vehicle_make(Request $request)
    {
        $type_id = $request->type_id;
        $subtype_id = $request->subtype_id;
        $query = "
            select * 
            from vehicle_make 
            where id in (
                select vehicle_make_id
                from vehicle_model
                where vehicle_type_id = $type_id and vehicle_subtype_id = $subtype_id
            ) and is_activated = '1'
            order by make_name
        ";
        $vehicle_make = DB::select($query);
        return $vehicle_make;
    }
    public function vehicle_model(Request $request)
    {
        $type_id = $request->type_id;
        $subtype_id = $request->subtype_id;
        $make_id = $request->make_id;
        return DB::table("vehicle_model")->where(["is_activated" => "1", "vehicle_type_id" => $type_id, "vehicle_subtype_id" => $subtype_id, "vehicle_make_id" => $make_id])->get();
    }


    public function get_nearby_drivers_for_offline_drive(Request $request)
    {
        $lat = $request->lat;
        $lon = $request->lon;
        $data = (new commonModel)->get_nearby_drivers_for_offline_drive($lat, $lon);
        if ($data) {
            $response['data'] = $data;
            $response['message'] = "Success";
        } else {
            $response['status'] = '0';
            $response['message'] = 'No nearby driver found';
        }
        return response($response);
    }

    public function vehicle_colors(Request $request)
    {
        $data = DB::table('vehicle_colors')->select("id", "color_name")->get();
        if ($data) {
            $response['data'] = $data;
            $response['message'] = "Success";
        } else {
            $response['status'] = '0';
            $response['message'] = 'No color found';
        }
        return response($response);
    }
}
