<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\promo_code;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

class promo_codeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promo_code = DB::table('promo_code')->orderBy('id', 'desc')->get();
        //$vehicle_type = vehicletype::all()->sortByDesc("id");
        return view('admin.promo_code.index', ['data' => $promo_code]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promo_code.add');
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
            'promo_name' => 'required',
            'promo_code' => 'required',
            'promo_type' => 'required',
            'promo_rate' => 'required',
            'expiry_date' => 'required',
            'minimum_amount' => 'required',
            'maximum_discount' => 'required',
        ];

        $messages = [
            'required' => 'The :attribute is required.',
            //            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'promo_name' => 'Promo Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        //        $validator = Validator::make($request->all(), [
        ////            'subtype_name' => 'required',
        //        ]);

        $promo_name =  $request->promo_name;
        $promo_code =  $request->promo_code;
        $promo_type =  $request->promo_type;
        $promo_rate =  $request->promo_rate;
        $redeem_per_user = $request->redeem_per_user;
        $expiry_date =  $request->expiry_date;

        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if ($promo_name) {
            $where_array = [
                'promo_name' => $promo_name,
            ];
            $type_exist  = commonModel::check_value_exist('promo_code', $where_array, '', '');

            if ($type_exist) {

                $errors[] = "Promo is already exist";
            }
        }

        //$file = $request->file('image'); 

        // if($file) {
        //     $file_name = $file->getClientOriginalName();

        //     if($file_name) {
        //         $file_name = time().$file_name;
        //         //Move Uploaded File
        //         $destinationPath = 'uploads/promo_code';

        //         $file->move($destinationPath,$file_name);
        //     }
        // }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/promo_code/create')->withErrors($validator)->withInput();
        } else {
            $promo_rate = preg_replace("/[^0-9]./", "", $promo_rate);
            $redeem_per_user = (int) $redeem_per_user;
            $fieldvalues = [
                'promo_name' => $promo_name,
                'promo_code' => $promo_code,
                'promo_type' => $promo_type,
                'promo_rate' => $promo_rate,
                'minimum_amount' => $request->minimum_amount,
                'maximum_discount' => $request->maximum_discount,
                'redeem_per_user' => $redeem_per_user,
                'expiry_date' => $expiry_date,
                'is_activated' => $is_activated,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // if(isset($file_name)) {
            //     $fieldvalues['image'] = $file_name;
            // }

            $insert = DB::table('promo_code')->insert($fieldvalues);

            return redirect('admin/promo_code/create')->with('success', 'Promo added successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\promo_code  $promo_code
     * @return \Illuminate\Http\Response
     */
    public function show(promo_code $promo_code)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\promo_code  $promo_code
     * @return \Illuminate\Http\Response
     */
    public function edit(promo_code $promo_code)
    {
        //
        return view('admin.promo_code.edit', compact('promo_code'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\promo_code  $promo_code
     * @return \Illuminate\Http\Response
     */



    public function update(Request $request, promo_code $promo_code)
    {
        //
        $promo_code = promo_code::find($promo_code->id);

        $errors = array();

        $rules = [
            'promo_name' => 'required',
            'promo_code' => 'required',
            'promo_type' => 'required',
            'promo_rate' => 'required',
            'expiry_date' => 'required',
            'minimum_amount' => 'required',
            'maximum_discount' => 'required',
        ];

        $messages = [
            'required' => 'The :attribute is required.',
            //            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'promo_name' => 'Promo Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        //        $validator = Validator::make($request->all(), [
        ////            'subtype_name' => 'required',
        //        ]);

        $promo_name = $request->promo_name;
        $promo_unique_code = $request->promo_code;
        $promo_type = $request->promo_type;
        $promo_rate = $request->promo_rate;
        $redeem_per_user = $request->redeem_per_user;
        $expiry_date = $request->expiry_date;


        if (isset($request->is_activated) && $request->is_activated != '') {
            $is_activated = 1;
        } else {
            $is_activated = 0;
        }

        if ($promo_name) {
            $where_array = [
                'promo_name' => $promo_name,
            ];
            $type_exist  = commonModel::check_value_exist('promo_code', $where_array, 'id', $promo_code->id);

            if ($type_exist) {

                $errors[] = "Promo Name is already exist";
            }
        }

        //$file = $request->file('image'); 

        // if($file) {
        //     $file_name = $file->getClientOriginalName();

        //     if($file_name) {
        //         $file_name = time().$file_name;
        //         //Move Uploaded File
        //         $destinationPath = 'uploads/promo_code';

        //         $file->move($destinationPath,$file_name);
        //     }
        // }

        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/promo_code/' . $promo_code->id . '/edit')->withErrors($validator)->withInput();
        } else {
            $promo_rate = preg_replace("/[^0-9]./", "", $promo_rate);
            $redeem_per_user = (int) $redeem_per_user;
            $fieldvalues = [
                'promo_name' => $promo_name,
                'promo_code' => $promo_unique_code,
                'promo_type' => $promo_type,
                'promo_rate' => $promo_rate,
                'minimum_amount' => $request->minimum_amount,
                'maximum_discount' => $request->maximum_discount,
                'redeem_per_user' => $redeem_per_user,
                'expiry_date' => $expiry_date,
                'is_activated' => $is_activated,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // if(isset($file_name)) {
            //     $fieldvalues['image'] = $file_name;
            // }

            $updated = DB::table('promo_code')->where('id', $promo_code->id)->update($fieldvalues);

            return redirect('admin/promo_code/' . $promo_code->id . '/edit')->with('success', 'Update successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\promo_code  $promo_code
     * @return \Illuminate\Http\Response
     */
    public function destroy(promo_code $promo_code)
    {
        //
        $promo_code = promo_code::find($promo_code->id);  // can also skip this line //

        $promo_code->delete();

        return response()->json(array('success' => 1, 'msg' => 'deleted successfully'), 200);
        // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
