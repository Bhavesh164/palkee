<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class vehicletype extends Model
{
    protected $table = 'vehicle_type';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //
    
    public static function get_active_vehicle_type()
    {
        $vehicle_type = self::where('is_activated', 1)
               ->orderBy('type_name', 'asc')->get();
        
        return $vehicle_type;
    }
}
