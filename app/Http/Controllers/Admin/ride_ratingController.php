<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\ride_rating;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;

class ride_ratingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    { 
       
        $page=1;
        $limit=10;
        $start=0;

        if(isset($request->limit))
        {
            $limit = $request->limit;
        }
        if(isset($request->page))
        {
            $page =  $request->page;
        }
        
        $start=($page-1)*$limit;
        
        $where_condition[] = 1;
        
        $startdate = '';
        if(isset($request->startdate) && $request->startdate!=''){

            $startdate  = date('Y-m-d',strtotime($request->startdate));
            
            $where_condition[]  =   "DATE(ride_rating.created_at)>='$startdate'";
        }
        $enddate = '';
        if(isset($request->enddate) && $request->enddate!=''){

            $enddate    = date('Y-m-d',strtotime($request->enddate));
            
            $where_condition[]  =   "DATE(ride_rating.created_at)<='$enddate'";
        }        
        
        $id = '';
        $type = '';
        $driver_name = '';
        $rider_name =  '';
        $ride_number ='';
        if(isset($request->id) && isset($request->type) ) {
            
            $id = $request->id;
            $type = $request->type;
            if($request->type == 'driver')
            {
                $where_condition[]  =   "ride_rating.driver_id = '$id'";
                $driver_name = commonModel::get_driver_name($id);
            
              
            }
            elseif($request->type == 'rider')
            {
                $where_condition[]  =   "ride_rating.rider_id = '$id'";
                $rider_name = commonModel::get_rider_name($id);
            }
            elseif($request->type == 'ride')
            {
               $where_condition[]  =   "ride_rating.ride_id = '$id'";
               $ride_number = commonModel::get_ride_number($id); 
            }
            
        }
        
        if(!empty($where_condition)) {
            $where = implode(' and ', $where_condition);
        }
        
        $searchcolumn  =  $request->searchcolumn;

        $searchkeyword = $request->searchkeyword;
        
        if($searchkeyword!='' &&   $searchcolumn!='')
        {
            $having_condition = "concat_ws(' ',$searchcolumn) like '%$searchkeyword%'";
        }
        else {
            
            if($searchkeyword!='')
            {
                $search_having_columns = ('ride_number,driver_name,rider_name,rating_to');

                $having_condition = "concat_ws(' ',$search_having_columns) like '%$searchkeyword%'";
            }
       }
       if(!isset($having_condition))
       {
           $having_condition = 1;
       }
       
       if(isset($request->sort) && isset($request->sortorder))
       {
           $sort = $request->sort;
           $sortorder = $request->sortorder;
       } else {
           $sort = 'ride_rating.id';
           $sortorder = 'desc';
       }
       
       $ride_ratings = DB::table('ride_rating')
            ->join('ride','ride_rating.ride_id','=','ride.id')
            ->leftjoin('driver', 'ride_rating.driver_id','=','driver.id')
            ->leftjoin('rider', 'ride_rating.rider_id','=','rider.id')   
            ->select('ride_rating.*','ride.ride_number','rider.full_name as rider_name','driver.full_name as driver_name')
            ->whereRaw("$where")
            ->havingRaw("$having_condition")
            ->orderBy($sort,$sortorder)
            ->Paginate($limit);
       
       $data = [
           'data' => $ride_ratings,
           'start'=>$start,
           'page'=>$page,
           'limit'=>$limit,
           'id'=>$id,
           'type'=>$type,
           'startdate'=>$startdate,
           'enddate'=>$enddate,
           'searchcolumn'=>$searchcolumn,
           'searchkeyword'=>$searchkeyword,
           'sort'=>$sort,
           'sortorder'=>$sortorder,
           'driver_name'=>$driver_name,
           'rider_name' => $rider_name,
           'ride_number' => $ride_number
       ];
       return view('admin.ride_rating.index',$data);      
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
         
         return view('admin.ride_rating.add',compact('dial_code','phone','is_activated'));
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
        
        $ride_ratings_code = $request->ride_ratings_code;
        
        $dial_code = $request->dial_code;
        
        $phone =  $request->phone;
        
        $address =  $request->address;
        
        $password =  md5($request->password);
        
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
            $email_exist  = commonModel::check_value_exist('ride_ratings',$where_array,'','');

            if ($email_exist) {
               
             $errors[] ="Email Already Exist";
            }
        }
        
        if($ride_ratings_code)
        {
            $where_array = [
                    'ride_ratings_code'=> $ride_ratings_code,
                ];
            $ride_ratings_code_exist  = commonModel::check_value_exist('ride_ratings',$where_array,'','');

            if ($ride_ratings_code_exist) {
               
             $errors[] ="Rider Code Already Exist";
            }
        }
        
        if($dial_code && $phone)
        {
            $where_array = [
                    'dial_code'=> $dial_code,
                    'phone'=> $phone,
                ];
            $phone_exist  = commonModel::check_value_exist('ride_ratings',$where_array,'','');

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
                $destinationPath = 'uploads/ride_ratings';

                $file->move($destinationPath,$file_name);
            }
        }
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/ride_ratings/create')->withErrors($validator)->withInput();
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
                       'ride_ratings_code'    => $ride_ratings_code,
            ];

        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }

            $insert = DB::table('ride_ratings')->insert($fieldvalues);

            return redirect('admin/ride_ratings/create')->with('success', 'Add successfully');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\ride_ratings  $ride_ratings
     * @return \Illuminate\Http\Response
     */
    public function show(ride_rating $ride_rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\ride_ratings  $ride_ratings
     * @return \Illuminate\Http\Response
     */
    public function edit(ride_rating $ride_rating)
    {
        //
        //print_r($ride_rating);
       
        $ride_rating_detail = ride_rating::get_ride_rating_detail($ride_rating->id);
       
        return view('admin.ride_rating.edit', compact('ride_rating_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\ride_ratings  $ride_ratings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ride_rating $ride_rating)
    {
        //
        $ride_rating = ride_rating::find($ride_rating->id);
         
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
        
        
        if($validator->fails() || !empty($errors))
        {
             foreach($errors as $error)
             {
                    $validator->errors()->add('field',$error);
             }
             
             return redirect('admin/ride_rating/'.$ride_rating->id.'/edit')->withErrors($validator)->withInput();
        }
        else
        {
            $fieldvalues = [
                       'status'   => $status,
            ];
            

           $updated = DB::table('ride_rating')->where('id', $ride_rating->id)->update($fieldvalues);

           return redirect('admin/ride_rating/'.$ride_rating->id.'/edit')->with('success', 'Update successfully');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\ride_ratings  $ride_ratings
     * @return \Illuminate\Http\Response
     */
    public function destroy(ride_rating $ride_rating)
    {
        //
        $ride_rating = ride_rating::find($ride_rating->id);  // can also skip this line //
        
        $ride_rating->delete();
        
        //return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
        return redirect('admin/ride_rating/')->with('success', 'deleted successfully');
    }
}
