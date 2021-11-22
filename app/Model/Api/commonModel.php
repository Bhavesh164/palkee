<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class commonModel extends Model
{
    public static function check_value_exist($table_name, $where_array, $primary_field, $primary_id)
    {

        $where_clause = "";

        //$field_value = $db->escape($field_value);
        $idcheck = '';
        if ($primary_id != '') {
            $primary_id = intval($primary_id);
            $idcheck = " and " . $primary_field . "!='$primary_id'";
        }

        $wherecount = 0;
        foreach ($where_array as $field => $value) {
            if ($wherecount > 0) {
                $where_clause .= ' AND ';
            }
            $where_clause .= $field . "='" . $value . "'";
            $wherecount++;
        }
        $req =  DB::select("Select * from " . $table_name . " WHERE {$where_clause} $idcheck");
        $count = count($req);
        if ($count > 0) {
            return $req;
        } else {
            return FALSE;
        }
    }

    public static function location_list($table_name, $where = '')
    {

        $req = DB::select("select * from $table_name $where");

        //      while($row = $req->fetch_assoc()) {
        //        $data[] = $row;
        //      }
        return $req;
    }

    public static function get_subtypes($type_id)
    {

        $data_q = "
            select * 
            from vehicle_subtype 
            where id in (
                select vehicle_subtype_id
                from vehicle_model
                where vehicle_type_id = $type_id
            ) and is_activated = '1'
            order by subtype_name
        ";

        $req = DB::select($data_q);

        return $req;
    }

    public static function get_makes($type_id, $subtype_id)
    {

        $data_q = "
            select * 
            from vehicle_make 
            where id in (
                select vehicle_make_id
                from vehicle_model
                where vehicle_type_id = $type_id and vehicle_subtype_id = $subtype_id
            ) and is_activated = '1'
            order by make_name
        ";

        $req = DB::select($data_q);

        return $req;
    }

    public static function get_models($type_id, $subtype_id, $make_id)
    {
        global $db;

        $data_q = "
            select *
            from vehicle_model
            where vehicle_make_id = $make_id and vehicle_type_id = $type_id and vehicle_subtype_id = $subtype_id and is_activated = '1'
            order by model_name
        ";

        $req = DB::select($data_q);

        return $req;
    }

    public static function get_vehicle_colors_list()
    {

        global $db;

        $req = DB::select("select * from vehicle_colors order by color_name");

        return $req;
    }

    public static function get_config()
    {
        $results = DB::table('config')->get();
        $data = array();
        foreach ($results as $row) {
            $data[$row->key_name] = $row->key_value;
        }

        return (object)$data;
    }

    public static function get_driver_name($id)
    {
        $driver_name  = DB::table('driver')->where('id', $id)->value('full_name');

        return $driver_name;
    }
    public static function get_rider_name($id)
    {
        $rider_name  = DB::table('rider')->where('id', $id)->value('full_name');

        return $rider_name;
    }
    public static function get_ride_number($id)
    {
        $ride_number  = DB::table('ride')->where('id', $id)->value('ride_number');

        return $ride_number;
    }

    public static function get_rider_menu_list()
    {
        $image_path = url('/') . '/uploads/admin/rider_menu/';

        $rider_menu_list = DB::table('rider_menu')->select('rider_menu.*')->selectRaw('CONCAT("' . $image_path . '","",image) as image')->where([
            ['is_activated', '=', '1'],
        ])->orderBy('priority', 'asc')->get();

        //        $rider_menu_list = DB::select("SELECT rider_menu.*,ride_types.name as ride_type,GROUP_CONCAT(vehicle_type.type_name) as vehicle_types FROM `rider_menu` "
        //                . "left join vehicle_type on FIND_IN_SET(vehicle_type.id,rider_menu.vehicle_type_ids) "
        //                . "left join ride_types on ride_types.id = rider_menu.ride_type "
        //                . "where rider_menu.is_activated = '1' GROUP BY rider_menu.id ORDER BY rider_menu.priority ASC");

        return $rider_menu_list;
    }

    public static function get_nearby_drivers($vehicle_type_ids, $lat, $lon, $where)
    {
        $config_data = get_config();
        $distance_unit = $config_data['distance_unit'];
        if ($config_data['distance_unit'] == 'km') {
            $range_in_km = $config_data['driver_assignment_area_in_km'];
        } else {
            $range_in_km = $config_data['driver_assignment_area_in_miles'];
        }


        $vehicle_type_image_path = url('/') . '/uploads/vehicle_type/';

        $query = "select driver.id,driver.full_name,driver.lat,driver.lon,driver.driver_code,vehicle_type.type_name as vehicle_type_name,CONCAT('$vehicle_type_image_path','',vehicle_type.image) as vehicle_type_image, "
            . "(6371 * acos (cos ( radians($lat) )
      * cos( radians( driver.lat ) )
      * cos( radians( driver.lon ) - radians($lon) )
      + sin ( radians($lat) )
      * sin( radians( driver.lat ) )
    )
  ) AS distance from driver "
            . "inner join vehicle on vehicle.driver_id = driver.id and driver.is_activated = 1 "
            . "inner join vehicle_model on vehicle_model.id = vehicle.vehicle_model_id and vehicle_model.is_activated = 1 "
            . "inner join vehicle_type on vehicle_type.id = vehicle_model.vehicle_type_id and vehicle_type.is_activated = 1 "
            . "where $where and vehicle_model.vehicle_type_id in ($vehicle_type_ids) "
            . "and driver.is_online = 1 and driver.is_available = 1 "
            . "having distance<=$range_in_km order by distance asc";

        $req_data = DB::select($query);
        $data = array();
        foreach ($req_data as $row) {
            $row->distance = round($row->distance, 2) . ' ' . $distance_unit;

            $data[] = $row;
        }
        return $data;
    }

    public static function nearby_vehicle_sub_types($vehicle_type_ids, $lat, $lon, $where)
    {
        $config_data = get_config();
        $distance_unit = $config_data['distance_unit'];
        if ($config_data['distance_unit'] == 'km') {
            $range_in_km = $config_data['driver_assignment_area_in_km'];
        } else {
            $range_in_km = $config_data['driver_assignment_area_in_miles'];
        }

        $vehicle_type_image_path = url('/') . '/uploads/vehicle_type/';

        $query = "select vehicle_subtype.*,vehicle_type.type_name as vehicle_type_name,CONCAT('$vehicle_type_image_path','',vehicle_type.image) as vehicle_type_image, "
            . "MIN((6371 * acos (cos ( radians($lat) )
            * cos( radians( driver.lat ) )
            * cos( radians( driver.lon ) - radians($lon) )
            + sin ( radians($lat) )
            * sin( radians( driver.lat ) )
          )
  )) AS distance from driver "
            . "inner join vehicle on vehicle.driver_id = driver.id and driver.is_activated = 1 "
            . "inner join vehicle_model on vehicle_model.id = vehicle.vehicle_model_id and vehicle_model.is_activated = 1 "
            . "inner join vehicle_type on vehicle_type.id = vehicle_model.vehicle_type_id and vehicle_type.is_activated = 1 "
            . "inner join vehicle_subtype on vehicle_subtype.id = vehicle_model.vehicle_subtype_id and vehicle_subtype.is_activated = 1 "
            . "where $where and vehicle_model.vehicle_type_id in ($vehicle_type_ids) "
            . "and driver.is_online = 1 and driver.is_available = 1 "
            . "group by vehicle_subtype.id "
            . "having distance<=$range_in_km order by vehicle_subtype.subtype_name asc";
        $req_data = DB::select($query);

        return $req_data;
    }

    public static function get_nearby_drivers_for_vehicle_subtype($vehicle_subtype_id, $lat, $lon, $where)
    {
        $config_data = get_config();
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        $distance_unit = $config_data['distance_unit'];
        if ($config_data['distance_unit'] == 'km') {
            $range_in_km = $config_data['driver_assignment_area_in_km'];
        } else {
            $range_in_km = $config_data['driver_assignment_area_in_miles'];
        }


        $query = "select driver.id,driver.full_name,concat(driver.dial_code,'-',phone) as phone ,driver.image as driver_image, driver.driver_code,vehicle_model.model_name,vehicle.vehicle_number,vehicle.vehicle_color,round(AVG(ride_rating.rating),1) as avg_rating,(CASE WHEN vehicle_model.image='' THEN '' WHEN vehicle_model.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vehicle_model.image) END) as vehicle_image, "
            . "(6371 * acos (cos ( radians($lat) )
      * cos( radians( driver.lat ) )
      * cos( radians( driver.lon ) - radians($lon) )
      + sin ( radians($lat) )
      * sin( radians( driver.lat ) )
    )
  ) AS distance from driver "
            . "inner join vehicle on vehicle.driver_id = driver.id and driver.is_activated = 1 "
            . "inner join vehicle_model on vehicle_model.id = vehicle.vehicle_model_id and vehicle_model.is_activated = 1 "
            . "inner join vehicle_type on vehicle_type.id = vehicle_model.vehicle_type_id and vehicle_type.is_activated = 1 "
            . "left join ride_rating on ride_rating.driver_id = driver.id and ride_rating.rating_to = 'driver' "
            . "where $where and vehicle_model.vehicle_subtype_id = $vehicle_subtype_id "
            . "and driver.is_online = 1 and driver.is_available = 1 "
            . "group by driver.id "
            . "having distance<=$range_in_km order by distance asc";

        $req_data = DB::select($query);
        $data = array();
        foreach ($req_data as $key => $row) {
            $driver_image_path = url('/') . '/uploads/driver/';
            if ($row->driver_image) {
                $row->driver_image = $driver_image_path . $row->driver_image;
            }
            $row->distance = round($row->distance, 2) . ' ' . $distance_unit;
            if ($row->avg_rating == null) {
                $row->avg_rating = "";
            }

            $data[] = $row;
        }
        return $data;
    }

    public static function getcountry($request)
    {
        $countryList = array();

        $response = Self::location($request, 'country', '');
        $response = json_decode($response, true);
        foreach ($response['data'] as $index => $datum) {
            $countryList[$datum['countryId']] = $datum['name'];
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

    public static function  location($request, $type = '', $id = '')
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

    public static function get_nearby_drivers_for_offline_drive($lat, $lon)
    {
        $config_data = get_config();
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        $distance_unit = $config_data['distance_unit'];
        if ($config_data['distance_unit'] == 'km') {
            $range_in_km = $config_data['driver_assignment_area_in_km'];
        } else {
            $range_in_km = $config_data['driver_assignment_area_in_miles'];
        }


        $query = "select driver.id,driver.driver_code,driver.full_name,concat(driver.dial_code,'-',phone) as phone ,driver.image as driver_image, driver.driver_code,vehicle_model.model_name,vehicle.vehicle_number,vehicle.vehicle_color,round(AVG(ride_rating.rating),1) as avg_rating,(CASE WHEN vehicle_model.image='' THEN '' WHEN vehicle_model.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vehicle_model.image) END) as vehicle_image, "
            . "(6371 * acos (cos ( radians($lat) )
      * cos( radians( driver.lat ) )
      * cos( radians( driver.lon ) - radians($lon) )
      + sin ( radians($lat) )
      * sin( radians( driver.lat ) )
    )
  ) AS distance from driver "
            . "inner join vehicle on vehicle.driver_id = driver.id and driver.is_activated = 1 "
            . "inner join vehicle_model on vehicle_model.id = vehicle.vehicle_model_id and vehicle_model.is_activated = 1 "
            . "inner join vehicle_type on vehicle_type.id = vehicle_model.vehicle_type_id and vehicle_type.is_activated = 1 and vehicle_type.type_name='Bike' "
            . "left join ride_rating on ride_rating.driver_id = driver.id and ride_rating.rating_to = 'driver' "
            . "and driver.is_online = 1 and driver.is_available = 1  and driver.offline_drive=1 and driver.on_ride=0 "
            . "group by driver.id "
            . "having distance<=$range_in_km order by distance asc";

        $req_data = DB::select($query);
        $data = array();
        foreach ($req_data as $key => $row) {
            $driver_image_path = url('/') . '/uploads/driver/';
            if ($row->driver_image) {
                $row->driver_image = $driver_image_path . $row->driver_image;
            }
            $row->distance = round($row->distance, 2) . ' ' . $distance_unit;
            if ($row->avg_rating == null) {
                $row->avg_rating = "";
            }

            $data[] = $row;
        }
        return $data;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
