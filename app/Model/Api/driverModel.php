<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Api\ride;

class driverModel extends Model
{
    //
    protected $table = 'driver';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function ride_accept_or_reject_notification($driver_id)
    {
        return DB::table('ride')
            ->where([
                'driver_id' => $driver_id,
                'ride_status_id' => '1'
            ])
            ->orderByDesc('id')
            ->first();
    }

    public function vehicle_driver_history($vehicle_driver_history)
    {
        DB::table('vehicle_driver_history')->insertGetId($vehicle_driver_history);
        return true;
    }

    public function vehicle_info($driver_id)
    {
        $image_path = url('/') . "/uploads/driver/docs/" . $driver_id . "/";
        return DB::table('driver as d')
            ->leftjoin('vehicle as v', 'v.driver_id', '=', 'd.id')
            ->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
            ->leftjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
            ->leftjoin('vehicle_subtype as vst', 'vst.id', '=', 'vm.vehicle_subtype_id')
            ->leftjoin('vehicle_make as vma', 'vma.id', '=', 'vm.vehicle_make_id')
            ->select('v.id', 'v.vehicle_model_id', 'v.mfg_year', 'vm.vehicle_make_id', 'vm.vehicle_type_id', 'vm.vehicle_subtype_id', 'v.vehicle_color', 'v.vehicle_number', 'd.dl_number', 'd.dl_expiry_date', DB::raw("(CASE WHEN v.vehicle_image='' THEN '' WHEN v.vehicle_image IS NULL THEN ''  ELSE concat('$image_path',v.vehicle_image) END) as vehicle_image"), DB::raw("(CASE WHEN v.ins_image='' THEN '' WHEN v.ins_image IS NULL THEN ''  ELSE concat('$image_path',v.ins_image) END) as ins_image"), DB::raw("(CASE WHEN v.reg_image='' THEN '' WHEN v.reg_image IS NULL THEN ''  ELSE concat('$image_path',v.reg_image) END) as reg_image"), DB::raw("(CASE WHEN d.dl_image='' THEN '' WHEN d.dl_image IS NULL THEN ''  ELSE concat('$image_path',d.dl_image) END) as dl_image"))
            ->where("d.id", $driver_id)
            ->first();
    }

    public function earning_list($driver_id)
    {
        return DB::table('driver as d')
            ->join('ride as r', 'r.driver_id', '=', 'd.id')
            ->join('rider as ri', 'ri.id', '=', 'r.rider_id')
            ->leftjoin('ride_rating as rt', function ($join) {
                $join->on('rt.rider_id', '=', 'ri.id');
                $join->on('rt.rating_to', '=', DB::raw("'driver'"));
            })
            ->select(DB::raw("round(AVG(rt.rating),1) as rider_rating"), 'ri.full_name as rider_name', 'r.start_location', 'r.end_location', 'r.pickup_time', DB::raw('round((duration)/60) as duration_in_minutes'), DB::raw("round(r.distance/1000) as distance_in_km"), 'r.driver_earning')
            ->where("d.id", $driver_id)
            ->get();
    }
}
