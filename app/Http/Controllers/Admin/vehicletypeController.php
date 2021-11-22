<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\vehicletype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class vehicletypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //return "test index";
       
       $vehicle_type = DB::table('vehicle_type')->orderBy('id','desc')->get();
       //$vehicle_type = vehicletype::all()->sortByDesc("id");
      
       return view('admin.vehicletype.index', ['data' => $vehicle_type]);
   
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //return  $success;
        return view('admin.vehicletype.add');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type_name' => 'required|unique:vehicle_type',
        ]);
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        $file = $request->file('image'); 
          
        if($file)
        {
            $file_name = $file->getClientOriginalName();

            if($file_name)
            {
                $file_name = time().$file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/vehicle_type';

                $file->move($destinationPath,$file_name);
            }
        }
        
//        $product = new Product;
//        $product->name = "Product 1";
//        $product->description = "Description 1";
//        $product->save();

        $fieldvalues = [
                   'type_name' => $request->type_name,
                   'is_activated' => $is_activated,
                   'created_at' => date('Y-m-d H:i:s'),
        ];
        
        if(isset($file_name))
        {
            $fieldvalues['image'] = $file_name;
        }
         
         $insert = DB::table('vehicle_type')->insert($fieldvalues);
         
         //vehicletype::create($fieldvalues);

        return redirect('admin/vehicletype/create')->with('success', 'Add successfully');
        
            //return redirect('/')->with('success', 'Post Updated');

       // return 'test store';
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\vehicletype  $vehicletype
     * @return \Illuminate\Http\Response
     */
    public function show(vehicletype $vehicletype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\vehicletype  $vehicletype
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicletype $vehicletype)
    {
        
         return view('admin.vehicletype.edit', compact('vehicletype'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\vehicletype  $vehicletype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vehicletype $vehicletype)
    {
        // echo $vehicletype->id;
         
         $vehicletype = vehicletype::find($vehicletype->id);
//        $this->validate($request, [
//            'type_name' => 'required|unique:vehicle_type',
//        ]);
        
        if(isset($request->is_activated) && $request->is_activated!='') {
                $is_activated = 1;
        }
        else
        {
            $is_activated = 0;
        }
        
        $file = $request->file('image'); 
        if($file){
            $file_name = $file->getClientOriginalName();

            if($file_name)
            {
                $file_name = time().$file_name;
                //Move Uploaded File
                $destinationPath = 'uploads/vehicle_type';

                $file->move($destinationPath,$file_name);
            }
        }
        
        $vehicletype->type_name = $request->type_name;
        $vehicletype->is_activated = $is_activated;
        
        if(isset($file_name))
        {
           $vehicletype->image = $file_name;
        }
         
       // $insert = DB::table('vehicle_type')->insert($fieldvalues);
         
        $vehicletype->save();
         //vehicletype::create($fieldvalues);

        return redirect('admin/vehicletype/'.$vehicletype->id.'/edit')->with('success', 'Update successfully');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\vehicletype  $vehicletype
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicletype $vehicletype)
    {
     
       
        $vehicletype = vehicletype::find($vehicletype->id);  // can also skip this line //
        
        $vehicletype->delete();
        
        return response()->json(array('success'=>1,'msg'=>'deleted successfully'), 200);
       // return redirect('admin/vehicletype/')->with('success', 'deleted successfully');

    }
}
