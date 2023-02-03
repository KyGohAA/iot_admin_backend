<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Request as Rqt;
use App\Setting;
use App\Company;
use App\Language;

class SettingsController extends Controller
{
	public function __construct()
	{
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Settings Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew',
                                    'language_listing' => ['english','malay','chinese']
                                ];

		//$this->shop_id = 1;
        //$this->middleware('acl', ['only'=>['getIndex']]);
        $this->middleware('auth_admin');
	}

    public function getIndex()
    {
        $is_model_page  = false;
        $page_variables = $this->page_variables;

        /*if (!$model = Company::find($this->shop_id)) {
            $model = new Company();
            $model->display_date($model->system_live_date) ;
        }*/

        if (!$model = Company::get_model_by_leaf_group_id(Company::get_group_id())) {
            $model = new Company();
        }else{
            $model->display_date($model->system_live_date) ;
        }

        return view(Setting::UI_VERSION.'commons.settings.index', compact('page_variables','model'/*,'is_model_page'*/));
    }

    public function postIndex(Request $request)
    {
        if (!$model = Company::get_model_by_leaf_group_id(Company::get_group_id())) {
            $model = new Company();
        }

        //dd($request->input());
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('SettingsController@getIndex', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' Settings was successfully saved.'));
    }

    public function getUtilityChargeIndex()
    {
        $page_variables = $this->page_variables;
    	if (!$model = Company::find($this->shop_id)) {
    		$model = new Company();
    	}
    	return view(Setting::UI_VERSION.'utility_charges.settings.index', compact('page_variables','model'));
    }

    public function postUtilityChargeIndex(Request $request)
    {
    	if (!$model = Company::find($this->shop_id)) {
    		$model = new Company();
    	}
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('SettingsController@getUtilityChargeIndex', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans('Settings was successfully saved.'));
    }


    public function updateSelectedGroup($leaf_group_id=null)
    {
        $return['status_code'] = false;

        $input = Rqt::input();
        $leaf_group_id = isset($leaf_group_id) ? $leaf_group_id : $input['leaf_group_id'];

        $return['input'] = $leaf_group_id;
        if($leaf_group_id != null)
        {
            Setting::setCompany($leaf_group_id);
            $return['status_code'] = true;
        }

        return json_encode($return);
        
    }
}
