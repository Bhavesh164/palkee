<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class vehicle_model extends Model
{
    //
    protected $table = 'vehicle_model';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function all_records(){
        
            $vehicle_model = DB::table('vehicle_model')
            ->leftjoin('vehicle_type', 'vehicle_model.vehicle_type_id','=','vehicle_type.id')
            ->leftjoin('vehicle_subtype', 'vehicle_model.vehicle_subtype_id', '=', 'vehicle_subtype.id')
            ->leftjoin('vehicle_make', 'vehicle_model.vehicle_make_id', '=', 'vehicle_make.id')
            ->select('vehicle_model.*', 'vehicle_type.type_name','vehicle_subtype.subtype_name','vehicle_make.make_name')->orderBy('vehicle_model.id','desc')
            ->get();
        // $data = self::all();
         return $vehicle_model;
        //echo "This is a test function";
    }
}
