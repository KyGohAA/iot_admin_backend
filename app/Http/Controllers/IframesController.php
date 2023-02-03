<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Company;
use App\LeafAPI;
use App\Language;
use App\Setting;

class IframesController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Facility Booking Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];
        $this->middleware('auth_admin');
    }

    public function getBookingFacility()
    {
        $is_model_page  = false;
        $page_variables = $this->page_variables;
    	$leaf_api 		=	new LeafAPI();
		$session_token 	=	isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null;

        if(!isset($_COOKIE['session_token'])){
             return redirect()->action('DashboardsController@getUserProfile');
        }

    	//$user 			=	$session_token !=null ? $leaf_api->get_user_profile($session_token) : Auth::User;
		$app_secret 	=	'P5lsZKtSyQ3oV9mIQvzEDL1crszSKc4kO6i1ob8HfRLVE8RmU5Ms0RW11caQ0aXu';
		$url 			=	'https://cloud.leaf.com.my/web/book-facility.php?u='.Auth::user()->leaf_id_user.'&id='.Company::get_group_id().'&app_secret='.$leaf_api::app_secret.'&session_token='.$session_token.'&contentonly=1&headerfooter=none';
    	return view(Setting::UI_VERSION.'billings.iframes.booking_facility', compact('url','page_variables','is_model_page'));
    }
}
