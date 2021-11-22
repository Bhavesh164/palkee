<?php

namespace App\Model\Admin;

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

	public static function get_page($key)
	{
		return DB::table('config')->where('key_name', $key)->value("key_value");
	}
	public static function update_page($key, $data)
	{
		DB::table('config')->where('key_name', $key)->update(["key_value" => $data]);
	}

	public static function total_vehicles()
	{
		return DB::table('vehicle')
			->where([
				['driver_id', '<>', '0']
			])->count();
	}
	public static function total_drivers()
	{
		return DB::table('driver')
			->count();
	}
	public static function total_riders()
	{
		return DB::table('rider')
			->count();
	}
	public static function total_rides()
	{
		return DB::table('ride')
			->count();
	}

	public static function rides_count_by_vehicle_type()
	{
		return DB::table('ride as r')
			->select(DB::raw('count(r.id) as count'), 'vt.type_name')
			->leftjoin('driver as d', 'd.id', '=', 'r.driver_id')
			->leftjoin('vehicle as v', 'v.driver_id', '=', 'd.id')
			->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
			->rightjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
			->groupBy('vt.id')
			->orderByDesc('vt.id')
			->get();
	}

	public static function sale()
	{
		return DB::table('ride')
			->select(DB::raw('COALESCE(sum(base_fare+distance_fare+time_fare),0.00) as total_sale'), 'admin_earning')
			->where('ride_status_id', '7')
			->first();
	}
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
