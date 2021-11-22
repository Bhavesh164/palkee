<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Admin\Auth;
use App\Model\Admin\ride;
use App\Model\Api\commonModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class cronController extends Controller
{
	/* cases
			1) if requested 0  and driver does not accept ride within minute
	*/
	public function __construct()
	{
		//echo php_sapi_name();
		// if (PHP_SAPI != 'cli') {
		// 	echo 'access denied';
		// 	die;
		// } else {
		$getcwd = dirname(__FILE__) . "/cron.txt";
		$myfile = fopen($getcwd, "a");
		$txt = PHP_SAPI . "\n";
		fwrite($myfile, $txt);
		fclose($myfile);
		// }
	}
	public function forward_request(Request $request)
	{
		$day_start = date("Y-m-d 00:00:01");
		$day_end = date("Y-m-d 23:59:59");
		$now = date("Y-m-d H:i:s");
		$requested_rides = DB::table('ride')
			->where('ride_status_id', 0)
			->whereBetween('pickup_time', [$day_start, $day_end])
			->whereRaw("TIMESTAMPDIFF(MINUTE,pickup_time,'$now') < ? ", ["5"])
			->select('id')
			->get();
		//ride cancelled by driver
		$cancelled_rides = DB::table('ride')
			->where('ride_status_id', '8')
			->where('cancel_by', 'driver')
			->whereBetween('pickup_time', [$day_start, $day_end])
			->whereRaw("TIMESTAMPDIFF(MINUTE,pickup_time,'$now') < ? ", ["5"])
			->select('id')
			->get();
		$rides = $requested_rides->merge($cancelled_rides);
		foreach ($rides as $key => $value) {
			$ride_id = $value->id;
			$this->forward_after_one_minute($ride_id);
		}
	}

	public function forward_after_one_minute($ride_id)
	{
		$driver_id = get_driver_id_from_ride($ride_id);
		$rider_id = get_rider_id_from_ride($ride_id);
		$now = date("Y-m-d H:i:s");
		$data = [
			"driver_id" => $driver_id,
			"rider_id" => $rider_id,
			"cancellation_date" => $now,
			"ride_id" => $ride_id,
		];
		DB::table('ride_cancelled_by_driver')->insert($data);
		$driver_excluded = DB::table('ride_cancelled_by_driver')->where('ride_id', $ride_id)->select(DB::raw('group_concat(driver_id) as driver_id'))->get();
		if (!$driver_excluded->isEmpty()) {
			$driver_excluded = $driver_excluded[0]->driver_id;
		} else {
			$driver_excluded = 0;
		}
		$ride_details = (new ride)->find($ride_id);
		$ride_type_id = $ride_details->ride_type_id;
		$lat = $ride_details->start_lat;
		$lon = $ride_details->start_lon;
		$vehicle_type_ids = $this->get_vehicle_types($ride_type_id);
		$vehicle_type_ids = implode(",", $vehicle_type_ids);
		$where_condition[] = "driver.id NOT IN ($driver_excluded)";

		if ($ride_type_id == 4) {
			$where_condition[]  =   "driver.offline_drive = '1'";
		}

		if (!empty($where_condition)) {
			$where = implode(' and ', $where_condition);
		}

		$nearby_drivers = commonModel::get_nearby_drivers($vehicle_type_ids, $lat, $lon, $where);
		if ($nearby_drivers) {
			$nearby_driver_id = $nearby_drivers[0]->id;
		}
		if (isset($nearby_driver_id)) {
			DB::table('ride')->where('id', $ride_id)->update(["driver_id" => $nearby_driver_id]);
		}
	}

	public function automatic_cancel_ride()
	{
		$day_start = date("Y-m-d 00:00:01");
		$day_end = date("Y-m-d 23:59:59");
		$now = date("Y-m-d H:i:s");
		$requested_rides = DB::table('ride')
			->where('ride_status_id', 0)
			->whereBetween('pickup_time', [$day_start, $day_end])
			->whereRaw("TIMESTAMPDIFF(MINUTE,pickup_time,'$now') > ? ", ["5"])
			->select('id')
			->get();
		foreach ($requested_rides as $key => $value) {
			$ride_id = $value->id;
			DB::table('ride')->where('id', $ride_id)->update(["ride_status_id" => '8']);
		}
	}

	public function get_vehicle_types($ride_type_id)
	{
		$vehicle_type_ids = DB::table('rider_menu')->whereIn('ride_type_id', [$ride_type_id])->select('vehicle_type_ids')->get();
		$vehicle_types = [];
		foreach ($vehicle_type_ids as $key => $value) {
			$temp = explode(',', $value->vehicle_type_ids);
			foreach ($temp as $key2 => $value2) {
				if (!in_array($value2, $vehicle_types)) {
					$vehicle_types[] = $value2;
				}
			}
		}
		return $vehicle_types;
	}
}
