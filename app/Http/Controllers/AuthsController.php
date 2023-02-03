<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\User;
use App\LeafAPI;
use App\Company;
use App\Setting;

class AuthsController extends Controller
{
    public function __construct()
    {
        $this->label_session_token = 'session_token';
    }

    public function getAdmin()
    {
        $leaf_api = new LeafAPI();
        echo "<pre>";
        $leaf_api = $leaf_api->peter_login();
        print_r($leaf_api);
        Setting::setCompany(285);
        $api = new LeafAPI();
        $api->set_cookie_modules();
        // echo $leaf_api['id_user'].'<br>';
        $user = User::where('leaf_id_user','=',$leaf_api['id_user'])->first();
        Auth::loginUsingId($user->id, true);
        setcookie($this->label_session_token, $leaf_api['session_token']);
    }

    public function getSunwayTester()
    {
        $leaf_api = new LeafAPI();
        echo "<pre>";
        $leaf_api = $leaf_api->sunway_tester();
        print_r($leaf_api);
        // echo $leaf_api['id_user'].'<br>';
        $user = User::where('leaf_id_user','=',$leaf_api['id_user'])->first();
        Auth::loginUsingId($user->id, true);
        setcookie($this->label_session_token, $leaf_api['session_token']);

        Setting::setCompany(285);
        $api = new LeafAPI();
        $api->set_cookie_modules();
        print_r("Finish");
        dd($api->get_modules());
    }


    public function getLogin(Request $request)
    {
        $session_token = $request->input('session_token');
        $leaf_api = new LeafAPI();
        $model = new User();
        $result = $leaf_api->get_user_profile($session_token);
   
        if ($result['status_code'] == -1) {
            $data['status'] = false;
            $data['status_msg'] = $result['error'];
        } else {
            $result['leaf_id_user'] = $result['id_user'];
            unset($result['id_user']);
            $user = $model->get_or_create_user_account($result);
            setcookie($this->label_session_token, $session_token);
            Auth::loginUsingId($user->id, true);

            $data['status']         =   true;
            $data['status_msg']     =   'Authorization successfully.';
        }

        return json_encode($data);
    }

    public function getCheckLogin()
    {
    	$data['status'] 		=	false;
    	$data['status_msg'] 	=	'Not Logged';
    	if (Auth::check()) {
	    	$data['status'] 	=	true;
	    	$data['status_msg'] =	'Logged In';
    	}
    	return json_encode($data);
    }

    public function getLogout()
    {
        Auth::logout();
        $data['status']         =   true;
        $data['status_msg']     =   'Logout successfully.';
        return json_encode($data);
    }
}
