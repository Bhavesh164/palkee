<?php

namespace App\Model\Api;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class promocode extends Model
{
    //
    protected $table = 'promo_code';
    protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $fillable = ['full_name',''];
    protected $guarded = [];

    public function check_promo_code_eligibility($promo_code)
    {
        $now = date("Y-m-d H:i:s");
        return $this->where([
            ['promo_code', '=', $promo_code],
            ['expiry_date', '<=', $now],
            ['is_activated', '=', '1']
        ])->select('id')->first();
    }

    public function discount(object $ride_details, $ride_cost)
    {
        $discount = '0.00';
        if ($ride_details->is_promo_applied == '1') {
            $discount = $ride_details->promo_amount;
            if ($ride_details->promo_type == '2') {
                $discount = ($ride_cost * $discount) / 100;
            }
            $promo_details = $this->where('promo_code', '=', $ride_details->promo_code)->first();
            if ($ride_cost < $promo_details->minimum_amount) {
                $discount = '0.00';
            } else if ($discount > $promo_details->maximum_discount) {
                $discount = $promo_details->maximum_discount;
            }
        }
        return $discount;
    }
}
