<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Schema;

use App\Setting;
use App\Language;
use App\Location;

class LocationsController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Locations Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        $this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    public function getIndex()
    {
        $page_variables = $this->page_variables;
        $i              =   1;
        $model          =   new Location();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);

        return view(Setting::UI_VERSION.'commons.locations.index', compact('model','i','page_variables','cols'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new Location();

        return view(Setting::UI_VERSION.'commons.locations.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $model = new Location();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('LocationsController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = Location::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.locations.view', compact('model','page_variables'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = Location::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.locations.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = Location::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('LocationsController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = Location::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->name;
        $model->delete();
        return redirect()->action('LocationsController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }
}
