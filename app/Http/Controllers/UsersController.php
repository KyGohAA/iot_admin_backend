<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Schema;

use App\User;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Language;
use App\UserAssign;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Users Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        //$this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    public function getIndex()
    {
        $i              =   1;
        $is_index       =   true;
        $page_variables =   $this->page_variables;
        $model          =   new User();
        $cols           =   $model->listing_header();
        $model = User::all();
        /*$model          =   $model->leftJoin('user_assigns','user_assigns.user_id','=','users.id')
                                    ->where('user_assigns.leaf_group_id','=',Company::get_group_id())
                                    ->listing()->paginate(Setting::paginate);
        */

        return view(Setting::UI_VERSION.'iot.layouts.index', compact('model','i','cols','is_index','page_variables'));
    }

    public function getNew()
    { 
        $page_variables =   $this->page_variables;
        $model = new User();
        
        return view(Setting::UI_VERSION.'commons.users.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $leaf_api = new LeafAPI();
        $model = new User();

        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $email_listing = $request->input('email'); 
        $is_success = false;
        $success_result = "";
        $failure_result = "";

        foreach ($email_listing as $email) {
            
            $result = $leaf_api->get_user_profile_by_email($email);
            if (!$result['status_code']) {

                $failure_result = $email.','.$failure_result;
                continue;

            }else {
                $model =  User::get_user_by_email($email);
                echo json_encode($result)."<br>";
                $input['photo']         =   isset($result['user_photo']) ?  $result['user_photo'] : '' ;
                $input['fullname']      =   isset($result['user_fullname']) ?  $result['user_fullname'] : '' ;
                $input['email']         =   isset($result['user_email']) ? $result['user_email'] : '';
                $input['store_id']      =   $request->input('store_id');
                $input['status']        =   true;
                $input['leaf_id_user']  =   isset($result['id_user']) ? $result['id_user'] : '' ;
                $input['user_group_id'] =   $request->input('user_group_id') !== null ? $request->input('user_group_id') : 0;
                $fdata = $model->get_or_create_user_account($input);
                $model->create_group($fdata->id, $input['user_group_id']);
                $success_result =  $input['email'].','.$success_result;
                $is_success = true;

            }

        }

        if($is_success){
            return redirect()->action('UsersController@getIndex', [$fdata->id])
                                    ->with(Setting::session_alert_icon, 'check')
                                    ->with(Setting::session_alert_status, 'success')
                                    ->with(Setting::session_alert_msg, $success_result.Language::trans(' access right successfully created.').($failure_result != '' ? Language::trans('While,').$failure_result.Language::trans(' not created.'): ''));
        }else{
            return redirect()->back()->withInput()
                                    ->with(Setting::session_alert_icon, 'ban')
                                    ->with(Setting::session_alert_status, 'danger')
                                    ->with(Setting::session_alert_msg, $result['error']);
        }
        
    }




    public function getEdit($id)
    {   
        $page_variables =   $this->page_variables;

        if (!$model = User::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        $user_assign_model =  UserAssign::get_model_by_user_id($model['id']);
        $model->user_group_id  = isset($user_assign_model['user_group_id']) ? $user_assign_model['user_group_id'] : 0;

        return view(Setting::UI_VERSION.'commons.users.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = User::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        //$model->remove_group($model->id);
        //$model->create_group($model->id, $request->input('user_group_id'));
        return redirect()->action('UsersController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->email.Language::trans(' was successfully updated.'));
    }
}
