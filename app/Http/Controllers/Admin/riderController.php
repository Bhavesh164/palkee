<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\rider;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;
use Illuminate\Support\Facades\Hash;


use App\Model\Admin\vehicletype;
use App\Model\Admin\vehicle_make;
use App\Model\Admin\vehicle_subtype;

class riderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        //$rider = DB::table('rider')->orderBy('id','desc')->get();
        
        $rider = rider::get_rider_list();
        
        return view('admin.rider.index', ['data' => $rider]);
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
         $dial_code = (old('dial_code'))?old('dial_code'):'+1';
         $phone = (old('phone'))?old('phone'):'';
         
         
         //$is_activated = 1;
         if(old('_token') !== null)
         {
             $is_activated = (old('is_activated'))?1:0;
         } else {
             
            $is_activated = 1; 
         }
         //$is_activated = 1;
         
         return view('admin.rider.add',compact('dial_code','phone','is_activated'));
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
        
        $rider_code = $request->rider_code;
        
        $dial_code = $request->dial_code;
        
        $phone =  $request->phone;
        
        $address =  $request->address;
        
        $password =  Hash::make($request->password);
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($email)
        {
            $where_array = [
                    'email'=> $email,
                ];
            $email_exist  = commonModel::check_value_exist('rider',$where_array,'','');

            if ($email_exist) {
               
             $errors[] ="Email Already Exist";
            }
        }
        
        if($rider_code)
        {
            $where_array = [
                    'rider_code'=> $rider_code,
                ];
            $rider_code_exist  = commonModel::check_value_exist('rider',$where_array,'','');

            if ($rider_code_exist) {
               
             $errors[] ="Rider Code Already Exist";
            }
        }
        
        if($dial_code && $phone)
        {
            $where_array = [
                    'dial_code'=> $dial_code,
                    'phone'=> $phone,
                ];
            $phone_exist  = commonModel::check_value_exist('rider',$where_array,'','');

            if ($phone_exist) {
               
             $errors[] ="Phone No. Already Exist";
            }
        }
        
        $file = $request->file('image'); 
          
        if($file)
        {
            $file_name = $file->getClientOriginalName();

            if($file_name)
            {
                $file_name = time().$file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/rider';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/rider/create')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'full_name'   => $full_name,
                       'email' => $email,
                       'dial_code' => $dial_code,
                       'phone' => $phone,
                       'address' => $address,
                       'password' => $password,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
                       'rider_code'    => $rider_code,
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

            $insert = DB::table('rider')->insert($fieldvalues);

            return redirect('admin/rider/create')->with('success', 'Add successfully');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function show(rider $rider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function edit(rider $rider)
    {
        //
       
        return view('admin.rider.edit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rider $rider)
    {
        //
        $rider = rider::find($rider->id);
         
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
        
        $dial_code = $request->dial_code;
        
        $phone =  $request->phone;
        
        $address =  $request->address;
        
        $rider_code = $request->rider_code;
        
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($email)
        {
            $where_array = [
                    'email'=> $email,
                ];
            $email_exist  = commonModel::check_value_exist('rider',$where_array,'id',$rider->id);

            if ($email_exist) {
               
             $errors[] ="Email Already Exist";
            }
        }
        
        if($rider_code)
        {
            $where_array = [
                    'rider_code'=> $rider_code,
                ];
            $rider_code_exist  = commonModel::check_value_exist('rider',$where_array,'id',$rider->id);

            if ($rider_code_exist) {
               
             $errors[] ="Rider Code Already Exist";
            }
        }
        
        if($dial_code && $phone)
        {
            $where_array = [
                    'dial_code'=> $dial_code,
                    'phone'=> $phone,
                ];
            $phone_exist  = commonModel::check_value_exist('rider',$where_array,'id',$rider->id);

            if ($phone_exist) {
               
             $errors[] ="Phone No. Already Exist";
            }
        }

       
        $file = $request->file('image'); 
          
        if($file)
        {
            $file_name = $file->getClientOriginalName();

            if($file_name)
            {
                $file_name = time().$file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/rider';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/rider/'.$rider->id.'/edit')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'full_name'   => $full_name,
                       'email' => $email,
                       'rider_code' => $rider_code,
                       'dial_code' => $dial_code,
                       'phone' => $phone,
                       'address' => $address,
                       'is_activated' => $is_activated,
            ];
            
            if(isset($request->password))
            {
                $fieldvalues['password'] = Hash::make($request->password);
            }
            if(isset($file_name))
            {
                $fieldvalues['image'] = $file_name;
            }

           $updated = DB::table('rider')->where('id', $rider->id)->update($fieldvalues);

           return redirect('admin/rider/'.$rider->id.'/edit')->with('success', 'Update successfully');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function destroy(rider $rider)
    {
        //
        $rider = rider::find($rider->id);  // can also skip this line //
        
        $rider->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
       // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
