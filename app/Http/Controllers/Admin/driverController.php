<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\driver;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\commonController;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;
use Illuminate\Support\Facades\Hash;


use App\Model\Admin\vehicletype;
use App\Model\Admin\vehicle_make;
use App\Model\Admin\vehicle_subtype;

class driverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        //$driver = DB::table('driver')->orderBy('id','desc')->get();

        $drivers = driver::get_driver_list();
        return view('admin.driver.index', ['data' => $drivers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //         $vehicle_types = vehicletype::get_active_vehicle_type();
        //         
        //         $vehicle_makes = vehicle_make::get_active_vehicle_make();
        $dial_code = (old('dial_code')) ? old('dial_code') : '+1';
        $phone = (old('phone')) ? old('phone') : '';
        $country_list = commonController::getcountry($request);
        if (old('languages')) {
            $languages = old('languages');
        } else {
            $languages = array();
        }

        if (old('_token') !== null) {
            $is_activated = (old('is_activated')) ? 1 : 0;
        } else {

            $is_activated = 1;
        }
        if (old('country_id')) {
            $region_list =  commonController::getregions($request, old('country_id'));
        } else {
            $region_list =  array();
        }

        if (old('region_id')) {
            $city_list =  commonController::getcities($request, old('region_id'));
        } else {
            $city_list = array();
        }
        return view('admin.driver.add', compact('dial_code', 'phone', 'is_activated', 'country_list', 'region_list', 'city_list', 'languages'));
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

        $dob = $request->dob;

        $dial_code = $request->dial_code;

        $phone =  $request->phone;

        $country_id = $request->country_id;
        $region_id = $request->region_id;
        $city_id = $request->city_id;
        $postal_code = $request->postal_code;

        $languages = implode(',', $request->languages);

        $address =  $request->address;

        $password =  Hash::make($request->password);

        $driver_code = $request->driver_code;

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if (isset($request->offline_drive) && $request->offline_drive != '') {
            $offline_drive = '1';
        } else {
            $offline_drive = '0';
        }

        if (isset($request->is_online) && $request->is_online != '') {
            $is_online = 1;
        } else {
            $is_online = 0;
        }

        if ($driver_code) {
            $where_array = [
                'driver_code' => $driver_code,
            ];
            $driver_code_exist  = commonModel::check_value_exist('driver', $where_array, '', '');

            if ($driver_code_exist) {

                $errors[] = "Driver Code Already Exist";
            }
        }

        if ($email) {
            $where_array = [
                'email' => $email,
            ];
            $email_exist  = commonModel::check_value_exist('driver', $where_array, '', '');

            if ($email_exist) {

                $errors[] = "Email Already Exist";
            }
        }

        if ($dial_code && $phone) {
            $where_array = [
                'dial_code' => $dial_code,
                'phone' => $phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, '', '');

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
                $destinationPath = 'uploads/driver';

                $file->move($destinationPath, $file_name);
            }
        }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/driver/create')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'full_name'   => $full_name,
                'email' => $email,
                'dial_code' => $dial_code,
                'phone' => $phone,
                'dob'   => $dob,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'city_id' => $city_id,
                'postal_code' => $postal_code,
                'address' => $address,
                'languages' => $languages,
                'password' => $password,
                'is_activated' => $is_activated,
                'is_online'  => $is_online,
                'offline_drive' => $offline_drive,
                'driver_code'   => $driver_code,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }

            $id = DB::table('driver')->insertGetId($fieldvalues);

            return redirect('admin/driver/edit_vehicle_info/' . $id)->with('success', 'Driver info Add successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function show(driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, driver $driver)
    {
        //

        $country_list = commonController::getcountry($request);

        if ($driver->languages) {
            $languages = explode(',', $driver->languages);
        } else {
            $languages = array();
        }

        if ($driver->country_id != 0) {
            $region_list =  commonController::getregions($request, $driver->country_id);
        } else {
            $region_list =  array();
        }

        if ($driver->region_id != 0) {
            $city_list =  commonController::getcities($request, $driver->region_id);
        } else {
            $city_list = array();
        }
        return view('admin.driver.edit', compact('driver', 'languages', 'country_list', 'region_list', 'city_list'));
    }

    public function edit_vehicle_info(Request $request, $id)
    {
        //

        $driver = driver::find($id);
        $data['driver'] = $driver;
        if ($driver->default_vehicle_id != 0) {
            $vehicle_info = driver::get_vehicle_info($driver->default_vehicle_id);

            $data['vehicle_info'] = $vehicle_info;
        }

        $vehicle_types = vehicletype::get_active_vehicle_type();
        $data['vehicle_types'] = $vehicle_types;
        $data['selected_vehicle_subtypes'] = array();
        $data['selected_vehicle_makes'] = array();
        $data['selected_vehicle_models'] = array();
        if (isset($vehicle_info->vehicle_type_id)) {

            $selected_vehicle_subtypes = commonController::get_selected_vehicle_subtypes($request, $vehicle_info->vehicle_type_id);

            $data['selected_vehicle_subtypes'] = $selected_vehicle_subtypes;
        }
        if (isset($vehicle_info->vehicle_subtype_id) && isset($vehicle_info->vehicle_type_id)) {
            $selected_vehicle_makes = commonController::get_selected_vehicle_makes($request, $vehicle_info->vehicle_type_id, $vehicle_info->vehicle_subtype_id);

            $data['selected_vehicle_makes'] = $selected_vehicle_makes;
        }
        if (isset($vehicle_info->vehicle_make_id) && isset($vehicle_info->vehicle_subtype_id) && isset($vehicle_info->vehicle_type_id)) {
            $selected_vehicle_models = commonController::get_selected_vehicle_models($request, $vehicle_info->vehicle_type_id, $vehicle_info->vehicle_subtype_id, $vehicle_info->vehicle_make_id);

            $data['selected_vehicle_models'] = $selected_vehicle_models;
        }

        $vehicle_colors = commonModel::get_vehicle_colors_list();

        $data['vehicle_colors'] = $vehicle_colors;




        return view('admin.driver.edit_vehicle_info', $data);
        //return view('admin.driver.edit', compact('rider'));
    }
    public function edit_documents(Request $request, $id)
    {
        $driver = driver::find($id);
        $data['driver'] = $driver;
        if ($driver->default_vehicle_id != 0) {
            $vehicle_info = driver::get_vehicle_info($driver->default_vehicle_id);

            $data['vehicle_info'] = $vehicle_info;
        }

        return view('admin.driver.edit_documents', $data);
    }

    public function update_vehicle_info(Request $request, $id)
    {
        //
        $driver_id =  $id;

        //        $input = $request->all();
        //        print_r($input);
        //        die;

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
        $vehicle_id =  $request->vehicle_id;

        //        $vehicle_type_id  =  $request->vehicle_type_id;
        //            
        //        $vehicle_subtype_id  =  $request->vehicle_subtype_id;
        //        
        //        $vehicle_make_id =  $request->vehicle_make_id;

        $vehicle_model_id =  $request->vehicle_model_id;

        $mfg_year =  $request->mfg_year;

        $vehicle_color =  $request->vehicle_color;

        $vehicle_number =  $request->vehicle_number;


        if ($vehicle_number) {
            $where_array = [
                'vehicle_number' => $vehicle_number,
            ];

            if ($vehicle_id) {
                $vehicle_number_exist  = commonModel::check_value_exist('vehicle', $where_array, 'id', $vehicle_id);
            } else {
                $vehicle_number_exist  = commonModel::check_value_exist('vehicle', $where_array, '', '');
            }
            if ($vehicle_number_exist) {

                $errors[] = "Vehicle Number Already exist";
            }
        }

        $file = $request->file('image');

        if ($file) {
            $file_name = $file->getClientOriginalName();

            if ($file_name) {
                $file_name = time() . $file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/driver/docs/' . $driver_id;

                create_folder_if_not_exist($destinationPath);

                $file->move($destinationPath, $file_name);
            }
        }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/driver/edit_vehicle_info/' . $driver_id)->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'vehicle_model_id' => $vehicle_model_id,
                'driver_id' => $driver_id,
                'mfg_year' => $mfg_year,
                'vehicle_color' => $vehicle_color,
                'vehicle_number' => $vehicle_number,
            ];


            if (isset($file_name)) {
                $fieldvalues['vehicle_image'] = $file_name;
            }

            if ($vehicle_id != 0) {
                $update = DB::table('vehicle')->where('id', $vehicle_id)->update($fieldvalues);
            } else {

                $insert_id = DB::table('vehicle')->insertGetId($fieldvalues);

                $fieldvalues_driver = [
                    'default_vehicle_id' => $insert_id,
                ];

                $update = DB::table('driver')->where('id', $driver_id)->update($fieldvalues_driver);
            }

            return redirect('admin/driver/edit_vehicle_info/' . $driver_id)->with('success', 'Update successfully');
        }
    }

    public function update_documents(Request $request, $id)
    {
        //
        $driver_id = $id;



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
        $vehicle_id = $request->vehicle_id;

        $dl_number =  $request->dl_number;

        $dl_expiry_date = $request->dl_expiry_date;


        if (isset($request->is_approved) && $request->is_approved != '') {
            $is_approved = 1;
        } else {
            $is_approved = 0;
        }

        $dl_image = $request->file('dl_image');

        if ($dl_image) {
            $dl_image_name = $dl_image->getClientOriginalName();

            if ($dl_image_name) {
                $dl_image_name = time() . $dl_image_name;
                //Move Uploaded File
                $destinationPath = 'uploads/driver/docs/' . $driver_id;

                $dl_image->move($destinationPath, $dl_image_name);
            }
        }

        $reg_image = $request->file('reg_image');

        if ($reg_image) {
            $reg_image_name = $reg_image->getClientOriginalName();

            if ($reg_image_name) {
                $reg_image_name = time() . $reg_image_name;
                //Move Uploaded File
                $destinationPath = 'uploads/driver/docs/' . $driver_id;

                $reg_image->move($destinationPath, $reg_image_name);
            }
        }

        $ins_image = $request->file('ins_image');

        if ($ins_image) {
            $ins_image_name = $ins_image->getClientOriginalName();

            if ($ins_image_name) {
                $ins_image_name = time() . $ins_image_name;
                //Move Uploaded File
                $destinationPath = 'uploads/driver/docs/' . $driver_id;

                $ins_image->move($destinationPath, $ins_image_name);
            }
        }


        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/driver/edit_documents/' . $driver_id)->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'dl_number' => $dl_number,
                'dl_expiry_date' => $dl_expiry_date,
                'is_approved'   => $is_approved,
            ];

            if (isset($dl_image_name)) {
                $fieldvalues['dl_image'] = $dl_image_name;
            }

            $update = DB::table('driver')->where('id', $driver_id)->update($fieldvalues);

            if (isset($reg_image_name)) {
                $fieldvalues_vehicle['reg_image'] = $reg_image_name;
            }
            if (isset($ins_image_name)) {
                $fieldvalues_vehicle['ins_image'] = $ins_image_name;
            }

            if (!empty($fieldvalues_vehicle)) {
                if ($vehicle_id != 0) {
                    $update = DB::table('vehicle')->where('id', $vehicle_id)->update($fieldvalues_vehicle);
                } else {

                    $insert_id = DB::table('vehicle')->insertGetId($fieldvalues_vehicle);

                    $fieldvalues_driver = [
                        'default_vehicle_id' => $insert_id,
                    ];

                    $update = DB::table('driver')->where('id', $driver_id)->update($fieldvalues_driver);
                }
            }


            return redirect('admin/driver/edit_documents/' . $driver_id)->with('success', 'Update successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, driver $driver)
    {
        //
        $driver = driver::find($driver->id);

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

        $full_name =  $request->full_name;

        $email = $request->email;

        $dob = $request->dob;

        $dial_code = $request->dial_code;

        $phone =  $request->phone;

        $country_id = $request->country_id;
        $region_id = $request->region_id;
        $city_id = $request->city_id;
        $postal_code = $request->postal_code;

        $languages = implode(',', $request->languages);

        $address =  $request->address;

        $driver_code = $request->driver_code;

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if (isset($request->is_online) && $request->is_online != '') {
            $is_online = 1;
        } else {
            $is_online = 0;
        }

        if (isset($request->offline_drive) && $request->offline_drive != '') {
            $offline_drive = '1';
        } else {
            $offline_drive = '0';
        }

        if ($driver_code) {
            $where_array = [
                'driver_code' => $driver_code,
            ];
            $driver_code_exist  = commonModel::check_value_exist('driver', $where_array, 'id', $driver->id);

            if ($driver_code_exist) {

                $errors[] = "Driver Code Already Exist";
            }
        }

        if ($email) {
            $where_array = [
                'email' => $email,
            ];
            $email_exist  = commonModel::check_value_exist('driver', $where_array, 'id', $driver->id);

            if ($email_exist) {

                $errors[] = "Email Already Exist";
            }
        }

        if ($dial_code && $phone) {
            $where_array = [
                'dial_code' => $dial_code,
                'phone' => $phone,
            ];
            $phone_exist  = commonModel::check_value_exist('driver', $where_array, 'id', $driver->id);

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
                $destinationPath = 'uploads/driver';

                $file->move($destinationPath, $file_name);
            }
        }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/driver/' . $driver->id . '/edit')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'full_name'   => $full_name,
                'email' => $email,
                'dial_code' => $dial_code,
                'phone' => $phone,
                'dob'   => $dob,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'city_id' => $city_id,
                'postal_code' => $postal_code,
                'address' => $address,
                'languages' => $languages,
                'is_activated' => $is_activated,
                'is_online'  => $is_online,
                'offline_drive'  => $offline_drive,
                'driver_code'    => $driver_code,

            ];

            if (isset($request->password) && trim($request->password) != "") {
                $fieldvalues['password'] = Hash::make($request->password);
            }
            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }

            $updated = DB::table('driver')->where('id', $driver->id)->update($fieldvalues);

            return redirect('admin/driver/' . $driver->id . '/edit')->with('success', 'Update successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function destroy(driver $driver)
    {
        //
        $driver = driver::find($driver->id);  // can also skip this line //

        $driver->delete();

        return response()->json(array('success' => 1, 'msg' => 'deleted successfully'), 200);
        // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }

    public function map_view()
    {
        return view('admin.driver.map_view');
    }

    public function get_all_drivers(Request $request)
    {
        $driver_results = DB::table('driver')->where([
            ['is_activated', '=', 1],
            ['is_online', '=', 1]
        ])->get();
        $positions = array();
        $info = array();
        foreach ($driver_results as $row) {
            $row = (array)$row;
            if ($row['lat'] == 0 || $row['lon'] == 0) {
                continue;
            }

            $positions[] = array($row['full_name'], $row['lat'], $row['lon'], (string)$row['id']);
            //$info[] = array('<div class="info_content"><p><b>Client Name : '.$row['fname'].' '.$row['lname'].'</b></p><p><b>Mobile : '.$row['mobile_code'].$row['mobile'].'</b></p><p><b>Status : '.ucfirst($row['panic_status']).'</b></p><a href="'.SITE_URL.'panicalerts/edit?panic_id='.$row[panic_id].'" class="btn btn-primary xsmall radius blue">Action</a></div>');
            $info[] =  $row;
        }

        $driver_data['current_positions'] = $positions;
        $driver_data['positions_info'] = $info;

        return json_encode($driver_data);
    }
}
