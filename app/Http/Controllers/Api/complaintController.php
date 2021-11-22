<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use App\Model\Api\commonModel;
use App\Model\Api\complaintModel;


class complaintController extends Controller
{
    public function complaint(Request $request)
    {
        if ($request->has('rider_id')) {
            $data['rider_id'] = $request->rider_id;
        } else {
            $data['driver_id'] = $request->driver_id;
        }
        $data['message'] = $request->message;
        $data['subject'] = $request->subject;
        $data['created_on'] = date("Y-m-d H:i:s");
        complaintModel::create($data);
        $res['status'] = 1;
        $res['message'] = 'Success';
        return response($res);
    }

    public function complaint_reply(Request $request)
    {
        $data['complaint_id'] = $request->complaint_id;
        $data['reply_by'] = $request->reply_by;
        $data['message'] = $request->message;
        $data['created_on'] = date("Y-m-d H:i:s");
        DB::table('complaint_reply')->insertGetId($data);
        $res['status'] = 1;
        $res['message'] = 'Success';
        return response($res);
    }

    public function complaint_list(Request $request)
    {
        if ($request->has('rider_id')) {
            $id = $request->rider_id;
            $table_column = 'rider_id';
        } else {
            $id = $request->driver_id;
            $table_column = 'driver_id';
        }
        $rider_id = $request->rider_id;
        $data = DB::table('complaint')->where($table_column, $id)->get();
        if ($data) {
            $res['status'] = 1;
            $res['data'] = $data;
            $res['message'] = 'Success';
        } else {
            $res['status'] = 0;
            $res['message'] = 'No record found';
        }
        return response($res);
    }

    public function complaint_details(Request $request)
    {
        $complaint_id = $request->complaint_id;
        $data['main'] = DB::table('complaint')->where('id', $complaint_id)->first();
        $data['thread'] = DB::table('complaint_reply')->where('complaint_id', $complaint_id)->get();
        $data['admin_photo'] = asset('resources/assets/images/dummy-user.png');
        $res['status'] = 1;
        $res['data'] = $data;
        $res['message'] = 'Success';
        return response($res);
    }
}
