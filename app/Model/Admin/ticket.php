<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ticket extends Model
{
    //
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
   public static function get_tickets_detail($id)
   {
       $tickets_detail = DB::table('tickets')
            ->leftjoin('driver', 'tickets.driver_id','=','driver.id')
            ->leftjoin('rider', 'tickets.rider_id','=','rider.id')
            ->select('tickets.*','driver.full_name as driver_name','rider.full_name as rider_name')
            ->where('tickets.id',$id)
            ->first();
    
      return $tickets_detail;
            
   }
    
}
