<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class rider_menu extends Model
{
    //
    protected $table = 'rider_menu';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function get_rider_menu_list()
    {
        $rider_menu_list = DB::select("SELECT rider_menu.*,ride_types.name as ride_type,GROUP_CONCAT(vehicle_type.type_name) as vehicle_types FROM `rider_menu` "
                . "left join vehicle_type on FIND_IN_SET(vehicle_type.id,rider_menu.vehicle_type_ids) "
                . "left join ride_types on ride_types.id = rider_menu.ride_type_id "
                . "GROUP BY rider_menu.id ORDER BY rider_menu.priority ASC");
        
        return $rider_menu_list;
    }
    
    public static function get_ride_types()
    {
        $ride_types = DB::table('ride_types')->get();
        
        return $ride_types;
    }
}
