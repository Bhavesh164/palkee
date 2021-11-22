<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\Admin\commonModel;

class configController extends Controller
{

    public function __construct()
    {

        //$this->middleware('auth');

        //$this->middleware('admin')->only('login');

        // $this->middleware('admin')->except('login');
    }

    public function commission(Request $request)
    {

        $config_values = commonModel::get_config();

        return view('admin.config.commission', compact('config_values'));
    }

    public function update_commission(Request $request)
    {

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



        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/config/commission')->withErrors($validator)->withInput();
        } else {
            $_REQUEST = $request->all();

            foreach ($_REQUEST as $key => $value) {

                if ($key == '_token' || $key == 'update') {
                    continue;
                }

                $update = DB::table('config')
                    ->updateOrInsert(
                        ['key_name' => $key],
                        ['key_value' => $value]
                    );
            }

            return redirect('admin/config/commission')->with('success', 'Update successfully');
        }
    }


    public function general(Request $request)
    {

        $config_values = commonModel::get_config();

        return view('admin.config.general', compact('config_values'));
    }

    public function update_general(Request $request)
    {

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



        if ($validator->fails() || !empty($errors)) {
            foreach ($errors as $error) {
                $validator->errors()->add('field', $error);
            }

            return redirect('admin/config/general')->withErrors($validator)->withInput();
        } else {
            $_REQUEST = $request->all();

            foreach ($_REQUEST as $key => $value) {

                if ($key == '_token' || $key == 'update') {
                    continue;
                }

                $update = DB::table('config')
                    ->updateOrInsert(
                        ['key_name' => $key],
                        ['key_value' => $value]
                    );
            }

            return redirect('admin/config/general')->with('success', 'Update successfully');
        }
    }

    public function about_page(Request $request)
    {
        $config_values = commonModel::get_page('about_page');

        return view('admin.config.about_page', compact('config_values'));
    }
    public function help_page(Request $request)
    {
        $config_values = commonModel::get_page('help_page');

        return view('admin.config.help_page', compact('config_values'));
    }
    public function terms_and_condition_page(Request $request)
    {
        $config_values = commonModel::get_page('terms_and_condition_page');

        return view('admin.config.terms_and_condition_page', compact('config_values'));
    }
    public function eula_page(Request $request)
    {
        $config_values = commonModel::get_page('eula_page');

        return view('admin.config.eula_page', compact('config_values'));
    }
    public function privacy_policy_page(Request $request)
    {
        $config_values = commonModel::get_page('privacy_policy_page');

        return view('admin.config.privacy_policy_page', compact('config_values'));
    }

    public function update_about_page(Request $request)
    {
        commonModel::update_page('about_page', $request->about_page);

        return redirect('admin/config/about_page')->with('success', 'Update successfully');
    }
    public function update_terms_and_condition_page(Request $request)
    {
        commonModel::update_page('terms_and_condition_page', $request->terms_and_condition_page);

        return redirect('admin/config/terms_and_condition_page')->with('success', 'Update successfully');
    }
    public function update_privacy_policy_page(Request $request)
    {
        commonModel::update_page('privacy_policy_page', $request->privacy_policy_page);

        return redirect('admin/config/privacy_policy_page')->with('success', 'Update successfully');
    }
    public function update_eula_page(Request $request)
    {
        commonModel::update_page('eula_page', $request->eula_page);

        return redirect('admin/config/eula_page')->with('success', 'Update successfully');
    }
    public function update_help_page(Request $request)
    {
        commonModel::update_page('help_page', $request->help_page);

        return redirect('admin/config/help_page')->with('success', 'Update successfully');
    }

    public function about_page_contents(Request $request)
    {
        $config_values = commonModel::get_page('about_page');

        return view('admin.config.view_about_page', compact('config_values'));
    }
    public function help_page_contents(Request $request)
    {
        $config_values = commonModel::get_page('help_page');

        return view('admin.config.view_help_page', compact('config_values'));
    }
    public function eula_page_contents(Request $request)
    {
        $config_values = commonModel::get_page('eula_page');

        return view('admin.config.view_eula_page', compact('config_values'));
    }
    public function terms_and_condition_page_contents(Request $request)
    {
        $config_values = commonModel::get_page('terms_and_condition_page');

        return view('admin.config.view_terms_and_condition_page', compact('config_values'));
    }
    public function privacy_policy_page_contents(Request $request)
    {
        $config_values = commonModel::get_page('privacy_policy_page');

        return view('admin.config.view_privacy_policy_page', compact('config_values'));
    }
}
