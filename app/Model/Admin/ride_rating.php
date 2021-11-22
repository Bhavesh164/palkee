<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ride_rating extends Model
{
    //
    protected $table = 'ride_rating';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
   public static function get_ride_detail($id)
   {
       $ride_detail = DB::table('ride')
            ->leftjoin('rider', 'ride.rider_id','=','rider.id')
            ->leftjoin('driver', 'ride.driver_id','=','driver.id')
            ->leftjoin('ride_types', 'ride.ride_type_id','=','ride_types.id')
            ->leftjoin('ride_vehicle', 'ride.id','=','ride_vehicle.ride_id')
            ->select('ride.*','ride_types.name as ride_type_name','rider.full_name as rider_name','driver.full_name as driver_name','ride_vehicle.distance_unit','ride_vehicle.vehicle_color','ride_vehicle.vehicle_number')
            ->where('ride.id',$id)
            ->first();

      $ride_detail->distance = beautify_distance($ride_detail->distance,$ride_detail->distance_unit);
      $ride_detail->duration = beautify_time($ride_detail->duration); 
      
      return $ride_detail;
            
   }
    
}
