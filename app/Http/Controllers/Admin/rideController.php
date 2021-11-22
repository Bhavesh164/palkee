<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\ride;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

class rideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $page = 1;
        $limit = 10;
        $start = 0;

        if (isset($request->limit)) {
            $limit = $request->limit;
        }
        if (isset($request->page)) {
            $page =  $request->page;
        }

        $start = ($page - 1) * $limit;

        $where_condition[] = 1;

        $startdate = '';
        if (isset($request->startdate) && $request->startdate != '') {

            $startdate  = date('Y-m-d', strtotime($request->startdate));

            $where_condition[]  =   "DATE(ride.created_at)>='$startdate'";
        }
        $enddate = '';
        if (isset($request->enddate) && $request->enddate != '') {

            $enddate    = date('Y-m-d', strtotime($request->enddate));

            $where_condition[]  =   "DATE(ride.created_at)<='$enddate'";
        }

        $id = '';
        $type = '';
        $driver_name = '';
        $rider_name =  '';
        if (isset($request->id) && isset($request->type)) {

            $id = $request->id;
            $type = $request->type;
            if ($request->type == 'driver') {
                $where_condition[]  =   "ride.driver_id = '$id'";
                $driver_name = commonModel::get_driver_name($id);
            } elseif ($request->type == 'rider') {
                $where_condition[]  =   "ride.rider_id = '$id'";
                $rider_name = commonModel::get_rider_name($id);
            }
        }

        if (!empty($where_condition)) {
            $where = implode(' and ', $where_condition);
        }

        $searchcolumn  =  $request->searchcolumn;

        $searchkeyword = $request->searchkeyword;

        if ($searchkeyword != '' &&   $searchcolumn != '') {
            $having_condition = "concat_ws(' ',$searchcolumn) like '%$searchkeyword%'";
        } else {

            if ($searchkeyword != '') {
                $search_having_columns = ('ride_number,driver_name,rider_name');

                $having_condition = "concat_ws(' ',$search_having_columns) like '%$searchkeyword%'";
            }
        }
        if (!isset($having_condition)) {
            $having_condition = 1;
        }

        if (isset($request->sort) && isset($request->sortorder)) {
            $sort = $request->sort;
            $sortorder = $request->sortorder;
        } else {
            $sort = 'ride.id';
            $sortorder = 'desc';
        }


        $rides = DB::table('ride')
            ->leftjoin('rider', 'ride.rider_id', '=', 'rider.id')
            ->leftjoin('driver', 'ride.driver_id', '=', 'driver.id')
            ->leftjoin('ride_types', 'ride.ride_type_id', '=', 'ride_types.id')
            ->leftjoin('ride_vehicle', 'ride.id', '=', 'ride_vehicle.ride_id')
            ->leftjoin('ride_status', 'ride.ride_status_id', '=', 'ride_status.id')
            ->select('ride.*', 'ride_types.name as ride_type_name', 'rider.full_name as rider_name', 'driver.full_name as driver_name', 'ride_vehicle.distance_unit', 'ride_status.name as ride_status_name')
            ->whereRaw("$where")
            ->havingRaw("$having_condition")
            ->orderBy($sort, $sortorder)
            ->Paginate($limit);

        $data = [
            'data' => $rides,
            'start' => $start,
            'page' => $page,
            'limit' => $limit,
            'id' => $id,
            'type' => $type,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'searchcolumn' => $searchcolumn,
            'searchkeyword' => $searchkeyword,
            'sort' => $sort,
            'sortorder' => $sortorder,
            'driver_name' => $driver_name,
            'rider_name' => $rider_name,
        ];
        return view('admin.ride.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //         $vehicle_types = vehicletype::get_active_vehicle_type();
        //         
        //         $vehicle_makes = vehicle_make::get_active_vehicle_make();
        //         $dial_code = '+1';
        //         $phone = '';
        $dial_code = (old('dial_code')) ? old('dial_code') : '+1';
        $phone = (old('phone')) ? old('phone') : '';


        //$is_activated = 1;
        if (old('_token') !== null) {
            $is_activated = (old('is_activated')) ? 1 : 0;
        } else {

            $is_activated = 1;
        }
        //$is_activated = 1;

        return view('admin.ride.add', compact('dial_code', 'phone', 'is_activated'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $errors = array();
        $rules = [
            //'model_name' => 'required',
        ];
        $messages = [
            // 'required' => 'The :attribute is required.',
            //            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            // 'model_name' => 'Model Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        //        $validator = Validator::make($request->all(), [
        ////            'subtype_name' => 'required',
        //        ]);

        $full_name =  $request->full_name;

        $email = $request->email;

        $rides_code = $request->rides_code;

        $dial_code = $request->dial_code;

        $phone =  $request->phone;

        $address =  $request->address;

        $password =  md5($request->password);

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if ($email) {
            $where_array = [
                'email' => $email,
            ];
            $email_exist  = commonModel::check_value_exist('rides', $where_array, '', '');

            if ($email_exist) {

                $errors[] = "Email Already Exist";
            }
        }

        if ($rides_code) {
            $where_array = [
                'rides_code' => $rides_code,
            ];
            $rides_code_exist  = commonModel::check_value_exist('rides', $where_array, '', '');

            if ($rides_code_exist) {

                $errors[] = "Rider Code Already Exist";
            }
        }

        if ($dial_code && $phone) {
            $where_array = [
                'dial_code' => $dial_code,
                'phone' => $phone,
            ];
            $phone_exist  = commonModel::check_value_exist('rides', $where_array, '', '');

            if ($phone_exist) {

                $errors[] = "Phone No. Already Exist";
            }
        }

        $file = $request->file('image');

        if ($file) {
            $file_name = $file->getClientOriginalName();

            if ($file_name) {
                $file_name = time() . $file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/rides';

                $file->move($destinationPath, $file_name);
            }
        }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/rides/create')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'full_name'   => $full_name,
                'email' => $email,
                'dial_code' => $dial_code,
                'phone' => $phone,
                'address' => $address,
                'password' => $password,
                'is_activated' => $is_activated,
                'created_at' => date('Y-m-d H:i:s'),
                'rides_code'    => $rides_code,
            ];

            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }

            $insert = DB::table('rides')->insert($fieldvalues);

            return redirect('admin/rides/create')->with('success', 'Add successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\rides  $rides
     * @return \Illuminate\Http\Response
     */
    public function show(ride $ride)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\rides  $rides
     * @return \Illuminate\Http\Response
     */
    public function edit(ride $ride)
    {
        //
        //print_r($ride);

        $ride_detail = ride::get_ride_detail($ride->id);
        $all_status = DB::table('ride_status')->get();

        return view('admin.ride.edit', compact('ride_detail', 'all_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\rides  $rides
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ride $ride)
    {
        //
        $ride = ride::find($ride->id);

        $errors = array();
        $rules = [
            // 'model_name' => 'required',
        ];
        $messages = [
            // 'required' => 'The :attribute is required.',
            //            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            // 'model_name' => 'Model Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        //        $validator = Validator::make($request->all(), [
        ////            'subtype_name' => 'required',
        //        ]);

        $status =  $request->status;


        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/ride/' . $ride->id . '/edit')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'ride_status_id'   => $status,
            ];

            if (in_array($status, [7, 8])) {
                DB::table('driver')->where('id', $ride->driver_id)->update(["on_ride" => "0"]);
            }
            $updated = DB::table('ride')->where('id', $ride->id)->update($fieldvalues);

            return redirect('admin/ride/' . $ride->id . '/edit')->with('success', 'Update successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\rides  $rides
     * @return \Illuminate\Http\Response
     */
    public function destroy(ride $ride)
    {
        //
        $ride = ride::find($ride->id);  // can also skip this line //

        $ride->delete();

        //return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
        return redirect('admin/ride/')->with('success', 'deleted successfully');
    }
}
