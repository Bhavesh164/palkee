<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class vehicle_make extends Model
{
    //
    protected $table = 'vehicle_make';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function get_active_vehicle_make()
    {
        $vehicle_make = self::where('is_activated', 1)
               ->orderBy('make_name', 'asc')->get();
        
        return $vehicle_make;
    }
}
