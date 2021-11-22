<?php

namespace App\Http\Controllers\Admin;
//ini_set('memory_limit', '64M');
use App\Http\Controllers\Controller;
use App\Model\Admin\content_management;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\Admin\commonModel;


class content_managementController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function index() {
    //     $content_management = DB::table('content_management')->orderBy('id','desc')->get();
    //    //$vehicle_type = vehicletype::all()->sortByDesc("id");
    //     return view('admin.content_management.index', ['data' => $content_management]);
    // }


    public function index_dnd() {
        //  $content_management = DB::table('content_management')->orderBy('id','desc')->get();
        // //$vehicle_type = vehicletype::all()->sortByDesc("id");
        //  return view('admin.content_management.index', ['data' => $content_management]);

        // return view('admin.content_management.index', ['content_management' => Content_management::findOrFail($page_name)]);
    }



    public function privacy_policy() {

        if(isset($_POST["update"])){
            self::content_update();exit;
        }
        $content_management=content_management::fetch_content('privacy_policy');
        return view('admin.content_management.privacy_policy', ['content_management' =>$content_management]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($page_name) {
        $content_management=content_management::fetch_content($page_name);
        //$content_management=content_management::findOrFail($page_name);
        if(!$content_management){
            return abort(404);
            //resources/views/errors/404.blade.php
        }
        // $content_management=json_decode($content_management);

        // if(isset($_POST["update"])){
        //     self::content_update($page_name);
        // }
        
        //return view('admin.content_management.index', ['content_management' => Content_management::findOrFail($page_name)]);

        return view('admin.content_management.index', ['content_management' => $content_management]);
    }


    public function create() {
        return view('admin.content_management.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\content_management  $content_management
     * @return \Illuminate\Http\Response
     */



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\content_management  $content_management
     * @return \Illuminate\Http\Response
     */
    public function edit(content_management $content_management) {
        //
        return view('admin.content_management.edit', compact('content_management'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\content_management  $content_management
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, content_management $content_management) {

        $content_management = content_management::find($content_management->content_id);
         
        $errors = array();

        $rules = [
           'description' => 'required',
           'content_id' => 'required'
        ];


        $messages = [
            'required' => 'The :attribute is required.',
//             'type_id.required' => 'Type Name is required.',
        ];

        $customAttributes = [
            'description' => 'Privacy Policy',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);
        
//        $validator = Validator::make($request->all(), [
////            'subtype_name' => 'required',
//        ]);

        $description = $request->description;
       
        if($validator->fails() || !empty($errors)) {
            foreach($errors as $error) {
                $validator->errors()->add('field',$error);
            }

            return redirect('admin/content_management/'.$content_management->content_id.'/edit')->withErrors($validator)->withInput();
        }
        else {
            $promo_rate=preg_replace("/[^0-9]./", "",$promo_rate);
            $redeem_per_user=(int) $redeem_per_user;
            $fieldvalues = [
                'description' => $description
            ];

           $updated = DB::table('content_management')->where('content_id', $content_management->content_id)->update($fieldvalues);

           return redirect('admin/content_management/'.$content_management->content_id.'/edit')->with('success', 'Update successfully');            
        }
    }


    // public function content_update($page_name){
    public function content_update(Request $request, content_management $content_management){
        $error="";
        $errors = array();
        // $data=$request->input(); 
        // echo $page_name = content_management::find($content_management->page_name);
       

        //$description = trim($_POST['description']);
        $description = trim($request->description);
        $page_name = $request->page_name;        

        $rules = [
           'description' => 'required',
           'content_id' => 'required'
        ];


        $messages = [
            'required' => 'The :attribute is required.',
//             'type_id.required' => 'Type Name is required.',
        ];

        $customAttributes = [
            'description' => 'Privacy Policy',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if(strlen($description)==68){
            $error="Page Content is required";
        }
        else if($description=='') {
            $error="Page Content is required";
        }

        if($error==''){
        
            $fieldvalues = [
                'page_content'=> $description,
                'updated_at'=>date('Y-m-d H:i:s')
            ];

            $content_id = $_REQUEST['content_id'];
            $updated = DB::table('content_management')->where('content_id', $content_id)->update($fieldvalues);
            if($updated) {
                $success = "Update Successfully";
                return redirect('admin/content_management/'.$page_name)->with('success', $success);
            }
            else {
                $error = "Error updating";
            }            
        }
        if($error!=""){
            // return response()->view('errors.invalid-order', [], 500);
            // errors()->add('field',$error);
            // return redirect('admin/content_management/'.$page_name)->withErrors($err)->withInput();

            $validator = Validator::make([], []);
            $validator->errors()->add('some_field',$error);
            // $fails = $v->fails();
            // $failedMessages = $v->failed(); 

            //$validator->getMessageBag()->add('password', 'Password wrong');

            return redirect('admin/content_management/'.$page_name)->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\content_management  $content_management
     * @return \Illuminate\Http\Response
     */

    public function destroy(content_management $content_management) {
        // can also skip this line //
        $content_management = content_management::find($content_management->id);
        
        $content_management->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
        // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');
    }
}
