<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class promo_code extends Model {

    protected $table = 'promo_code';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function get_active_promo_code() {
        $promo_code = self::where('is_activated', 1)
               ->orderBy('promo_name', 'asc')->get();        
        return $promo_code;
    }
}
