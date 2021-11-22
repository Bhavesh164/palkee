<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class vehicle extends Model
{
    //
    protected $table = 'vehicle';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $fillable = ['full_name',''];
    protected $guarded = ['id'];

    public function get_vehicle_details_from_driver($driver_id)
    {
        $vehicle_image_path = config('global.VEHICLE_MODEL_IMAGE_PATH');
        return DB::table('vehicle as v')
            ->join('driver as d', 'd.id', '=', 'v.driver_id')
            ->leftjoin('vehicle_model as vm', 'vm.id', '=', 'v.vehicle_model_id')
            ->leftjoin('vehicle_type as vt', 'vt.id', '=', 'vm.vehicle_type_id')
            ->leftjoin('vehicle_subtype as vst', 'vst.id', '=', 'vm.vehicle_subtype_id')
            ->leftjoin('vehicle_make as vma', 'vma.id', '=', 'vm.vehicle_make_id')
            ->select('v.mfg_year', 'v.vehicle_color', DB::raw("(CASE WHEN vm.image='' THEN '' WHEN vm.image IS NULL THEN ''  ELSE concat('$vehicle_image_path',vm.image) END) as vehicle_image"), 'vma.make_name', 'vm.model_name', 'vt.type_name', 'vst.subtype_name', 'vst.min_price', 'vst.per_km_price', 'vst.per_mile_price', 'vst.per_minute_price', 'vst.base_price', 'v.vehicle_number', 'd.full_name as driver_name', 'd.email', 'd.phone')
            ->where("d.id", $driver_id)
            ->first();
    }
}
