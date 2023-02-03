<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Mail;

use App\Help;
use App\User;
use App\LeafAPI;
use App\Company;
use App\Setting;
use App\Language;
use App\PowerMeterModel\MeterInvoice;
use App\PowerMeterModel\MeterReading;
use App\UTransaction;
use App\PowerMeterModel\MeterRegister;

class OpencartUsersController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('User Login'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

       // $this->middleware('auth', ['except'=>['getLogin','getLogout','postLogin']]);
        $this->middleware('auth', ['only'=>['getSwitchGroup']]);
    }

    public function getLogin()
    {
        $page_variables = $this->page_variables;
        $model = new User();

//        $company = Company::get_model_by_leaf_group_id(Company::get_group_id());
;
        if (Auth::check()) {

            return redirect()->action('IOTUniversalsController@getDashboard');
        }

        return view(Setting::UI_VERSION.'iot.login.login', compact('model','page_variables','company'));
    }

    public function getLogout()
    {

        if (Auth::check()) {
            Auth::logout();
        }

        return redirect()->action('OpencartUsersController@getLogin');
    }

    public function  postLogin(Request $request)
    {
       if (!Auth::check()) {
            $leaf_api   =   new LeafAPI();
            //dd($request->all());
            $result     =   $leaf_api->login($request->all());
            $model      =   new User();
            //dd($result);
            if ($result['status_code'] == 1) {
                    $user   =   $model->get_or_create_user_account($result);
                    setcookie(LeafAPI::label_session_token, $result['session_token']);
                    $company = new Company();
                    $company->set_group_id(282);
                    Auth::loginUsingId($user->id, true);
                    return redirect()->action('IOTUniversalsController@getDashboard');
            } else {
                return redirect()->back()->withInput()
                                        ->with('status_level', 'danger')
                                        ->with('status_msg', Language::trans('Login Failed!'));
            }
            
       }else{
            return redirect()->action('IOTUniversalsController@getDashboard');
       }

        
    }

    public function getSwitchGroup(Request $request)
    {
        if (!isset($_COOKIE[LeafAPI::label_session_token])) {
            Auth::logout();
            return redirect()->action('OpencartUsersController@getLogin');
        }
        $company = new Company();
        $company->set_group_id($request->input('group_id'));
        return redirect()->action('IOTUniversalsController@getDashboard');
    }

}
