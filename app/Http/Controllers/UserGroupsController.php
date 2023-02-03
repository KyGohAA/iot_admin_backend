<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Schema;
use Auth;

use App\UserGroup;
use App\Setting;
use App\LeafAPI;
use App\Language;
use App\UserAssign;

class UserGroupsController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('User Groups Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        $this->page_title   =   Language::trans('User Groups Page');
        //$this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    public function getIndex()
    {
        $page_variables = $this->page_variables;
        $is_index       =   true;
        $i              =   1;
        $model          =   new UserGroup();
        $page_title     =   $this->page_title;
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);
        $new_file_link  =   'UserGroupsController@getNew';
        $is_model_page = true;
        return view(Setting::UI_VERSION.'commons.user_groups.index', compact('model','i','page_variables','cols','is_index','is_model_page'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new UserGroup();
        $permissions = $this->getResources();

        return view(Setting::UI_VERSION.'commons.user_groups.form', compact('model','page_variables','permissions'));
    }

    public function postNew(Request $request)
    {
        $model = new UserGroup();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $input = $request->all();
        $user_assign = $input['user_assign'];
        $user_assign['user_group_id'] = $model['id'];

        $model->save_form($input);
        UserAssign::saveOrUpdateUserAssign($user_assign);
        
        return redirect()->action('UserGroupsController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = UserGroup::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $permissions = $this->getResources();

        return view(Setting::UI_VERSION.'commons.user_groups.view', compact('model','page_variables','permissions'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = UserGroup::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $permissions = $this->getResources();
        $user_assign['user_list'] = json_encode(array_column( UserAssign::getByUserGroupId($model['id'])->toArray(),'user_id'));
        return view(Setting::UI_VERSION.'commons.user_groups.form', compact('model','page_variables','permissions','user_assign'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = UserGroup::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $input = $request->all();
        $user_assign = $input['user_assign'];
        $user_assign['user_group_id'] = $model['id'];
        unset($input['user_assign']);
        //dd($user_assign);
        $model->save_form($input);
        UserAssign::saveOrUpdateUserAssign($user_assign);
        return redirect()->action('UserGroupsController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = UserGroup::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->name;
        $model->delete();
        return redirect()->action('UserGroupsController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }

    private function getResources()
    {
        if(isset($_COOKIE['modules_cookie'])){
            $modules    =   json_decode($_COOKIE['modules_cookie'], true);
        }else{
            $modules = json_decode(Auth::User()->module_access);
        }
        
        $datas      =   array();
        $query      =   DB::table('resources');
        if($modules != null){
            if (in_array(LeafAPI::label_accounting, $modules)) {
                $query = $query->orWhere('billing','=',true);
            }
            if (in_array(LeafAPI::label_umrah, $modules)) {
                $query = $query->orWhere('umrah','=',true);
            }
            if (in_array(LeafAPI::label_power_meter, $modules)) {
                $query = $query->orWhere('power_meter','=',true);
            }
        }
        
        $query      =   $query->where('resource_status', '=', 1)->orderBy('resource_seq','asc');
        $groups     =   $query->groupBy('resource_name')->get();
        foreach($groups as $group){
            $datas[$group->resource_name] = DB::table('resources')->where('resource_status', '=', 1)
                                            ->where('resource_name','=',$group->resource_name)
                                            ->orderBy('resource_seq','asc')
                                            ->get();
        }
        return $datas;

    }

}
