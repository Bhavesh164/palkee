<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class driver extends Model
{
    //
    protected $table = 'driver';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
   public static function get_driver_list()
   {
       
        $driver_list = DB::table('driver')
            ->leftjoin('country', 'driver.country_id','=','country.countryId')
            ->leftjoin('region', 'driver.region_id','=','region.regionId')
            ->leftjoin('cities', 'driver.city_id','=','cities.cityId')
            ->leftJoin('ride_rating', function($join)
            {
                             $join->on('ride_rating.driver_id','=','driver.id');
                             $join->on('ride_rating.rating_to','=',DB::raw("'driver'"));
            })
            ->select('driver.*', 'country.name as country_name','region.name as region_name','cities.name as city_name',DB::raw('round(AVG(ride_rating.rating),1) as avg_rating'))
            ->groupBy('driver.id')
            ->orderBy('driver.id','desc')
            //->toSql();
            ->get();
        // $data = self::all();
        
                            
        return $driver_list;
   } 
   
   public static function get_vehicle_info($id)
   {
       
        $vehicle_info = DB::table('vehicle')
            ->leftjoin('vehicle_model', 'vehicle.vehicle_model_id','=','vehicle_model.id')
            ->select('vehicle.*', 'vehicle_model.vehicle_make_id','vehicle_model.vehicle_type_id','vehicle_model.vehicle_subtype_id')
            ->where('vehicle.id',$id)
            ->first();
        // $data = self::all();
        
        return $vehicle_info;
   } 
}
