<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Model\Admin\commonModel;


class commonController extends Controller
{

    public function __construct()
    {

        //$this->middleware('auth');

        //$this->middleware('admin')->only('login');

        // $this->middleware('admin')->except('login');
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
                $response['success'] = '1';
                $response['data'] = $vehicle_subtype;
            } else {
                $response['success'] = '0';
                $response['data'] = 'not found';
            }
            return response()->json($response, 200);
        }
    }

    public static function getcountry($request)
    {
        $countryList = array();

        $response = Self::location($request, 'country', '');
        $response = json_decode($response, true);
        foreach ($response['data'] as $index => $datum) {
            $countryList[$datum['countryId']] = $datum['name'];
            //$countryList[$datum['countryname']] = $datum['name'];

        }

        return $countryList;
    }

    public static function getregions($request, $country_id)
    {
        $regionList = array();
        $response = Self::location($request, 'region', $country_id);
        $response = json_decode($response, true);
        foreach ($response['data'] as $index => $datum) {
            $regionList[$datum['regionId']] = $datum['name'];
            //$countryList[$datum['countryname']] = $datum['name'];

        }

        return $regionList;
    }
    public static function getcities($request, $region_id)
    {
        $cityList = array();
        $response = Self::location($request, 'cities', $region_id);
        $response = json_decode($response, true);
        foreach ($response['data'] as $index => $datum) {
            $cityList[$datum['cityId']] = $datum['name'];
            //$countryList[$datum['countryname']] = $datum['name'];
        }

        return $cityList;
    }

    public static function  location(Request $request, $type = '', $id = '')
    {

        // $_model = self::$_model;
        // require_once('models/'.$_model.'.php');

        $where_condition = array();
        $where = '';
        if (isset($request->type)) {
            $type  = $request->type;
            if ($request->country_id) {
                $counrty_id = $request->country_id;
                $where_condition[] = 'countryId =' . $counrty_id;
            }
            if ($request->region_id) {
                $region_id = $request->region_id;
                $where_condition[] = 'regionId =' . $region_id;
            }
        } else {
            if ($id) {
                if ($type == 'region') {
                    $where_condition[] = 'countryId =' . $id;
                }
                if ($type == 'cities') {
                    $where_condition[] = 'regionId =' . $id;
                }
            }
        }

        $table_name = $type;

        if (!empty($where_condition)) {
            $where = implode(' and ', $where_condition);

            $where = 'where ' . $where;
        }

        $location_list = commonModel::location_list($table_name, $where);

        if (!empty($location_list)) {
            $response['success'] = '1';
            $response['data'] = $location_list;
        } else {
            $response['success'] = '0';
            $response['data'] = 'not found';
        }
        //        if(isset($request->type))
        //        {
        //            echo json_encode($response);
        //        }
        //        else
        //        {
        //            return  json_encode($response);
        //        }

        return  json_encode($response);
    }

    public static function  get_selected_vehicle_subtypes(Request $request, $vehicle_type_id = '')
    {

        if ($request->vehicle_type_id && $request->type == 'json') {
            $vehicle_type_id =  $request->vehicle_type_id;
        }

        $data = commonModel::get_subtypes($vehicle_type_id);

        if ($request->vehicle_type_id && $request->type == 'json') {
            if (!empty($data)) {
                $response['success'] = '1';
                $response['data'] = $data;
            } else {
                $response['success'] = '0';
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
                $response['success'] = '1';
                $response['data'] = $data;
            } else {
                $response['success'] = '0';
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
                $response['success'] = '1';
                $response['data'] = $data;
            } else {
                $response['success'] = '0';
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
}
