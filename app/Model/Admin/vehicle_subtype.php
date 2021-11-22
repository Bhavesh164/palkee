<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class vehicle_subtype extends Model
{
    //
    protected $table = 'vehicle_subtype';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    
     public static function all_records(){
        
            $vehicle_subtype = DB::table('vehicle_subtype')
            ->leftjoin('vehicle_type', 'vehicle_subtype.type_id', '=', 'vehicle_type.id')
            ->select('vehicle_subtype.*', 'vehicle_type.type_name')->orderBy('vehicle_subtype.id','desc')
            ->get();
        // $data = self::all();
         return $vehicle_subtype;
        //echo "This is a test function";
    }
    
    public static function get_selected_vehicle_subtype($type_id)
    {
        $vehicle_type = self::where([
           ['is_activated', '=', 1],
           ['type_id', '=', $type_id],
        ])->orderBy('subtype_name', 'asc')->get();
        
        return $vehicle_type;
    }
    public static function test(){
        
        // $data = self::all();
        // return $data;
        //echo "This is a test function";
    }
    public function scopetest1(){
        
        // we can call in controller like ( vehicle_subtype::test() ) //
        
       // echo "This is a test2 function";
    }
}
