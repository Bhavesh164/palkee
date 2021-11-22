<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\vehicle_make;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

class vehicle_makeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vehicle_make = DB::table('vehicle_make')->orderBy('id','desc')->get();
       //$vehicle_type = vehicletype::all()->sortByDesc("id");
        return view('admin.vehicle_make.index', ['data' => $vehicle_make]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.vehicle_make.add');
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
           'make_name' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'make_name' => 'Make Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $make_name =  $request->make_name;
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($make_name)
        {
            $where_array = [
                    'make_name'=>$make_name,
                ];
            $type_exist  = commonModel::check_value_exist('vehicle_make',$where_array,'','');

            if ($type_exist) {
               
             $errors[] ="Make Name is already exist";
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
                $destinationPath = 'uploads/vehicle_make';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_make/create')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'make_name' => $make_name,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

            $insert = DB::table('vehicle_make')->insert($fieldvalues);

            return redirect('admin/vehicle_make/create')->with('success', 'Add successfully');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_make  $vehicle_make
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_make $vehicle_make)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_make  $vehicle_make
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicle_make $vehicle_make)
    {
        //
        return view('admin.vehicle_make.edit', compact('vehicle_make'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\vehicle_make  $vehicle_make
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vehicle_make $vehicle_make)
    {
        //
        $vehicle_make = vehicle_make::find($vehicle_make->id);
         
        $errors = array();
        $rules = [
           'make_name' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'make_name' => 'Make Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $make_name =  $request->make_name;
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($make_name)
        {
            $where_array = [
                    'make_name'=>$make_name,
                ];
            $type_exist  = commonModel::check_value_exist('vehicle_make',$where_array,'id',$vehicle_make->id);

            if ($type_exist) {
               
             $errors[] ="Make Name is already exist";
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
                $destinationPath = 'uploads/vehicle_make';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_make/'.$vehicle_make->id.'/edit')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'make_name' => $make_name,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

           $updated = DB::table('vehicle_make')->where('id', $vehicle_make->id)->update($fieldvalues);

           return redirect('admin/vehicle_make/'.$vehicle_make->id.'/edit')->with('success', 'Update successfully');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\vehicle_make  $vehicle_make
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicle_make $vehicle_make)
    {
        //
        $vehicle_make = vehicle_make::find($vehicle_make->id);  // can also skip this line //
        
        $vehicle_make->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
       // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
