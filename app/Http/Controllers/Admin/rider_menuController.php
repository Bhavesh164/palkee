<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\rider_menu;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

use App\Model\Admin\vehicletype;
use App\Model\Admin\vehicle_make;
use App\Model\Admin\vehicle_subtype;

class rider_menuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rider_menu_list = rider_menu::get_rider_menu_list();

        return view('admin.rider_menu.index', ['data' => $rider_menu_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (old('_token') !== null) {
            $is_activated = (old('is_activated')) ? 1 : 0;
        } else {

            $is_activated = 1;
        }

        $ride_types =  rider_menu::get_ride_types();

        $vehicle_types = vehicletype::get_active_vehicle_type();

        return view('admin.rider_menu.add', compact('vehicle_types', 'ride_types', 'is_activated'));
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

        $menu_name =  $request->menu_name;

        $priority = $request->priority;

        $ride_type_id =  $request->ride_type_id;

        $vehicle_type_ids = implode(',', $request->vehicle_type_ids);

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if ($menu_name) {
            $where_array = [
                'menu_name' => $menu_name,
            ];
            $menu_name_exist  = commonModel::check_value_exist('rider_menu', $where_array, '', '');

            if ($menu_name_exist) {

                $errors[] = "Menu Name Already Exist";
            }
        }


        $file = $request->file('image');

        if ($file) {
            $file_name = $file->getClientOriginalName();

            if ($file_name) {
                $file_name = time() . $file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/admin/rider_menu';

                $file->move($destinationPath, $file_name);
            }
        }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/rider_menu/create')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'menu_name'   => $menu_name,
                'ride_type_id'  => $ride_type_id,
                'priority' => $priority,
                'vehicle_type_ids' => $vehicle_type_ids,
                'is_activated' => $is_activated,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }

            $insert = DB::table('rider_menu')->insert($fieldvalues);

            return redirect('admin/rider_menu/create')->with('success', 'Add successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\rider_menu  $rider_menu
     * @return \Illuminate\Http\Response
     */
    public function show(rider_menu $rider_menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\rider_menu  $rider_menu
     * @return \Illuminate\Http\Response
     */
    public function edit(rider_menu $rider_menu)
    {
        //
        $ride_types =  rider_menu::get_ride_types();

        $vehicle_types = vehicletype::get_active_vehicle_type();

        return view('admin.rider_menu.edit', compact('rider_menu', 'ride_types', 'vehicle_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\rider_menu  $rider_menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rider_menu $rider_menu)
    {
        //
        $rider_menu = rider_menu::find($rider_menu->id);

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

        $menu_name =  $request->menu_name;

        $priority = $request->priority;

        $ride_type_id =  $request->ride_type_id;

        $vehicle_type_ids = implode(',', $request->vehicle_type_ids);

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = '1';
        } else {
            $is_activated = '0';
        }

        if ($menu_name) {
            $where_array = [
                'menu_name' => $menu_name,
            ];
            $menu_name_exist  = commonModel::check_value_exist('rider_menu', $where_array, 'id', $rider_menu->id);

            if ($menu_name_exist) {

                $errors[] = "Menu Name Already Exist";
            }
        }

        $file = $request->file('image');

        if ($file) {
            $file_name = $file->getClientOriginalName();

            if ($file_name) {
                $file_name = time() . $file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/admin/rider_menu';

                $file->move($destinationPath, $file_name);
            }
        }


        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/rider_menu/' . $rider_menu->id . '/edit')->withErrors($validator)->withInput();
        } else {
            $fieldvalues = [
                'menu_name'   => $menu_name,
                'ride_type_id'  => $ride_type_id,
                'priority' => $priority,
                'vehicle_type_ids' => $vehicle_type_ids,
                'is_activated' => $is_activated,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (isset($file_name)) {
                $fieldvalues['image'] = $file_name;
            }


            $updated = DB::table('rider_menu')->where('id', $rider_menu->id)->update($fieldvalues);

            return redirect('admin/rider_menu/' . $rider_menu->id . '/edit')->with('success', 'Update successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\rider_menu  $rider_menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(rider_menu $rider_menu)
    {
        //
        $rider_menu = rider_menu::find($rider_menu->id);  // can also skip this line //

        $rider_menu->delete();

        return response()->json(array('success' => 1, 'msg' => 'deleted successfully'), 200);
        // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
