<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Model\Api\promocode;
use Illuminate\Auth\EloquentUserProvider;

class PromocodeService
{
    /**
     * @return boolean | object
     */
    public function promocode(Request $request)
    {
        $promo_code = $request->promo_code;
        $res = $this->check_promo_code_eligibility($request);
        if ($res) {
            $details = promocode::where('promo_code', '=', $promo_code)->first();
            return $details;
        } else {
            return false;
        }
    }

    public function check_promo_code_eligibility(Request $request)
    {
        $promo_code = $request->promo_code;
        $result = (new promocode)->check_promo_code_eligibility($promo_code);
        if ($result) {
            return false;
        } else {
            return true;
        }
    }
}
