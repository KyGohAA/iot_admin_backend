<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Schema;

use App\Setting;
use App\Language;
use App\City;

class CitiesController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Cities Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        //$this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    public function getCombobox(Request $request)
    {
        $listing = City::ofAvailable('status',true);
        $listing = $listing->where('state_id','=',$request->input('state_id'));
        $listing = $listing->get();
        $data[] = ['id'=>'', 'text'=>Language::trans('Please select city...')];
        foreach ($listing as $row) {
            $data[] = [
                        'id'    =>  $row->id,
                        'text'    =>  $row->name,
                        ];
        }

        return json_encode($data);
    }

    public function getIndex()
    {
        $page_variables = $this->page_variables;
        $i              =   1;
        $model          =   new City();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);
        $is_model_page  = true;
        return view(Setting::UI_VERSION.'commons.cities.index', compact('model','i','page_variables','cols','is_model_page'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new City();

        return view(Setting::UI_VERSION.'commons.cities.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $model = new City();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());

        return redirect()->action('CitiesController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $model->country_id = $model->display_relationed('state_id', 'country_id');

        return view(Setting::UI_VERSION.'commons.cities.view', compact('model','page_variables'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $model->country_id = (old('country_id') ? old('country_id'):$model->display_relationed('state_id', 'country_id'));

        return view(Setting::UI_VERSION.'commons.cities.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('CitiesController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->name;
        $model->delete();
        return redirect()->action('CitiesController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }
}
