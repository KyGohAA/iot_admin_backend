<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;
use Schema;
use App\Setting;
use App\Language;
use App\Currency;

class CurrenciesController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Currency Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete', 
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        //$this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    public function getCurrencyModelById($id=null)
    {
        $input = Request::input();
        $id = isset($id) ? $id : $input['currency_id'];
        $model = Currency::find($id);

        $array = array(
            'status_code' => ( isset($model) ? true : false ),
            'model'       => $model
        );

        return $array;
    }

    public function getIndex()
    {
        $page_variables = $this->page_variables;
        $i              =   1;
        $model          =   new Currency();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);

        return view(Setting::UI_VERSION.'commons.currencies.index', compact('model','i','.','cols','page_variables'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new Currency();
        return view(Setting::UI_VERSION.'commons.currencies.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $model = new Currency();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('CurrenciesController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->code.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = Currency::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.currencies.view', compact('model','page_variables'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;

        if (!$model = Currency::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.currencies.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = Currency::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('CurrenciesController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->code.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = Currency::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->code;
        $model->delete();
        return redirect()->action('CurrenciesController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }
}
