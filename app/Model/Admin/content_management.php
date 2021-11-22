<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class content_management extends Model {

    protected $table = 'content_management';
    protected $primaryKey = 'page_name';
    public $timestamps = false;
    
    // public static function get_active_promo_code() {
    //     // $content_management = self::where('is_activated', 1)
    //     //        ->orderBy('content_id', 'asc')->get();
    //     // return $content_management;
    // }

    // public static function get_active_content_management() {
    //     $content_management = self::where('is_activated', 1)->orderBy('content_id', 'asc')->get();        
    //     return $content_management;
    // }

	public static function fetch_content($page_name) {
		//$content_management = DB::table('content_management')->where('page_name', $page_name)->get();
        $content_management = DB::table('content_management')->where('page_name', $page_name)->first();

		//$content_management = self::where('page_name', $page_name)->get();
		return $content_management;
	}

    
}
