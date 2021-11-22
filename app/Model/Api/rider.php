<?php

namespace App\Model\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class rider extends Model
{
    //
    protected $table = 'rider';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $fillable = ['full_name',''];
    protected $guarded = [];


    public static function get_rider_list()
    {

        $rider_list = DB::table('rider')
            ->leftJoin('ride_rating', function ($join) {
                $join->on('ride_rating.rider_id', '=', 'rider.id');
                $join->on('ride_rating.rating_to', '=', DB::raw("'rider'"));
            })
            ->select('rider.*', DB::raw('round(AVG(ride_rating.rating),1) as avg_rating'))
            ->groupBy('rider.id')
            ->orderBy('rider.id', 'desc')
            //->toSql();
            ->get();
        // $data = self::all();


        return $rider_list;
    }

    public static function get_rider_detail($id)
    {
        $image_path = url('/') . '/uploads/rider/';

        $rider_detail = DB::table('rider')->select('full_name', 'email', 'dial_code', 'phone', 'country_id', 'address')->selectRaw('IF(image != "", CONCAT("' . $image_path . '","",image), "") as image')->where([
            ['id', '=', $id],
        ])->first();

        return $rider_detail;
    }

    public function rider_rating($rider_id)
    {
        return DB::table('ride_rating')
            ->where([
                "rider_id" => $rider_id,
                "rating_to" => 'rider'
            ])
            ->avg('rating');
    }
}
