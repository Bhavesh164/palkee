<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\vehicle_subtype;
use App\Model\Admin\vehicletype;
use App\Model\Admin\commonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class vehicle_subtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return "test index";
       
       //$vehicle_subtype = DB::table('vehicle_subtype')->orderBy('id','desc')->get();

       //$vehicle_type = vehicletype::all()->sortByDesc("id");
       $vehicle_subtype = vehicle_subtype::all_records();
       
       return view('admin.vehicle_subtype.index', ['data' => $vehicle_subtype]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicle_types = vehicletype::get_active_vehicle_type();
        
        return view('admin.vehicle_subtype.add',['vehicle_types'=>$vehicle_types]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->validate($request, [
//            'subtype_name' => 'required',
//        ]);
        $errors = array();
        $rules = [
           'type_id' => 'required',
           'subtype_name' => 'required',            
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'type_id' => 'Type Name',
            'subtype_name' => 'SubType Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $type_id =  $request->type_id;
        $subtype_name = $request->subtype_name;
        $base_price = $request->base_price;
        $min_price = $request->min_price;
        $per_min_price = $request->per_min_price;
        $per_km_price =  $request->per_km_price;
        $per_mile_price =  $request->per_mile_price; 
        
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($type_id && $subtype_name)
        {
            $where_array = [
                    'type_id' => $type_id,
                    'subtype_name'=>$subtype_name,
                ];
            $type_exist  = commonModel::check_value_exist('vehicle_subtype',$where_array,'','');

            if ($type_exist) {
               
             $errors[] ="Sub Type names is already exist with Vehicle Type";
            }
        }
        
//        $product = new Product;
//        $product->name = "Product 1";
//        $product->description = "Description 1";
//        $product->save();
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_subtype/create')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'type_id' => $type_id,
                       'subtype_name' => $subtype_name,
                       'min_price' => $min_price,
                       'base_price' => $base_price,
                       'per_minute_price' => $per_min_price,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
            ];

            if($per_km_price)
            {
                 $fieldvalues['per_km_price'] = $per_km_price;
            }
            if($per_mile_price)
            {
                 $fieldvalues['per_mile_price'] = $per_mile_price;
            }

           $insert = DB::table('vehicle_subtype')->insert($fieldvalues);
            
            return redirect('admin/vehicle_subtype/create')->with('success', 'Add successfully');
            
        }
         
         //vehicletype::create($fieldvalues);

        
        
        
       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_subtype  $vehicle_subtype
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_subtype $vehicle_subtype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\vehicle_subtype  $vehicle_subtype
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicle_subtype $vehicle_subtype)
    {
        //
         $vehicle_types = vehicletype::get_active_vehicle_type();
         
         return view('admin.vehicle_subtype.edit', compact('vehicle_subtype','vehicle_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\vehicle_subtype  $vehicle_subtype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vehicle_subtype $vehicle_subtype)
    {
        //
        $vehicle_subtype = vehicle_subtype::find($vehicle_subtype->id);
        $errors = array();
        $rules = [
           'type_id' => 'required',
           'subtype_name' => 'required',            
        ];
        $messages = [
            'required' => 'The :attribute is required.',
//            'type_id.required' => 'Type Name is required.',
        ];
        $customAttributes = [
            'type_id' => 'Type Name',
            'subtype_name' => 'SubType Name',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);
        
        $type_id =  $request->type_id;
        $subtype_name = $request->subtype_name;
        $base_price = $request->base_price;
        $min_price = $request->min_price;
        $per_min_price = $request->per_min_price;
        $per_km_price =  $request->per_km_price;
        $per_mile_price =  $request->per_mile_price; 
        
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        if($type_id && $subtype_name)
        {
            $where_array = [
                    'type_id' => $type_id,
                    'subtype_name'=>$subtype_name,
                ];
            $type_exist  = commonModel::check_value_exist('vehicle_subtype',$where_array,'id',$vehicle_subtype->id);

            if ($type_exist) {
               
             $errors[] ="Sub Type names is already exist with Vehicle Type";
            }
        }
        
//        $product = new Product;
//        $product->name = "Product 1";
//        $product->description = "Description 1";
//        $product->save();
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/vehicle_subtype/'.$vehicle_subtype->id.'/edit')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'type_id' => $type_id,
                       'subtype_name' => $subtype_name,
                       'min_price' => $min_price,
                       'base_price' => $base_price,
                       'per_minute_price' => $per_min_price,
                       'is_activated' => $is_activated,
                       'created_at' => date('Y-m-d H:i:s'),
            ];

            if($per_km_price)
            {
                 $fieldvalues['per_km_price'] = $per_km_price;
            }
            if($per_mile_price)
            {
                 $fieldvalues['per_mile_price'] = $per_mile_price;
            }

           
           $updated = DB::table('vehicle_subtype')->where('id', $vehicle_subtype->id)->update($fieldvalues);

           return redirect('admin/vehicle_subtype/'.$vehicle_subtype->id.'/edit')->with('success', 'Update successfully');

        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\vehicle_subtype  $vehicle_subtype
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicle_subtype $vehicle_subtype)
    {
        //
        
        $vehicle_subtype = vehicle_subtype::find($vehicle_subtype->id);  // can also skip this line //
        
        $vehicle_subtype->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
       // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
