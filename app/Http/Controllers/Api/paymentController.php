<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Api\ride;
use App\Model\Api\rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Api\driverModel;
use App\Model\Api\commonModel;
use App\Support\firebase;
use Google\Cloud\Core\Timestamp;

//use App\Model\Admin\vehicletype;
//use App\Model\Admin\vehicle_make;
//use App\Model\Admin\vehicle_subtype;

class paymentController extends Controller
{
    public function payment(Request $request)
    {
        if ($request->has('rider_id')) {
            $ride_id = $request->ride_id;
            $payment_type = $request->payment_type;
            $data["is_payment"] = 1;
            $data["payment_type"] = $payment_type;
            DB::table('ride')->where('id', $ride_id)->update($data);
            $res['status'] = 1;
            $res['message'] = 'Payment done successfully';
            return response($res);
        }
    }

    public function confirm_cash_payment(Request $request)
    {
        if ($request->has('driver_id')) {
            $ride_id = $request->ride_id;
            $data["cash_payment_confirmed_by_driver"] = 1;
            DB::table('ride')->where('id', $ride_id)->update($data);
            $res['status'] = 1;
            $res['message'] = 'Payment cofirmed successfully';
            return response($res);
        }
    }
}
