<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Api\driverModel;
use App\Model\Api\promocode;
use Illuminate\Http\Request;

class ride extends Model
{
    //
    protected $table = 'ride';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $fillable = ['full_name',''];
    protected $guarded = [];

    public function arrived_at_location($ride_id)
    {
        return $this->where('id', $ride_id)->update(["ride_status_id" => '4']);
    }

    public function update_ride($ride_id, $data)
    {
        return $this->where('id', $ride_id)->update($data);
    }

    public function get_ride($ride_id)
    {
        $driver_id = get_driver_id_from_ride($ride_id);
        $driver_image_path = config('global.DRIVER_IMAGE_PATH');
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        $rider_image_path = config('global.RIDER_IMAGE_PATH');
        return DB::table('vehicle as v')
            ->join('driver as d', 'd.id', '=', 'v.driver_id')
            ->join('ride as r', 'r.driver_id', '=', 'd.id')
            ->join('rider as ri', 'ri.id', '=', 'r.rider_id')
            ->join('ride_status as rs', 'rs.id', '=', 'r.ride_status_id')
            ->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
            ->leftjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
            ->leftjoin('vehicle_subtype as vst', 'vst.id', '=', 'vm.vehicle_subtype_id')
            ->leftjoin('vehicle_make as vma', 'vma.id', '=', 'vm.vehicle_make_id')
            ->select('v.mfg_year', 'v.vehicle_color', 'vma.make_name', 'vm.model_name', 'vt.type_name', 'vst.subtype_name', 'vst.min_price', 'vst.per_km_price', 'vst.per_mile_price', 'vst.per_minute_price', 'vst.base_price', 'v.vehicle_number', 'd.full_name as driver_name', 'd.email', DB::raw('concat(d.dial_code,d.phone) as phone'), DB::raw("(CASE WHEN d.image='' THEN '' ELSE concat('$driver_image_path',d.image) END) as driver_image"), DB::raw("(CASE WHEN vm.image='' THEN '' WHEN vm.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vm.image) END) as vehicle_image"), 'r.est_duration_text', 'r.est_duration_secs', 'r.est_distance_text', 'r.est_distance_meters', 'r.ride_status_id', 'rs.name as ride_status', 'r.rated_by_driver', 'r.rated_by_rider', 'r.id as ride_id', 'r.start_location', 'r.end_location', 'r.is_payment', 'r.payment_type', 'r.cash_payment_confirmed_by_driver', DB::raw("(CASE WHEN ri.image='' THEN '' ELSE concat('$rider_image_path',ri.image) END) as rider_image"), DB::raw("round(r.distance/1000) as distance_in_km"), DB::raw("round(r.duration/60) as duration_in_minutes"), DB::raw("(r.distance_fare+r.base_fare+r.time_fare) as total_cost"), 'd.full_name as driver_name', 'ri.full_name as rider_name', 'r.polyline_overview', DB::raw('concat(ri.dial_code,ri.phone) as rider_phone'), 'r.created_at as created_on', 'r.cancellation_time as cancel_on', 'r.accepted_on', 'r.arrived_on_rider_location', 'r.started_ride_on', 'r.end_ride_on', 'd.lat as driver_lat', 'd.lon as driver_lon', 'r.ride_type_id', 'd.is_online')
            ->where("d.id", $driver_id)
            ->where("r.id", $ride_id)
            ->first();
    }

    public function calculate_cost($distance_meters, $duration_secs, $driver_id, $ride_details)
    {
        //config('constant.currency_symbol')
        $config_data = get_config();
        if ($config_data['commission_type'] == 'per') {
            $commission_value = $config_data['commission_value'] / 100;
        } else {
            $commission_value = $config_data['commission_value'];
        }
        $vehicle_cost = get_vehicle_price($ride_details->id);
        $base_price = $vehicle_cost->base_price;
        $min_price = $vehicle_cost->min_price;
        $per_km_price = $vehicle_cost->per_km_price;
        $per_mile_price = $vehicle_cost->per_km_price;
        $per_minute_price = $vehicle_cost->per_minute_price;

        $minutes = $duration_secs / 60;
        $distance_in_km = $distance_meters / 1000;
        $distance_in_miles = $distance_meters / 1609;

        if ($ride_details->ride_type_id == '3') {
            $duration_cost = $price = $this->calculate_hourly_duration_cost($minutes, $per_minute_price, $ride_details->hourly);
        } else {
            $duration_cost = $price = round($per_minute_price * $minutes);
        }

        $distance_cost = $km_price = round($per_km_price * $distance_in_km);
        $mile_price = round($per_mile_price * $distance_in_miles);

        $ride_cost = $total_cost = $price + $km_price + $base_price;

        if ($total_cost < $min_price) {
            $total_cost = $min_price;
        }

        $driver_earning = $commission_value * $total_cost;
        $admin_earning = $total_cost - $driver_earning;
        $discount_amount = (new promocode)->discount($ride_details, $ride_cost);
        $tax = (($total_cost - $discount_amount) * $config_data['tax']) / 100;
        $data = compact(
            'min_price',
            'base_price',
            'per_km_price',
            'per_minute_price',
            'distance_cost',
            'duration_cost',
            'ride_cost',
            'tax',
            'total_cost',
            'driver_earning',
            'discount_amount',
            'admin_earning'
        );
        // print_r($data);die();
        return $data;
    }

    public function invoice($ride_details)
    {
        $ride_id = $ride_details->id;
        $driver_id = $ride_details->driver_id;
        $rider_id = $ride_details->rider_id;
        $driver_image_path = config('global.DRIVER_IMAGE_PATH');
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        $rider_image_path = config('global.RIDER_IMAGE_PATH');
        return DB::table('vehicle as v')
            ->join('driver as d', 'd.id', '=', 'v.driver_id')
            ->join('ride as r', 'r.driver_id', '=', 'd.id')
            ->join('rider as ri', 'ri.id', '=', 'r.rider_id')
            ->join('ride_status as rs', 'rs.id', '=', 'r.ride_status_id')
            ->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
            ->leftjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
            ->leftjoin('vehicle_make as vma', 'vma.id', '=', 'vm.vehicle_make_id')
            ->leftjoin('ride_rating as rt', function ($join) {
                $join->on('rt.driver_id', '=', 'd.id');
                $join->on('rt.rating_to', '=', DB::raw("'driver'"));
            })
            ->select('v.mfg_year', 'v.vehicle_color', 'vma.make_name', 'vm.model_name', 'vt.type_name',  'v.vehicle_number', 'd.full_name as driver_name', 'd.email', 'd.phone', DB::raw("(CASE WHEN d.image='' THEN '' ELSE concat('$driver_image_path',d.image) END) as driver_image"), DB::raw("(CASE WHEN vm.image='' THEN '' WHEN vm.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vm.image) END) as vehicle_image"), DB::raw("(r.distance_fare+r.base_fare+r.time_fare) as total_cost"), 'r.discount_amount', DB::raw("round(r.distance/1000) as distance_in_km"), DB::raw("round(r.duration/60) as duration_in_minutes"), DB::raw("round(AVG(rt.rating),1) as driver_rating"), 'r.start_location', 'r.end_location', 'r.payment_type', 'ri.full_name as rider_name', DB::raw("(CASE WHEN ri.image='' THEN '' ELSE concat('$rider_image_path',ri.image) END) as rider_image"), 'r.id as ride_id', 'r.tax', DB::raw('((r.distance_fare+r.base_fare+r.time_fare)-r.discount_amount) as sub_total'), DB::raw('(((r.distance_fare+r.base_fare+r.time_fare)-r.discount_amount)+r.tax) as grand_total'))
            ->where("d.id", $driver_id)
            ->where("r.id", $ride_id)
            ->where("r.ride_status_id", '7')
            ->first();
    }

    public function ride_detail($ride_details)
    {
        $ride_id = $ride_details->id;
        $driver_id = $ride_details->driver_id;
        $rider_id = $ride_details->rider_id;
        $driver_image_path = config('global.DRIVER_IMAGE_PATH');
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        $rider_image_path = config('global.RIDER_IMAGE_PATH');
        return DB::table('vehicle as v')
            ->join('driver as d', 'd.id', '=', 'v.driver_id')
            ->join('ride as r', 'r.driver_id', '=', 'd.id')
            ->join('rider as ri', 'ri.id', '=', 'r.rider_id')
            ->join('ride_status as rs', 'rs.id', '=', 'r.ride_status_id')
            ->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
            ->leftjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
            ->leftjoin('vehicle_make as vma', 'vma.id', '=', 'vm.vehicle_make_id')
            ->leftjoin('ride_rating as rt', function ($join) {
                $join->on('rt.driver_id', '=', 'd.id');
                $join->on('rt.rating_to', '=', DB::raw("'driver'"));
            })
            ->select('v.mfg_year', 'v.vehicle_color', 'vma.make_name', 'vm.model_name', 'vt.type_name',  'v.vehicle_number', 'd.full_name as driver_name', 'd.email', 'd.phone', DB::raw("(CASE WHEN d.image='' THEN '' ELSE concat('$driver_image_path',d.image) END) as driver_image"), DB::raw("(CASE WHEN vm.image='' THEN '' WHEN vm.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vm.image) END) as vehicle_image"), DB::raw("(r.distance_fare+r.base_fare+r.time_fare) as total_cost"), 'r.discount_amount', DB::raw("round(r.distance/1000) as distance_in_km"), DB::raw("round(r.duration/60) as duration_in_minutes"), DB::raw("round(AVG(rt.rating),1) as driver_rating"), 'r.start_location', 'r.end_location', 'r.payment_type', 'ri.full_name as rider_name', DB::raw("(CASE WHEN ri.image='' THEN '' ELSE concat('$rider_image_path',ri.image) END) as rider_image"), 'r.id as ride_id', DB::raw('0.00 as tax'), DB::raw('(r.distance_fare+r.base_fare+r.time_fare) as sub_total'), DB::raw('((r.distance_fare+r.base_fare+r.time_fare)-r.discount_amount) as grand_total'), 'rs.name as ride_status')
            ->where("d.id", $driver_id)
            ->where("r.id", $ride_id)
            ->whereIn("r.ride_status_id", ['3', '7', '8'])
            ->first();
    }

    private function calculate_hourly_duration_cost($minutes, $per_minute_price, $hourly)
    {
        $in_min = $hourly * 60;
        if ($minutes > $in_min) {
            if ($minutes > 60 and $minutes < 121) {
                $minutes = 120;
            } else if ($minutes > 120 and $minutes < 181) {
                $minutes = 180;
            } else if ($minutes > 180 and $minutes < 240) {
                $minutes = 240;
            } else if ($minutes > 240 and $minutes < 301) {
                $minutes = 300;
            } else if ($minutes > 300 and $minutes < 361) {
                $minutes = 360;
            } else if ($minutes > 360 and $minutes < 421) {
                $minutes = 420;
            } else if ($minutes > 420 && $minutes < 481) {
                $minutes = 480;
            } else if ($minutes > 480 && $minutes < 541) {
                $minutes = 540;
            } else if ($minutes > 540 && $minutes < 601) {
                $minutes = 600;
            } else if ($minutes > 600 && $minutes < 661) {
                $minutes = 660;
            } else if ($minutes > 660 && $minutes < 721) {
                $minutes = 720;
            }
            $cost = $minutes * $per_minute_price;
        } else {
            $cost = $in_min * $per_minute_price;
        }
        return $cost;
    }

    public function ride_history($key, $id, $limit, $offset)
    {
        //DB::enableQueryLog();
        $query = DB::table('ride as r')
            ->join('ride_status as rs', 'rs.id', '=', 'r.ride_status_id')
            ->join('ride_types as rt', 'rt.id', '=', 'r.ride_type_id')
            ->select('r.id', 'r.start_location', 'r.end_location', DB::raw('(base_fare+distance_fare+time_fare) as total_cost'), 'r.created_at', DB::raw("round(r.distance/1000) as distance_in_km"), DB::raw("round(r.duration/60) as duration_in_minutes"), 'rt.name as ride_type_name', 'rs.name as ride_status_name', 'r.ride_status_id');
        if ($key == 'driver') {
            $query->where('r.driver_id', '=', $id);
        } else {
            $query->where('r.rider_id', '=', $id);
        }
        $offset = ($offset - 1) * $limit;
        $query->orderByDesc('r.id')
            ->offset($offset)
            ->limit($limit);
        $events = $query->get();
        return $events;
    }
}
