<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Admin\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;

class authController extends Controller
{

	public function __construct()
	{

		//$this->middleware('auth');

		//$this->middleware('admin')->only('login');

		// $this->middleware('admin')->except('login');
	}
	public function login(Request $request)
	{

		//        $value = $request->session()->all();
		//      
		//        print_r($value);

		if ($request->session()->has('admin_id')) {
			return redirect()->route('dashboard');
		} else {

			return view('admin.login');
		}

		// $value = $request->session()->all();

		// print_r($value);
		//        foreach (Auth::all() as $flight) {
		//        echo $flight->email;
		//        }
		// $users = DB::table('admin')->get();

		//       $users = DB::select('select * from admin');
		//
		//        foreach ($users as $user) {
		//            echo $user->email;
		//        }
		return view('admin.login');
	}


	public function checklogin(Request $request)
	{

		$email = $request->email;
		$password = md5($request->password);

		if ($email != '' && $password != '') {

			$row = DB::table('admin')->where([
				['email', '=', $email],
				['password', '=', $password],
			])->first();

			if ($row) {

				$request->session()->put('admin_id', $row->admin_id);
				$request->session()->put('admin_name', $row->fname . " " . $row->lname);
				if ($row->image) {
					$request->session()->put('admin_image', $row->image);
				}
				$request->session()->put('loggedin_time', time());

				//return json_encode(array('success'=>1,'msg'=>'login sucessfully')); 
				return response()->json(array('success' => 1, 'msg' => 'login sucessfully'), 200);

				//return "1"; die;
			} else {
				return response()->json(array('success' => 0, 'msg' => 'Invalid Credentials'), 200);
				//return "Invalid Credentials";die;
			}
		} else {
			return response()->json(array('success' => 0, 'msg' => 'All fields are required'), 200);
			// return "All fields are required";
		}
	}

	public function forgot(Request $request)
	{
		if ($request->session()->has('admin_id')) {
			return redirect()->route('dashboard');
		} else {

			return view('admin.forgot');
		}
	}

	public function logout(Request $request)
	{
		$request->session()->flush();
		return redirect()->route('login');
	}

	public function profile(Request $request)
	{
		$admin_id = $request->session()->get('admin_id');

		$admin_detail = DB::table('admin')->where([
			['admin_id', '=', $admin_id]
		])->first();

		return view('admin.profile', compact('admin_detail'));
	}

	public function update_profile(Request $request)
	{

		$admin_id = $request->session()->get('admin_id');

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

		$fname =  $request->fname;
		$lname =  $request->lname;
		$email =  $request->email;
		$contact =  $request->contact;
		$address = $request->address;
		$password = $request->password;
		$confirm_password = $request->confirm_password;

		$file = $request->file('image');

		if ($file) {
			$file_name = $file->getClientOriginalName();

			if ($file_name) {
				$file_name = time() . $file_name;
				//Move Uploaded File
				$destinationPath = 'uploads/admin/profile_image';

				$file->move($destinationPath, $file_name);
			}
		}

		if ($password != $confirm_password) {
			$errors[] = 'password and Confirm password not matched';
		}

		if ($validator->fails() || !empty($errors)) {
			foreach ($errors as $error) {
				$validator->errors()->add('field', $error);
			}

			return redirect('admin/profile')->withErrors($validator)->withInput();
		} else {
			$fieldvalues = [
				'fname' => $fname,
				'lname' => $lname,
				'email' => $email,
				'contact' => $contact,
				'address' => $address,
			];

			if ($password != '') {
				$fieldvalues['password'] = md5($password);
			}
			if (isset($file_name)) {
				$fieldvalues['image'] = $file_name;
			}


			$update = DB::table('admin')->where('admin_id', $admin_id)->update($fieldvalues);

			$request->session()->put('admin_name', $fname . " " . $lname);

			if (isset($file_name)) {
				$request->session()->put('admin_image', $file_name);
			}

			return redirect('admin/profile')->with('success', 'Update successfully');
		}
	}
}
