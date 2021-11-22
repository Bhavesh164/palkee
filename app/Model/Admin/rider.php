<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class rider extends Model
{
    //
    protected $table = 'rider';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function get_rider_list()
   {
       
        $rider_list = DB::table('rider')
            ->leftJoin('ride_rating', function($join)
            {
                             $join->on('ride_rating.rider_id','=','rider.id');
                             $join->on('ride_rating.rating_to','=',DB::raw("'rider'"));
            })
            ->select('rider.*',DB::raw('round(AVG(ride_rating.rating),1) as avg_rating'))
            ->groupBy('rider.id')
            ->orderBy('rider.id','desc')
            //->toSql();
            ->get();
        // $data = self::all();
        
                            
        return $rider_list;
   }
    
}
