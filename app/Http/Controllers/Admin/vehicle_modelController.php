<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\vehicle_model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

use App\Model\Admin\vehicletype;
use App\Model\Admin\vehicle_make;
use App\Model\Admin\vehicle_subtype;

class vehicle_modelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $vehicle_model = vehicle_model::all_records();
       
         return view('admin.vehicle_model.index', ['data' => $vehicle_model]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $vehicle_types = vehicletype::get_active_vehicle_type();
         
         $vehicle_makes = vehicle_make::get_active_vehicle_make();
         
         return view('admin.vehicle_model.add',['vehicle_types'=>$vehicle_types,'vehicle_makes'=>$vehicle_makes]);
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
           'model_name' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'model_name' => 'Model Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $vehicle_type_id =  $request->vehicle_type_id;
        
        $vehicle_subtype_id = $request->vehicle_subtype_id;
        
        $vehicle_make_id =  $request->vehicle_make_id;
        
        $model_name =  $request->model_name;
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($model_name)
        {
            $where_array = [
                    'vehicle_type_id'=> $vehicle_type_id,
                    'vehicle_subtype_id' => $vehicle_subtype_id,
                    'vehicle_make_id'  => $vehicle_make_id,
                    'model_name'=>$model_name,
                ];
            $type_exist  = commonModel::check_value_exist('vehicle_model',$where_array,'','');

            if ($type_exist) {
               
             $errors[] ="Model Name is already exist with Same vehicle Type & Make";
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
                $destinationPath = 'uploads/vehicle_model';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_model/create')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'vehicle_type_id'   => $vehicle_type_id,
                       'vehicle_subtype_id' => $vehicle_subtype_id,
                       'vehicle_make_id' => $vehicle_make_id,
                       'model_name' => $model_name,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

            $insert = DB::table('vehicle_model')->insert($fieldvalues);

            return redirect('admin/vehicle_model/create')->with('success', 'Add successfully');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_model $vehicle_model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicle_model $vehicle_model)
    {
        //
       
        $vehicle_types = vehicletype::get_active_vehicle_type();
         
        $vehicle_subtypes = vehicle_subtype::get_selected_vehicle_subtype($vehicle_model->vehicle_type_id);
        
        $vehicle_makes = vehicle_make::get_active_vehicle_make();
        
        return view('admin.vehicle_model.edit', compact('vehicle_model','vehicle_types','vehicle_subtypes','vehicle_makes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vehicle_model $vehicle_model)
    {
        //
        $vehicle_model = vehicle_model::find($vehicle_model->id);
         
        $errors = array();
        $rules = [
           'model_name' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'model_name' => 'Model Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $vehicle_type_id =  $request->vehicle_type_id;
        
        $vehicle_subtype_id = $request->vehicle_subtype_id;
        
        $vehicle_make_id =  $request->vehicle_make_id;
        
        $model_name =  $request->model_name;
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($model_name)
        {
             $where_array = [
                    'vehicle_type_id'=> $vehicle_type_id,
                    'vehicle_subtype_id' => $vehicle_subtype_id,
                    'vehicle_make_id'  => $vehicle_make_id,
                    'model_name'=>$model_name,
             ];
            $type_exist  = commonModel::check_value_exist('vehicle_model',$where_array,'id',$vehicle_model->id);

            if ($type_exist) {
               
                $errors[] ="Model Name is already exist with Same vehicle Type & Make";
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
                $destinationPath = 'uploads/vehicle_model';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_model/'.$vehicle_model->id.'/edit')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'vehicle_type_id'   => $vehicle_type_id,
                       'vehicle_subtype_id' => $vehicle_subtype_id,
                       'vehicle_make_id' => $vehicle_make_id,
                       'model_name' => $model_name,
                       'is_activated' => $is_activated,
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

           $updated = DB::table('vehicle_model')->where('id', $vehicle_model->id)->update($fieldvalues);

           return redirect('admin/vehicle_model/'.$vehicle_model->id.'/edit')->with('success', 'Update successfully');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicle_model $vehicle_model)
    {
        //
        $vehicle_model = vehicle_model::find($vehicle_model->id);  // can also skip this line //
        
        $vehicle_model->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
       // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
